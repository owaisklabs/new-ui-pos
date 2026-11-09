<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchasePaymentDetail;
use App\Models\Supplier;
use App\Services\UnitConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'items.book']);
        if ($request->has('query')) {
            $filters = $request->query('query');

            if (!empty($filters['invoice_no'])) {
                $query->where('invoice_no', 'LIKE', '%' . $filters['invoice_no'] . '%');
            }

                if (!empty($filters['book_id'])) {
                    $query->whereHas('items', function ($query) use ($filters) {
                        $query->where('book_id', $filters['book_id']);
                    });
                }

            if (!empty($filters['from_date'])) {
                $query->whereDate('created_at', '>=', $filters['from_date']);
            }

            if (!empty($filters['to_date'])) {
                $query->whereDate('created_at', '<=', $filters['to_date']);
            }
        }
        $totals = [
            'total_amount_sum' => (clone $query)->sum(DB::raw('total_amount + COALESCE(expense, 0)')),
            'paid_amount_sum' => (clone $query)->sum('paid_amount'),
        ];

        $purchases = $query->latest()->paginate(50);
        $books = Book::all();
        return view('purchase.index', compact('purchases', 'books', 'totals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $books = Book::all();
        return  view('purchase.create',compact('suppliers','books'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.book_id' => 'required|exists:books,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $totalAmount = 0;

            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'invoice_no' => $request->invoice_no,
                'purchase_date' => $request->purchase_date,
                'status' => $request->status,
                'payment_type' => $request->payment_type,
                'expense' => $request->expense,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {

                $book = Book::findOrFail($item['book_id']);
                $baseQty = $item['quantity'];
                $unitCost = $item['unit_cost'];
                $lineTotal = $item['quantity'] * $item['unit_cost'];
                $dual = [];

                if ($book->isDualUnit()) {
                    $rolls = (int) $item['quantity'];
                    if ($rolls < 1) {
                        throw new \InvalidArgumentException("Enter a roll quantity of at least 1 for \"{$book->title}\".");
                    }
                    $factor = (float) $book->conversion_factor;
                    $baseQty = UnitConversion::toBase($rolls, 0, $factor);
                    $lineTotal = round($rolls * $item['unit_cost'], 2);
                    $unitCost = round($item['unit_cost'] / $factor, 2);
                    $dual = [
                        'purchase_qty' => $rolls,
                        'purchase_unit' => $book->purchase_unit ?: 'Roll',
                        'conversion_factor' => $factor,
                        'purchase_unit_cost' => $item['unit_cost'],
                    ];
                }

                $totalAmount += $lineTotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'book_id' => $item['book_id'],
                    'quantity' => $baseQty,
                    'unit_cost' => $unitCost,
                    'line_total' => $lineTotal,
                ] + $dual);

                $inventory = Inventory::firstOrCreate(
                    ['book_id' => $item['book_id']],
                    ['quantity' => 0, 'reorder_level' => 10]
                );

                $inventory->increment('quantity', $baseQty);
            }

            $payable = round($totalAmount + (float) $request->expense, 2);
            $isCredit = $request->payment_type === 'credit';

            $purchase->update([
                'total_amount' => $totalAmount,
                'paid_amount' => $isCredit ? 0 : $payable,
            ]);

            if (!$isCredit && $payable > 0) {
                PurchasePaymentDetail::create([
                    'purchase_id' => $purchase->id,
                    'amount_received' => $payable,
                    'payment_type' => $request->payment_type,
                    'remarks' => $request->remarks,
                    'created_by' => auth()->id(),
                ]);
            }
        });
        return redirect()->route('purchase.index')->with('success', 'Purchase created successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['items.book', 'supplier', 'user', 'paymentDetails.user']);

        return view('purchase.detail', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        $books = Book::all();
        return view('purchase.update', compact('purchase', 'suppliers', 'books'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        $data = [
            'supplier_id' => $request->supplier_id,
            'invoice_no' => $request->invoice_no,
            'purchase_date' => $request->purchase_date,
            'status' => $request->status,
            'payment_type' => $request->payment_type,
        ];

        if ($request->filled('payment_type') && $request->payment_type !== $purchase->payment_type) {
            $payable = round((float) $purchase->total_amount + (float) $purchase->expense, 2);
            $data['paid_amount'] = $request->payment_type === 'credit' ? 0 : $payable;
        }

        $purchase->update($data);
        return redirect()->route('purchase.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchase.index')->with('success', 'Purchase deleted successfully');
    }

    public function paymentPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_id' => 'required|exists:purchases,id',
            'receive_amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:cash,online,cheque',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $purchase = Purchase::findOrFail($request->purchase_id);

        if (in_array($purchase->status, [Purchase::STATUS_CANCELLED, Purchase::STATUS_RETURNED], true)) {
            return response()->json(['message' => 'This purchase cannot be paid.'], 422);
        }

        $amount = round((float) $request->receive_amount, 2);
        $remaining = $purchase->balanceAmount();

        if ((int) round($amount * 100) > (int) round($remaining * 100)) {
            return response()->json(['message' => 'Amount is greater than the remaining balance.'], 422);
        }

        DB::transaction(function () use ($purchase, $request, $amount) {
            $purchase->paid_amount = round((float) $purchase->paid_amount + $amount, 2);
            $purchase->save();

            $detail = new PurchasePaymentDetail();
            $detail->purchase_id = $purchase->id;
            $detail->amount_received = $amount;
            $detail->payment_type = $request->payment_type;
            $detail->remarks = $request->remark;
            if ($request->payment_type === 'online') {
                $detail->account_number = $request->extra_value;
            }
            if ($request->payment_type === 'cheque') {
                $detail->cheque_number = $request->extra_value;
            }
            $detail->created_by = auth()->id();
            $detail->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully.',
            'redirect_url' => route('purchase.index'),
        ]);
    }
}

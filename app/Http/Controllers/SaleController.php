<?php

namespace App\Http\Controllers;

use App\Exceptions\StockException;
use App\Models\Book;
use App\Models\Inventory;
use App\Services\UnitConversion;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalesPaymentDetail;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Sale::has('items')
            ->with(['items', 'customer'])
            ->withSum('items as total_items_quantity', 'quantity'); // ✅ per sale

        if ($request->has('query')) {
            $filters = $request->query('query');

            if (!empty($filters['invoice_no'])) {
                $query->where('invoice_no', 'LIKE', '%' . $filters['invoice_no'] . '%');
            }

            if (!empty($filters['customer_id'])) {
                $query->where('customer_id', $filters['customer_id']);
            }

            if (!empty($filters['from_date'])) {
                $query->whereDate('created_at', '>=', $filters['from_date']);
            }

            if (!empty($filters['to_date'])) {
                $query->whereDate('created_at', '<=', $filters['to_date']);
            }
        }

        $totals = [
            'total_amount_sum' => (clone $query)->toBase()->sum('total_amount'),
            'paid_amount_sum'  => (clone $query)->toBase()->sum('paid_amount'),
        ];

        $sales = $query->latest()->paginate(50);

        return view('sales.index', compact('sales', 'totals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales.create');
    }


        public function store(Request $request)
        {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'cartData' => 'required|array|min:1',
                'cartData.*.book_id' => 'required|integer|exists:books,id',
                'cartData.*.qty' => 'nullable|numeric|min:0',
                'cartData.*.roll_qty' => 'nullable|integer|min:0',
                'cartData.*.feet_qty' => 'nullable|integer|min:0',
                'cartData.*.price' => 'required|numeric|min:0',
                'cartData.*.discount' => 'nullable|numeric|min:0|max:100',
                'cartData.*.line_total' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            try {

                $lastSale = Sale::latest('id')->first();
                $nextId = $lastSale ? $lastSale->id + 1 : 1;

                $invoiceNumber = str_pad($nextId, 6, '0', STR_PAD_LEFT);

                $paidAmount = ($request->sales_status === Sale::STATUS_PAID) ? $request->amount : 0;

                $sale = Sale::create([
                    'invoice_no'   => $invoiceNumber,
                    'sale_date'    => now(),
                    'total_amount' => $request->amount,
                    'paid_amount'  => $paidAmount,
                    'customer_id'  => $request->customer_id,
                    'created_by'   => auth()->id(),
                    'status'       => $request->sales_status,
                ]);

                foreach ($request->cartData as $item) {

                    $book = Book::findOrFail($item['book_id']);
                    $unitPrice = $item['price'];
                    $cost_price = $item['cost_price'];
                    $discount = $item['discount'] ?? 0;
                    $afterDiscount = $unitPrice * (1 - ($discount / 100));
                    $dual = [];

                    if ($book->isDualUnit()) {
                        $rolls = (int) ($item['roll_qty'] ?? 0);
                        $feet = (int) ($item['feet_qty'] ?? 0);
                        $quantity = UnitConversion::toBase($rolls, $feet, $book->conversion_factor);

                        if ($quantity <= 0) {
                            throw new StockException("Enter Roll or Feet quantity for \"{$book->title}\".");
                        }

                        $available = (float) Inventory::where('book_id', $book->id)->lockForUpdate()->value('quantity');
                        if ($quantity > $available) {
                            throw new StockException(
                                "Insufficient stock for \"{$book->title}\". Available: {$available} " . ($book->base_unit ?: 'Feet') . ", requested: {$quantity}."
                            );
                        }

                        $dual = [
                            'roll_qty' => $rolls,
                            'feet_qty' => $feet,
                            'conversion_factor' => $book->conversion_factor,
                            'purchase_unit' => $book->purchase_unit ?: 'Roll',
                            'base_unit' => $book->base_unit ?: 'Feet',
                        ];
                        $lineTotalAfterDiscount = round($quantity * $afterDiscount, 2);
                    } else {
                        $quantity = $item['qty'] ?? 0;
                        if ($quantity < 1) {
                            throw new StockException("Quantity must be at least 1 for \"{$book->title}\".");
                        }
                        $lineTotalAfterDiscount = $item['line_total'];
                    }

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'book_id' => $item['book_id'],
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'cost_price' => $cost_price,
                        'sale_price' => $afterDiscount,
                        'line_total' => $quantity * $unitPrice,
                        'discount' => $discount,
                        'total' => $lineTotalAfterDiscount,
                    ] + $dual);
                    $inventory = Inventory::where('book_id', $item['book_id'])->first();
                    if ($inventory) {
                        $inventory->decrement('quantity', $quantity);
                    }
                }

                DB::commit();
                $data = $sale->load('items');
                $request->user()->cart()->detach();
                return response()->json(['message' => 'success', 'data' => $data], 201);

            } catch (StockException $e) {

                DB::rollBack();
                return response()->json(['message' => $e->getMessage()], 422);

            } catch (\Exception $e) {

                DB::rollBack();
                return response()->json([
                    'message' => 'error',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

    public function printA5Receipt($id)
    {
        $sale = Sale::with('items.book', 'customer')->findOrFail($id);
        $setting = Setting::query()->first();
        $invoiceType = $setting?->invoice_type ?? 'thermal';

        $viewName = $invoiceType === 'a5' ? 'reports.sale-receipt-a5' : 'reports.sale-receipt-thermal';

        return view($viewName, compact('sale'));
    }


    /**
     * Display the specified resource.
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        return view('sales.detail', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        return view('sales.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $sale->items()->delete();

        $sale->delete();

        return back()->with('success', 'Sale and related items deleted');

    }

    public function paymentReceive(Request $request)
    {

        $saleId = $request->sale_id;

        $receiveAmount = $request->receive_amount;

        $sale = Sale::findOrFail($saleId);

        $sale->paid_amount = $sale->paid_amount + $receiveAmount;

        if ($sale->paid_amount >= $sale->total_amount) {
            $sale->status = 'paid';
        } elseif ($sale->paid_amount > 0) {
            $sale->status = 'open';
        }

        $sale->save();

        $salesDetails = new SalesPaymentDetail();
        $salesDetails->sales_id = $saleId;
        $salesDetails->amount_received = $receiveAmount;
        $salesDetails->payment_type = $request->payment_type;
        $salesDetails->remarks = $request->remark;
        if ($request->payment_type == "online") {
            $salesDetails->account_number = $request->extra_value;
        }
        if ($request->payment_type == "cheque") {
            $salesDetails->cheque_number = $request->extra_value;
        }
        $salesDetails->created_by = Auth::id();
        $salesDetails->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment received successfully.',
            'sale' => $sale,
            'redirect_url' => route('sales.index')
        ]);


    }
}

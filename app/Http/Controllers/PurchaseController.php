<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Services\UnitConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $purchases = $query->latest()->paginate(50);
        $books = Book::all();
        return view('purchase.index',compact('purchases', 'books'));
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

            $purchase->update(['total_amount' => $totalAmount]);
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
        $purchase->load(['items.book', 'supplier', 'user']);

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
        $purchase->update([
                'supplier_id' => $request->supplier_id,
                'invoice_no' => $request->invoice_no,
                'purchase_date' => $request->purchase_date,
                'status' => $request->status,
            ]
        );
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
        //
    }
}

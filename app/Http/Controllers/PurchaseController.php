<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
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
        $query = Purchase::with('supplier','items');
        if ($request->has('query')) {
            $filters = $request->query('query');

            if (!empty($filters['invoice_no'])) {
                $query->where('invoice_no', 'LIKE', '%' . $filters['invoice_no'] . '%');
            }

                if (!empty($filters['book_id'])) {
                    $query->whereHas('items', function ($query) use ($filters) {
                        dd($filters['book_id']);
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

                $lineTotal = $item['quantity'] * $item['unit_cost'];
                $totalAmount += $lineTotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'book_id' => $item['book_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $lineTotal,
                ]);

                $inventory = Inventory::firstOrCreate(
                    ['book_id' => $item['book_id']],
                    ['quantity' => 0, 'reorder_level' => 10]
                );

                $inventory->increment('quantity', $item['quantity']);
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

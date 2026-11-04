<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returns = SaleReturn::with(['sale.customer', 'items'])
            ->latest()
            ->paginate(50);

        return view('return-sales.index', compact('returns'));
    }

    public function create(Sale $sale)
    {
        $sale->load(['items.book', 'customer']);

        $hasReturnableItems = $sale->items->contains(fn ($item) => $item->returnableQuantity() > 0);

        if (!$hasReturnableItems) {
            return redirect()
                ->route('sales.show', $sale)
                ->with('error', 'All items from this sale have already been returned.');
        }

        return view('return-sales.create', compact('sale'));
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
            'sale_id' => 'required|exists:sales,id',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.sale_item_id' => 'required|exists:sale_items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::with('items')->findOrFail($request->sale_id);

            $returnItems = collect($request->items)->filter(fn ($item) => (int) $item['quantity'] > 0);

            if ($returnItems->isEmpty()) {
                throw new \Exception('Please enter return quantity for at least one item.');
            }

            $lastReturn = SaleReturn::latest('id')->first();
            $nextId = $lastReturn ? $lastReturn->id + 1 : 1;
            $returnInvoiceNo = 'RET-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,
                'return_invoice_no' => $returnInvoiceNo,
                'total_return_amount' => 0,
                'notes' => $request->notes,
            ]);

            $totalReturnAmount = 0;

            foreach ($returnItems as $item) {
                $saleItem = $sale->items->firstWhere('id', $item['sale_item_id']);

                if (!$saleItem) {
                    throw new \Exception('Invalid sale item selected.');
                }

                $returnQty = (int) $item['quantity'];
                $availableQty = $saleItem->returnableQuantity();

                if ($returnQty > $availableQty) {
                    throw new \Exception(
                        "Return quantity for \"{$saleItem->book->title}\" exceeds available quantity ({$availableQty})."
                    );
                }

                $unitPrice = $saleItem->sale_price;
                $lineTotal = round($returnQty * $unitPrice, 2);

                SaleReturnItem::create([
                    'sale_return_id' => $saleReturn->id,
                    'sale_item_id' => $saleItem->id,
                    'book_id' => $saleItem->book_id,
                    'quantity' => $returnQty,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ]);

                Inventory::where('book_id', $saleItem->book_id)
                    ->increment('quantity', $returnQty);

                $totalReturnAmount += $lineTotal;
            }

            $saleReturn->update([
                'total_return_amount' => $totalReturnAmount,
            ]);
            $sale->total_amount = max(0, $sale->total_amount - $totalReturnAmount);
            $sale->paid_amount = max(0, $sale->paid_amount - $totalReturnAmount);

            $allFullyReturned = $sale->items->every(function ($item) {
                return $item->returnableQuantity() === 0;
            });

            if ($allFullyReturned) {
                $sale->status = Sale::STATUS_REFUND;
            }

            $sale->save();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Return processed successfully.',
                    'return_amount' => $totalReturnAmount,
                    'redirect_url' => route('return-sales.show', $saleReturn),
                ]);
            }

            return redirect()
                ->route('return-sales.show', $saleReturn)
                ->with('success', 'Return processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleReturn  $return_sale
     * @return \Illuminate\Http\Response
     */
    public function show(SaleReturn $return_sale)
    {
        
        $return_sale->load(['sale.customer', 'items.book', 'items.saleItem']);
        return view('return-sales.show', compact('return_sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleReturn $saleReturn)
    {
        $saleReturn->load(['sale.customer', 'items.book', 'items.saleItem']);

        return view('return-sales.edit', compact('saleReturn'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleReturn $saleReturn)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.sale_item_id' => 'required|exists:sale_items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $saleReturn->update($request->all());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}

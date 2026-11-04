<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $returns = PurchaseReturn::with(['purchase.supplier', 'items'])
            ->latest()
            ->paginate(50);

        return view('return-purchases.index', compact('returns'));
    }

    public function create(Purchase $purchase)
    {
        $purchase->load(['items.book', 'supplier']);

        $hasReturnableItems = $purchase->items->contains(fn ($item) => $item->returnableQuantity() > 0);

        if (!$hasReturnableItems) {
            return redirect()
                ->route('purchase.show', $purchase)
                ->with('error', 'All items from this purchase have already been returned.');
        }

        return view('return-purchases.create', compact('purchase'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.purchase_item_id' => 'required|exists:purchase_items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $purchase = Purchase::with('items.book')->findOrFail($request->purchase_id);

            $returnItems = collect($request->items)->filter(fn ($item) => (int) $item['quantity'] > 0);

            if ($returnItems->isEmpty()) {
                throw new \Exception('Please enter return quantity for at least one item.');
            }

            $lastReturn = PurchaseReturn::latest('id')->first();
            $nextId = $lastReturn ? $lastReturn->id + 1 : 1;
            $returnInvoiceNo = 'PRET-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            $purchaseReturn = PurchaseReturn::create([
                'purchase_id' => $purchase->id,
                'return_invoice_no' => $returnInvoiceNo,
                'total_return_amount' => 0,
                'notes' => $request->notes,
            ]);

            $totalReturnAmount = 0;

            foreach ($returnItems as $item) {
                $purchaseItem = $purchase->items->firstWhere('id', $item['purchase_item_id']);

                if (!$purchaseItem) {
                    throw new \Exception('Invalid purchase item selected.');
                }

                $returnQty = (int) $item['quantity'];
                $availableQty = $purchaseItem->returnableQuantity();

                if ($returnQty > $availableQty) {
                    throw new \Exception(
                        "Return quantity for \"{$purchaseItem->book->title}\" exceeds available quantity ({$availableQty})."
                    );
                }

                $inventory = Inventory::where('book_id', $purchaseItem->book_id)->first();

                if (!$inventory || $inventory->quantity < $returnQty) {
                    throw new \Exception(
                        "Insufficient inventory for \"{$purchaseItem->book->title}\" to process this return."
                    );
                }

                $unitCost = $purchaseItem->unit_cost;
                $lineTotal = round($returnQty * $unitCost, 2);

                PurchaseReturnItem::create([
                    'purchase_return_id' => $purchaseReturn->id,
                    'purchase_item_id' => $purchaseItem->id,
                    'book_id' => $purchaseItem->book_id,
                    'quantity' => $returnQty,
                    'unit_cost' => $unitCost,
                    'total_price' => $lineTotal,
                ]);

                $inventory->decrement('quantity', $returnQty);

                $totalReturnAmount += $lineTotal;
            }

            $purchaseReturn->update([
                'total_return_amount' => $totalReturnAmount,
            ]);

            $purchase->total_amount = max(0, $purchase->total_amount - $totalReturnAmount);

            $allFullyReturned = $purchase->items->every(function ($item) {
                return $item->returnableQuantity() === 0;
            });

            if ($allFullyReturned) {
                $purchase->status = Purchase::STATUS_RETURNED;
            }

            $purchase->save();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase return processed successfully.',
                    'return_amount' => $totalReturnAmount,
                    'redirect_url' => route('return-purchases.show', $purchaseReturn),
                ]);
            }

            return redirect()
                ->route('return-purchases.show', $purchaseReturn)
                ->with('success', 'Purchase return processed successfully.');

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

    public function show(PurchaseReturn $return_purchase)
    {
        $return_purchase->load(['purchase.supplier', 'items.book', 'items.purchaseItem']);

        return view('return-purchases.show', compact('return_purchase'));
    }
}

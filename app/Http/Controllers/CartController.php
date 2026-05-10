<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCart(Request $request){
        return response()->json([
            "status"  => JsonResponse::HTTP_OK,
            "message" => "cart Fetch successfully",
            "data"  => $request->user()->cart()->get(),
        ], JsonResponse::HTTP_OK);

    }

    public function storeCart(Request $request){
        $book = Book::find($request->bookId);

        $cartItem = $request->user()
            ->cart()
            ->where('id', $request->bookId)
            ->first();
        if ($cartItem) {
            return $this->incrementCartItem($cartItem, $book);
        }
        return $this->addNewCartItem($request, $book);

    }
    public function delete(Request $request): JsonResponse
    {
        $request->user()->cart()->detach($request->product_id);

        return response()->json(['success' => true]);
    }
    public function empty(Request $request): JsonResponse
    {
        $request->user()->cart()->detach();

        return response()->json(['success' => true]);
    }
    public function changeQty(Request $request): JsonResponse
    {
        $product = Book::findOrFail($request->product_id);

        $cartItem = $request->user()
            ->cart()
            ->where('id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => true]);
        }

        // Validate stock availability
//        if ($product->quantity < $request->quantity) {
//            return response()->json([
//                'message' => __('cart.available', ['quantity' => $product->quantity]),
//            ], 400);
//        }

        $cartItem->pivot->quantity = $request->quantity;
        $cartItem->pivot->discount = $request->discount;
        $cartItem->pivot->save();

        return response()->json(['success' => true]);
    }

    private function incrementCartItem($cartItem, Book $book): JsonResponse
    {
//        if ($book->quantity <= $cartItem->pivot->quantity) {
//            return response()->json([
//                'message' => "Qty not available"
//            ], 400);
//        }

        $cartItem->pivot->increment('quantity');

        return response()->json(['success' => true]);
    }
    private function addNewCartItem(Request $request, Book $book): JsonResponse
    {
//        dd($book->quantity );
//        if ($book->quantity < 1) {
//            return response()->json([
//                'message' => "Out of Stock",
//            ], 400);
//        }

        $request->user()->cart()->attach($book->id, ['quantity' => 1]);

        return response()->json(['success' => true]);
    }
}

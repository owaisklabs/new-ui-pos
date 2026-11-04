<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::latest()->paginate(50);
        return view('book.index',compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        return  view('book.create',compact('authors','publishers'));
    }


    public function store(Request $request)
    {
        
        $request->validate([
            'sku' => 'required',
            'bar_code' => 'required',
            'title' => 'required',
            'subtitle' => 'nullable',
            'description' => 'nullable',
            'cost_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ] + $this->unitRules(), $this->unitMessages());
        $data = $this->perBaseUnitPrices($request, $request->except('cover_image', 'author_id'));
        if ($request->hasFile('cover_image')) {
            $imageName = time().'.'.$request->cover_image->extension();
            $request->cover_image->storeAs('book_covers', $imageName, 'public');

            $data['cover_image'] = 'book_covers/'.$imageName;
        }


        $book = Book::create($data);
        // Many-to-many pivot insert
        $book->authors()->attach($request->author_id);
        return  redirect()->route('book.index');

    }

    public function show(Book $book)
    {
        //
    }


    public function edit(Book $book)
    {
        
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('book.update',compact('book','authors','publishers'));
    }


    public function update(Request $request, Book $book)
    {
        $request->validate([
            'sku' => 'required',
            'bar_code' => 'required',
            'title' => 'required',
            'subtitle' => 'nullable',
            'publisher_id' => 'nullable|exists:publishers,id',
            'author_id' => 'nullable|array',
            'author_id.*' => 'nullable|exists:authors,id',
            'published_at' => 'nullable',
            'description' => 'nullable',
            'cost_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'pages' => 'nullable',
            'language' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ] + $this->unitRules(), $this->unitMessages());
        $data = $this->perBaseUnitPrices($request, $request->except('cover_image', 'author_id'));
        if ($request->hasFile('cover_image')) {
            $imageName = time().'.'.$request->cover_image->extension();
            $request->cover_image->storeAs('book_covers', $imageName, 'public');

            $data['cover_image'] = 'book_covers/'.$imageName;
        }
        $book->update($data);
        return redirect()->route('book.index');
    }


    private function unitRules(): array
    {
        return [
            'conversion_factor' => 'nullable|numeric|min:2|required_with:purchase_unit,base_unit',
            'purchase_unit' => 'nullable|string|max:32|required_with:conversion_factor,base_unit',
            'base_unit' => 'nullable|string|max:32|required_with:conversion_factor,purchase_unit',
        ];
    }

    private function perBaseUnitPrices(Request $request, array $data): array
    {
        if ($request->filled('conversion_factor')) {
            $factor = (float) $request->input('conversion_factor');
            $data['cost_price'] = round($request->input('cost_price') / $factor, 2);
            $data['sell_price'] = round($request->input('sell_price') / $factor, 2);
        }

        return $data;
    }

    private function unitMessages(): array
    {
        return [
            'conversion_factor.required_with' => 'Enter how many Feet are in one Roll (e.g. 40), or clear the other unit fields.',
            'conversion_factor.min' => 'Feet per Roll must be at least 2.',
            'purchase_unit.required_with' => 'Enter the Purchase Unit (e.g. Roll), or clear the other unit fields.',
            'base_unit.required_with' => 'Enter the Base Unit (e.g. Feet), or clear the other unit fields.',
        ];
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('book.index');
    }

    public function getBookByTitle(Request $request)
    {
        $query =  $request->input('search');
        $books =  Book::where('title','LIKE',"%$query%")->get();
        return response()->json([
            "status"  => JsonResponse::HTTP_OK,
            "message" => "Books Fetch successfully",
            "data"  => $books,
        ], JsonResponse::HTTP_OK);

    }
    public function getBookByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $book = Book::where('bar_code',$barcode)->first();
        return response()->json([
            "status"  => JsonResponse::HTTP_OK,
            "message" => "Book Fetch successfully",
            "data"  => $book,
        ], JsonResponse::HTTP_OK);  

    }

    public function getBookSalesById($id)
    {
        $book = Book::with(['saleItems.sale.customer', 'publisher', 'authors'])
            ->findOrFail($id);

        return view('book.sales_list', compact('book'));
    }
}

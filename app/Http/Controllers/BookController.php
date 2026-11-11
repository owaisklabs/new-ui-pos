<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::with(['category', 'inventory'])->latest()->paginate(50);
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
        $categories = Category::orderBy('id')->get();
        return  view('book.create',compact('authors','publishers','categories'));
    }


    public function store(Request $request)
    {
        
        $request->validate($this->productRules($request), $this->unitMessages());
        $data = $this->applyCategoryUnits($request, $request->except('cover_image', 'author_id'));
        if ($request->hasFile('cover_image')) {
            $imageName = time().'.'.$request->cover_image->extension();
            $request->cover_image->storeAs('book_covers', $imageName, 'public');

            $data['cover_image'] = 'book_covers/'.$imageName;
        }


        $book = Book::create($data);
        if ($request->filled('author_id')) {
            $book->authors()->attach($request->author_id);
        }
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
        $categories = Category::orderBy('id')->get();
        return view('book.update',compact('book','authors','publishers','categories'));
    }


    public function update(Request $request, Book $book)
    {
        $request->validate(array_merge($this->productRules($request), [
            'sku' => 'required',
            'bar_code' => 'required',
            'publisher_id' => 'nullable|exists:publishers,id',
            'author_id' => 'nullable|array',
            'author_id.*' => 'nullable|exists:authors,id',
            'published_at' => 'nullable',
            'pages' => 'nullable',
            'language' => 'nullable',
        ]), $this->unitMessages());
        $data = $this->applyCategoryUnits($request, $request->except('cover_image', 'author_id'));
        if ($request->hasFile('cover_image')) {
            $imageName = time().'.'.$request->cover_image->extension();
            $request->cover_image->storeAs('book_covers', $imageName, 'public');

            $data['cover_image'] = 'book_covers/'.$imageName;
        }
        $book->update($data);
        return redirect()->route('book.index');
    }


    private function productRules(Request $request): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable',
            'bar_code' => 'nullable',
            'title' => 'required',
            'subtitle' => 'nullable',
            'description' => 'nullable',
            'cost_price' => 'required|numeric',
            'sell_price' => 'nullable|numeric',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'conversion_factor' => 'nullable|numeric',
            'purchase_unit' => 'nullable|string|max:32',
            'base_unit' => 'nullable|string|max:32',
        ];

        $category = Category::find($request->input('category_id'));
        if ($category && $category->isFeetRoll()) {
            $rules['conversion_factor'] = 'required|numeric|min:2';
            $rules['purchase_unit'] = 'required|string|max:32';
            $rules['base_unit'] = 'required|string|max:32';
        }

        return $rules;
    }

    private function applyCategoryUnits(Request $request, array $data): array
    {
        $category = Category::findOrFail($request->input('category_id'));
        $data['category_id'] = $category->id;

        if ($category->isFeetRoll()) {
            $factor = (float) $request->input('conversion_factor');
            $data['conversion_factor'] = $factor;
            $data['purchase_unit'] = $request->input('purchase_unit') ?: 'Roll';
            $data['base_unit'] = $request->input('base_unit') ?: 'Feet';
            $data['cost_price'] = round($request->input('cost_price') / $factor, 2);
            if ($request->filled('sell_price')) {
                $data['sell_price'] = round($request->input('sell_price') / $factor, 2);
            }

            return $data;
        }

        $data['conversion_factor'] = null;
        $data['purchase_unit'] = 'Pieces';
        $data['base_unit'] = 'Pieces';

        return $data;
    }

    private function unitMessages(): array
    {
        return [
            'category_id.required' => 'Select a category.',
            'conversion_factor.required' => 'Enter how many Feet are in one Roll (e.g. 40).',
            'conversion_factor.min' => 'Feet per Roll must be at least 2.',
            'purchase_unit.required' => 'Enter the Purchase Unit (e.g. Roll).',
            'base_unit.required' => 'Enter the Base Unit (e.g. Feet).',
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
        $books =  Book::with('category')->where('title','LIKE',"%$query%")->get();
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

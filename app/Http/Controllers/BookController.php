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
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|array',
            'author_id.*' => 'exists:authors,id',
            'published_at' => 'nullable',
            'description' => 'nullable',
            'cost_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'pages' => 'nullable',
            'language' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $data = $request->except('cover_image', 'author_id');
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
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|array',
            'author_id.*' => 'exists:authors,id',
            'published_at' => 'nullable',
            'description' => 'nullable',
            'cost_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'pages' => 'nullable',
            'language' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $data = $request->except('cover_image', 'author_id');
        if ($request->hasFile('cover_image')) {
            $imageName = time().'.'.$request->cover_image->extension();
            $request->cover_image->storeAs('book_covers', $imageName, 'public');

            $data['cover_image'] = 'book_covers/'.$imageName;
        }
        $book->update($data);
        return redirect()->route('book.index');
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
}

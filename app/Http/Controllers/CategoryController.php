<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->orderBy('id')->paginate(50);

        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:128|unique:categories,name',
            'unit_mode' => 'required|in:feet_roll,pieces',
        ]);

        Category::create([
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'unit_mode' => $data['unit_mode'],
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function show(Category $category)
    {
        return redirect()->route('categories.edit', $category);
    }

    public function edit(Category $category)
    {
        return view('category.update', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:128|unique:categories,name,' . $category->id,
            'unit_mode' => 'required|in:feet_roll,pieces',
        ]);

        $payload = ['name' => $data['name']];

        if (!$category->isSystem()) {
            $payload['unit_mode'] = $data['unit_mode'];
            $payload['slug'] = $this->uniqueSlug($data['name'], $category->id);
        }

        $category->update($payload);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->isSystem()) {
            return redirect()->route('categories.index')
                ->with('error', 'Feet/Roll and Pieces are required and cannot be deleted.');
        }

        if ($category->books()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'This category has products and cannot be deleted.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $i = 2;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Category::class, 'categories');
    }

    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view('dashboard.categories.index', [
            'categories' => $categories
        ]);
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->all());
        return redirect()->route('categories.index')->with('message', 'Categoria registada com sucesso');
    }

    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', [
            'category' => $category
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        return redirect()->route('categories.index')->with('message', 'Categoria actualizada com sucesso');
    }


    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('message', 'Categoria excluída com sucesso');
    }
}

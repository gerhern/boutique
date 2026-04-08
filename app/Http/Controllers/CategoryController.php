<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\{CategoryUpdateRequest, CategoryStoreRequest};
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(CategoryStoreRequest $request){
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect(route('admin.categories.index'))->with('success', 'Category created successfully.');
    }

    public function update(CategoryUpdateRequest $request, Category $category){
        $category->update([
            'name' => $request->name ?? $category->name,
            'slug' => Str::slug($request->name ?? $category->slug)
        ]);

        return redirect(route('admin.categories.index'))->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request, Category $category){
        $hasProducts =  $category->products()->exists();

        if($hasProducts){
            return redirect()->back()->withErrors(['category' => 'Cannot delete category with associated products.']);
        }

        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}

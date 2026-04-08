<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function update(CategoryUpdateRequest $request, Category $category){
        $category->update([
            'name' => $request->name ?? $category->name
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

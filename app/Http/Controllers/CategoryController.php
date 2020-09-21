<?php

namespace App\Http\Controllers;

use App\Category;

use function Symfony\Component\String\b;

class CategoryController extends Controller {
    public function index() {
        return response()->json([
            'status' => 'success',
            'data' => Category::all()
        ]);
    }

    public function store() {
        $attr = request()->validate(['name' => ['required', 'string']]);

        $newCategory = Category::create($attr);

        return response()->json([
            'status' => 'success',
            'data' => $newCategory
        ]);
    }

    public function destroy(Category $category) {
        $category->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}

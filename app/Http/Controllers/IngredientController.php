<?php

namespace App\Http\Controllers;

use App\Ingredient;

class IngredientController extends Controller {
    public function index() {
        return response()->json([
            'status' => 'success',
            'data' => Ingredient::all()
        ]);
    }

    public function store() {
        $newIngredient = Ingredient::create($this->validateNew());

        return response()->json([
            'status' => 'success',
            'data' => $newIngredient
        ]);
    }

    public function update(Ingredient $ingredient) {
        $ingredient->update($this->validateUpdate());

        return response()->json([
            'status' => 'success',
            'data' => $ingredient
        ]);
    }

    public function destroy(Ingredient $ingredient) {
        $ingredient->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    protected function validateNew() {
        return request()->validate([
            'name' => ['required', 'max:35', 'string']
        ]);
    }

    protected function validateUpdate() {
        return request()->validate([
            'name' =>  ['max:35', 'string']
        ]);
    }
}

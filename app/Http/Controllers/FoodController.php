<?php

namespace App\Http\Controllers;

use App\Food;

class FoodController extends Controller {
    public function index() {
        return response()->json([
            'status' => 'success',
            'data' => Food::where('active', true)->with('ingredients')->get()
        ]);
    }

    public function store() {
        $newFood = Food::create($this->validateNew());

        return response()->json([
            'status' => 'success',
            'data' => $newFood
        ]);
    }

    public function update(Food $food) {
        // Modify food instance
        $updatedAttributes = $this->validateUpdate();
        if (count($updatedAttributes) > 0) {
            $food->update($updatedAttributes);
        }

        // Modify ingredients if necessary
        $ingredients = request('ingredients');
        if (request('ingredients')) {
            $food->ingredients()->sync($ingredients);
        }


        return response()->json([
            'status' => 'success',
            'data' => $food
        ]);
    }

    protected function destroy(Food $food) {
        $food->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    protected function validateNew() {
        return request()->validate([
            'name' => ['required', 'max:70', 'string'],
            'price' => ['required', 'numeric'],
            'discounted_price' => ['nullable', 'numeric'],
            'category' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean']
        ]);
    }

    protected function validateUpdate() {
        return request()->validate([
            'name' => ['max:70', 'string'],
            'price' => ['numeric'],
            'discounted_price' => ['nullable', 'numeric'],
            'category' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean']
        ]);
    }
}

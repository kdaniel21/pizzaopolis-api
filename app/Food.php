<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Food extends Model {
    protected $guarded = [];
    protected $hidden = ['active', 'created_at', 'updated_at'];
    
    public function ingredients() {
        return $this->belongsToMany(Ingredient::class);
    }

    public function currentPrice() {
        return $this->discounted_price ?: $this->price;
    }

    // public function toArray() {
    //     $attributes = $this->attributesToArray();
    //     $attributes = array_merge($attributes, $this->relationsToArray());

    //     // Add the quantity from the pivot table and hide the pivot
    //     if (isset($attributes['pivot']['quantity'])) {
    //         $attributes['quantity'] = $attributes['pivot']['quantity'];
    //         // unset($attributes['pivot']);
    //     }
    //     return $attributes;
    // }
}

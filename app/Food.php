<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use App\Ingredient;
use App\Category;

class Food extends Model {
    protected $guarded = [];
    protected $hidden = ['active', 'created_at', 'updated_at'];
    protected $with = ['ingredients', 'categories'];
    
    public function ingredients() {
        return $this->belongsToMany(Ingredient::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function currentPrice() {
        return $this->discounted_price ?: $this->price;
    }
}

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
}

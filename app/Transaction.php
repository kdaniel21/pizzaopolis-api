<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Food;
use App\Coupon;

class Transaction extends Model {
    protected $guarded = [];

    public function foods() {
        return $this->belongsToMany(Food::class)->withPivot(['quantity', 'unit_price']);
    }

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }

    public function addFoods($foods) {
        $foods->each(function ($food) {
            $this->foods()->attach($food->id, [
                'quantity' => $food->pivot->quantity,
                'unit_price' => $food->currentPrice()
            ]);
        });
    }
}

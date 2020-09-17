<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Food;

class Category extends Model {
    protected $guarded = [];

    public function foods() {
        return $this->belongsToMany(Food::class);
    }
}

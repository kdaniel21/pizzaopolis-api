<?php

namespace App\Http\Controllers;

use App\Coupon;

class CouponController extends Controller {
    public function index() {
        return response()->json([
            'status' => 'success',
            'data' => Coupon::all()
        ]);
    }

    public function show(Coupon $coupon) {
        if (!$coupon && !$coupon->isActive()) {
            return abort(404);
        }

        return response()->json([
            'status' => 'success',
            'code' => $coupon->code,
            'value' => $coupon->value,
            'type' => $coupon->type
        ]);
    }

    public function store() {
        $coupon = Coupon::create(request());

        return response()->json([
            'status' => 'success',
            'data' => $coupon
        ]);
    }

    public function update(Coupon $coupon) {
        // $coupon->update(request()->toArray())->save();
        $attributes = request()->validate([
            'code' => 'string',
            'amount' => 'number',
            'expires-at' => 'date',
            'max-times-used' => 'number',
            'active' => 'boolean'
        ]);

        $coupon->update($attributes);

        return response()->json([
            'status' => 'success',
            'data' => $attributes
        ]);
    }
}

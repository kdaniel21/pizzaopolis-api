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
        $coupon = Coupon::create($this->validateCoupon());

        return response()->json([
            'status' => 'success',
            'data' => $coupon
        ]);
    }

    public function update(Coupon $coupon) {
        $coupon->update($this->validateCoupon());

        return response()->json([
            'status' => 'success',
            'data' => $coupon
        ]);
    }

    protected function validateCoupon() {
        return request()->validate([
            'code' => 'string',
            'value' => 'numeric',
            'type' => 'in:amount,percent',
            'expires-at' => 'date',
            'max-times-used' => 'numeric',
            'active' => 'boolean'
        ]);
    }
}

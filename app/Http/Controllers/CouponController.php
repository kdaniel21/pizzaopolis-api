<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function store(Request $request) {
        $coupon = Coupon::create($this->validateCoupon($request));

        return response()->json([
            'status' => 'success',
            'data' => $coupon
        ]);
    }

    public function update(Request $request, Coupon $coupon) {
        $coupon->update($this->validateCoupon($request));

        return response()->json([
            'status' => 'success',
            'data' => $coupon
        ]);
    }

    protected function validateCoupon(Request $request) {
        $request->merge([
            'expires_at' => new Carbon($request['expires_at']),
        ]);
        return request()->validate([
            'code' => 'string',
            'value' => 'numeric',
            'type' => 'in:amount,percent',
            'expires_at' => 'date',
            'max_times_used' => 'numeric',
            'active' => 'boolean'
        ]);
    }
}

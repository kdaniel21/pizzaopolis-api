<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use App\Traits\Billable;
use App\ShippingInformation;
use App\BillingInformation;
use App\Coupon;
use App\Transaction;

class Order extends Model {
    use Billable;

    protected $guarded = ['status', 'paid', 'cancelled', 'coupon_id'];

    public function name() {
        return $this->shippingInformation()->pluck('name');
    }

    public function foods() {
        return $this->belongsToMany(Food::class)->withPivot(['quantity']);
    }

    public function addFoods($foodObj) {
        // Creates a 2D array with quantities assigned
        $foodIds = collect($foodObj)->mapWithKeys(function ($food) {
            return [
                $food['id'] => [
                    'quantity' => $food['quantity']
                ]
            ];
        });
        
        $this->foods()->sync($foodIds);
    }

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }
    
    // Returns the amount of discount relative to the total amount
    public function couponValue() {
        // Early return if no coupon was redeemed
        if (!$this->coupon || !$this->coupon->isValid()) {
            return 0;
        }

        return $this->coupon->type === 'percent'
            ? $this->total() * ($this->coupon->value / 100)
            : $this->coupon->value;
    }

    public function redeemCoupon($code) {
        $coupon = Coupon::where('code', $code)->firstOrFail();

        if (!$coupon->isValid()) {
            return abort(404);
        }

        $this->coupon()->associate($coupon);
    }

    public function shippingInformation() {
        return $this->hasOne(ShippingInformation::class);
    }

    public function setShippingInformation($shippingInformation) {
        return $this->shippingInformation()->updateOrCreate($shippingInformation);
    }

    public function billingInformation() {
        return $this->hasOne(BillingInformation::class)->exists()
        ? $this->hasOne(BillingInformation::class)
        : $this->hasOne(ShippingInformation::class);
    }

    public function setBillingInformation($billingInformation) {
        return $this->billingInformation()->updateOrCreate($billingInformation);
    }
    
    public function total() {
        // Calculates with the discounted price but if there is no discounted price uses the normal price to calculate the total
        $total = $this
            ->foods
            ->map(fn ($food) => $food->currentPrice() * $food->pivot->quantity)
            ->sum();

        // Apply coupon (or nothing if no coupon provided)
        $discount = $this->couponValue();
        
        return $total - $discount >= 0 ? $total - $discount : 0;
    }

    public function transaction() {
        return $this->hasOne(Transaction::class);
    }

    public function createTransaction() {
        // Create transaction
        $transaction = Transaction::create([
            'total' => $this->total(),
            'order_id' => $this->id,
            'coupon_id' => $this->coupon ? $this->coupon->id : null,
            'discount_amount' => $this->couponValue()
        ]);

        // Assign foods
        $transaction->addFoods($this->foods);
    }
}

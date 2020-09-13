<?php

namespace App\Traits;

use \Stripe\Stripe;
use \Stripe\Checkout\Session;

trait Billable {
    public function checkout() {
        Stripe::setApiKey(env('STRIPE_API_KEY'));

        // line_items is not specified because coupons can't be added manually
        // That way the total amount cannot be modified here

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $this->total() * 100,
                        'product_data' => [
                            'name' => "Order Nr. {$this->id}"
                        ],
                    ],
                    'quantity' => 1
                ]
            ],
            'mode' => 'payment',
            'success_url' => 'https://whatever.com/1234?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'https://whatever.com/1234-cancel'
        ]);

        return $session->id;
    }
}

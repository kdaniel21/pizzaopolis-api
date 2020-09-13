<?php

namespace App\Http\Controllers;

use App\Order;

class OrderController extends Controller {
    public function index() {
        return response()->json([
            'status' => 'success',
            // Include name and total amount to make orders easier to find
            'data' => Order::with(['foods', 'transaction', 'shippingInformation'])->get()->map(function ($order) {
                $order['total'] = $order->transaction->total;
                $order['name'] = $order->shippingInformation->name;
                unset($order['transaction']);
                unset($order['shippingInformation']);
                return $order;
            }),
        ]);
    }

    public function show(Order $order) {
        return response()->json([
            'status' => 'success',
            'data' => $order->load([
                'shippingInformation',
                'billingInformation',
                'foods',
                'transaction.foods',
                'transaction.coupon'
            ])
        ]);
    }

    // Create order and return the Stripe Session ID
    public function store() {
        // return response()->json(request());
        if (!request('food') || !request('shipping_information')) {
            return abort(400);
        }
        
        $order = Order::create();

        // Set data
        $order->addFoods(request('food'));


        if (request('coupon')) {
            $order->redeemCoupon(request('coupon'));
        }
        
        $order->setShippingInformation($this->validateShippingInformation());


        if (request('billing_information')) {
            $order->setBillingInformation($this->validateBillingInformation());
        }
        
        // Create Stripe payment session
        $sessionId = $order->checkout();

        return response()->json([
            'status' => 'success',
            'sessionId' => $sessionId
        ]);
    }

    protected function validateAddress($subarray) {
        $attr = request()->validate([
            "{$subarray}.address.country" => ['required', 'string'],
            "{$subarray}.address.state" => ['required', 'string'],
            "{$subarray}.address.district" => ['required', 'string'],
            "{$subarray}.address.postal_code" => ['required', 'numeric'],
            "{$subarray}.address.city" => ['required', 'string'],
            "{$subarray}.address.street" => ['required', 'string'],
            "{$subarray}.address.house_number" => ['required'],
            "{$subarray}.address.floor" => ['nullable'],
            "{$subarray}.address.door" => ['nullable']
        ]);

        return $attr[$subarray]['address'];
    }

    protected function validateShippingInformation() {
        $attr = request()->validate([
            'shipping_information.name' => ['required', 'string'],
            'shipping_information.email' => ['required', 'email'],
            'shipping_information.phone' => ['required', 'regex:#^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$#'],
            'shipping_information.comment' => ['nullable']
        ]);

        $address = $this->validateAddress('shipping_information');

        return array_merge($attr['shipping_information'], $address);
    }

    protected function validateBillingInformation() {
        $attr = request()->validate([
            'billing_information.name' => ['required', 'string']
        ]);

        $address = $this->validateAddress('billing_information');

        return array_merge($attr['billing_information'], $address);
    }
}

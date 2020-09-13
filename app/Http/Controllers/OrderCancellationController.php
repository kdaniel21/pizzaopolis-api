<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class OrderCancellationController extends Controller {
    public function update(Order $order) {
        $order->update([
            'cancelled' => true
        ]);

        // TODO: Send email to user
    }
}

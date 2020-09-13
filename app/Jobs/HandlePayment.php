<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;
use App\Order;

class HandlePayment implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $webhookCall;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall) {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        // Set order to finished and paid
        $orderId = $this->webhookCall->payload->metadata->orderId;
        $order = Order::find($orderId)->firstOrFail();

        // TODO: Create transaction
        $order->createTransaction();

        $order->update([
            'status' => 'finished',
        ]);

        // Send email invoice
    }
}

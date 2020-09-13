<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id')->constrained('order');
            $table->double('total');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons');
            $table->double('discount_amount')->nullable();
            $table->timestamps();
        });

        Schema::create('food_transaction', function (Blueprint $table) {
            $table->id();
            $table->uuid('food_id')->constrained('food');
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->integer('quantity')->default(1);
            $table->double('unit_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transactions');
    }
}

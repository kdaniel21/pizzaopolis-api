<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->enum('status', ['pending', 'finished', 'error'])->default('pending');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons');
            $table->boolean('cancelled')->default(false);
            
            $table->timestamps();
        });

        Schema::create('food_order', function (Blueprint $table) {
            $table->id();
            $table->uuid('food_id')->constrained('food');
            $table->uuid('order_id')->constrained('orders');
            $table->smallInteger('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }
}

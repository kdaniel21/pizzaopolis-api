<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingInformationTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('shipping_information', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id')->constrained('orders');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('comment')->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('district');
            $table->integer('postal_code');
            $table->string('city');
            $table->string('street');
            $table->string('house_number');
            $table->string('floor')->nullable();
            $table->string('door')->nullable();
            $table->timestamps();
        });

        Schema::create('billing_information', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id')->constrained('orders');
            $table->string('name');
            $table->string('phone');
            $table->string('country');
            $table->string('state');
            $table->string('district');
            $table->integer('postal_code');
            $table->string('city');
            $table->string('street');
            $table->string('house_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shipping_information');
        Schema::dropIfExists('billing_information');
    }
}

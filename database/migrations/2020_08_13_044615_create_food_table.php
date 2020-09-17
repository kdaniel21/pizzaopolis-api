<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('food', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->double('price');
            $table->double('discounted_price')->nullable();
            $table->boolean('active')->default(true);
            
            $table->timestamps();
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('food_ingredient', function (Blueprint $table) {
            $table->id();
            $table->uuid('food_id')->constrained('food');
            $table->foreignId('ingredient_id')->constrained('ingredients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('food');
    }
}

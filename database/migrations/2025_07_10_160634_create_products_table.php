<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('barcode')->unique()->nullable();
            $table->string('product_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('categories')->nullable();
            $table->string('labels')->nullable();
            $table->string('countries_sold')->nullable();
            $table->string('image_url')->nullable();
            $table->json('nutrient_levels')->nullable();
            $table->json('nutrient_table')->nullable();
            $table->text('ingredients')->nullable();
            $table->json('ingredients_info')->nullable();
            $table->string('source_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

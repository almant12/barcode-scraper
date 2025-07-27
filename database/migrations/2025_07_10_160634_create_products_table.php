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
            $table->foreignId('source_id')->constrained(table: 'sources');

            $table->unsignedBigInteger('barcode')->nullable();
            $table->string('title')->nullable();
            $table->string('brand')->nullable();
            $table->string('reference')->nullable();
            $table->string('categories')->nullable();
            $table->decimal('price', 6, 2)->nullable();
            $table->string('labels')->nullable();
            $table->text('description')->nullable();
            $table->string('countries_sold')->nullable();
            $table->json('image_urls')->nullable();
            $table->json('nutrient_levels')->nullable();
            $table->json('nutrient_table')->nullable();
            $table->text('ingredients')->nullable();
            $table->json('ingredients_info')->nullable();
            $table->json('data_sheet')->nullable();
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

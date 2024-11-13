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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // FK referencing products
            $table->string('color')->nullable(); // Allow color to be null for non-color-based products
            $table->string('size')->nullable();  // Allow size to be null for non-size-based products
            $table->integer('quantity')->default(0); // Quantity field
            $table->json('attributes')->nullable(); // JSON for additional custom attributes
            $table->boolean('availability')->default(true); // Indicates if this variation is available
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations'); // Drop the table
    }
};

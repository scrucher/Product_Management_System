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
            $table->id()->autoIncrement(); // primary key
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // FK referencing products
            $table->string('color');
            $table->string('size');
            $table->integer('quantity')->default(0);
            $table->boolean('availability')->default(true);
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variation');
    }
};

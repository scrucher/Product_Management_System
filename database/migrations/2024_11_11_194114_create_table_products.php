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
            $table->id()->autoIncrement(); // primary key
            $table->string('name');
            $table->string('sku');
            $table->string('status');
            $table->integer('quantity')->default(0);
            $table->decimal('price', 8, 2);
            $table->string('currency', 3);
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products'); // Correct table name
    }
};

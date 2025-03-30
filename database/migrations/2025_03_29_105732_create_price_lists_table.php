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
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('country_code', 3)->nullable()->index()->comment("Stores 'USA', 'CAN', etc.");
            $table->string('currency_code', 3)->nullable()->index()->comment("Stores 'USD', 'CAD', 'EUR', etc.");
            $table->decimal('price', 10, 2);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('priority')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_lists');
    }
};

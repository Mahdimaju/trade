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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->string('pair');
            $table->enum('type', ['buy', 'sell']);
            $table->decimal('entry_price', 15, 5);
            $table->decimal('stop_loss', 15, 5);
            $table->decimal('take_profit', 15, 5);
            $table->decimal('lot_size', 15, 2);
            $table->decimal('profit_loss', 15, 2)->default(0.00);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};

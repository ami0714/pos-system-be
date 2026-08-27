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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
        $table->string('invoice_number')->unique()->index();
        $table->foreignId('user_id')->constrained();
        $table->decimal('subtotal', 10, 2);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('grand_total', 10, 2);
        $table->decimal('paid_amount', 10, 2);
        $table->decimal('change_amount', 10, 2)->default(0);
        $table->enum('payment_method', ['CASH', 'QR', 'CARD']);
        $table->enum('status', ['COMPLETED', 'VOID'])->default('COMPLETED');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

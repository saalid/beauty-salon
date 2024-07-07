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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3); // e.g., 'USD'
            $table->string('status'); // e.g., 'pending', 'completed', 'failed'
            $table->string('payment_method'); // e.g., 'credit_card', 'paypal'
            $table->string('transaction_id')->unique(); // External payment gateway transaction ID
            $table->timestamp('paid_at')->nullable(); // When the payment was completed
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('price')->nullable();
            $table->string('status')->nullable();
            $table->string('state')->nullable();
            $table->string('state_code')->nullable();
            $table->string('ref_num')->nullable();
            $table->string('cid')->nullable();
            $table->string('trace_no')->nullable();
            $table->string('rrn')->nullable();
            $table->string('secure_pan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

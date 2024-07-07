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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'state',
                'state_code',
                'ref_num',
                'cid',
                'trace_no',
                'rrn',
                'secure_pan'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('state')->nullable();
            $table->string('state_code')->nullable();
            $table->string('ref_num')->nullable();
            $table->string('cid')->nullable();
            $table->string('trace_num')->nullable();
            $table->string('rrn')->nullable();
            $table->string('secure_pan')->nullable();
        });
    }
};

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
            $table->string('title', '2048');
            $table->string('slug', '2048');
            $table->string('thumbnail', '2048')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->string('number_of_session', '2048')->nullable();
            $table->string('type', '2048')->nullable();
            $table->string('spot_player_id', '2048')->nullable();
            $table->longText('body');
            $table->longText('headline');
            $table->boolean('active');
            $table->datetime('start_time');
            $table->datetime('published_at');
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, 'user_id');
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

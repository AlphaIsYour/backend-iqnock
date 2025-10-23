<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->integer('level_number')->unique();
            $table->string('level_name');
            $table->boolean('is_premium')->default(false); // true untuk level 11-20
            $table->integer('coin_price')->default(0); // harga coins untuk unlock
            $table->integer('reward_coins')->default(0); // reward setelah selesai
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
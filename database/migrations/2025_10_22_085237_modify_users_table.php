<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('coins')->default(0)->after('password');
            $table->integer('hearts')->default(5)->after('coins');
            $table->integer('hints')->default(5)->after('hearts');
            $table->integer('current_level')->default(1)->after('hints');
            $table->integer('total_score')->default(0)->after('current_level');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['coins', 'hearts', 'hints', 'current_level', 'total_score']);
        });
    }
};
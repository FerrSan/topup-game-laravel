<?php
// 1. Migration untuk tabel games
// database/migrations/2024_01_01_000001_create_games_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('banner')->nullable();
            $table->text('description')->nullable();
            $table->string('publisher')->nullable();
            $table->string('category'); // mobile, pc, console
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('form_fields')->nullable(); // untuk menyimpan field khusus per game
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
    }
};
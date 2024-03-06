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
        Schema::create('reply_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('reply_id');
            $table->enum('type',['like','bookmark']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('board_id')->references('id')->on('boards');
            $table->foreign('reply_id')->references('id')->on('replies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reply_reactions');
    }
};

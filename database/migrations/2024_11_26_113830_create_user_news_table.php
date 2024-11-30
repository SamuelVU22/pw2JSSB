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
        Schema::create('user_news', function (Blueprint $table) {
            $table->unsignedBigInteger('idUser'); // Match type of 'idUser' in 'users' table
            $table->unsignedBigInteger('idNews');
            $table->foreign('idUser')
                ->references('idUser')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('idNews')
                ->references('idNews')
                ->on('news')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_news');
    }
};

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
        Schema::create('news', function (Blueprint $table) {
            $table->id('idNews');
            $table->string('title');
            $table->text('description');
            $table->integer('numLikes')->default(0);
            $table->boolean('isLike')->default(false);
            $table->date('date');
            $table->text('urlPhoto');
            $table->text('urlNews');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

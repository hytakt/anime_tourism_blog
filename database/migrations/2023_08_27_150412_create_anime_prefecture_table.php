<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anime_prefecture', function (Blueprint $table) {
            $table->foreignId('anime_id')->constrained('animes');
            $table->foreignId('prefecture_id')->constrained('prefectures');
            $table->primary(['anime_id', 'prefecture_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anime_prefecture');
    }
};

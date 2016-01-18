<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imdb_id');
            $table->string('title');
            $table->string('short_info');
            $table->string('douban_rating')->nullable();
            $table->string('imdb_rating')->nullable();
            $table->string('tomato_meter')->nullable();
            $table->string('poster_url');
            $table->string('backdrop_url');
            $table->integer('ranking')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movies');
    }
}

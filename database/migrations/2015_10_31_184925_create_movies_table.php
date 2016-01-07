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
            // $table->increments('id');
            $table->string('imdb_id')->primary();
            $table->string('title');
            $table->string('short_info');
            $table->string('douban_rating');
            $table->string('imdb_rating');
            $table->string('poster_url');
            $table->string('backdrop_url');
            $table->integer('ranking');
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

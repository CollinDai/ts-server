<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxofficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxoffice', function (Blueprint $table) {
            $table->string('imdb_id');
            $table->foreign('imdb_id')->references('imdb_id')->on('movies');
            $table->integer('week');
            $table->integer('ranking');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('boxoffice');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubtitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtitles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_id');
            $table->string('imdb_id');
            $table->string('file_name');
            $table->string('duration');
            $table->string('download_link');
            $table->string('add_date');
            $table->string('language');
            $table->string('ISO639');
            $table->string('ISO639_2');
            $table->integer('download_count');
            $table->integer('file_size');
            $table->mediumText('content')->nullable();
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
        Schema::drop('subtitles');
    }
}

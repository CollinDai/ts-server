<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // $table = 'movies';
    protected $fillable = array('imdb_id');
    protected $primaryKey = 'imdb_id';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // $table = 'movies';
    protected $primaryKey = 'imdb_id';
    public function boxoffices() {
    	return $this->hasMany('App\Models\Boxoffice', 'imdb_id', 'imdb_id');
    }
}

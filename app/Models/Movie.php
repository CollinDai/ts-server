<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // $table = 'movies';
    protected $primaryKey = 'imdb_id';
    public function boxoffice() {
    	return $this->hasMany('Boxoffice', 'imdb_id', 'imdb_id');
    }
}

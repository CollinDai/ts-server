<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boxoffice extends Model
{
    public function movie() {
    	return $this->belongsTo('Movie', 'imdb_id', 'imdb_id');
    }
}

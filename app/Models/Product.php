<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function users(){ 
		return $this->belongsTo('App\User','user_id');
	}
	public function units(){ 
		return $this->belongsTo('App\Models\Unit','unit_id');
	}
}

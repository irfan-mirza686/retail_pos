<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function customers(){ 
		return $this->belongsTo('App\Models\Customer','customer_id');
	}
	public function areas(){ 
		return $this->belongsTo('App\Models\Area','area_id');
	}
	public function users(){ 
		return $this->belongsTo('App\User','added_by');
	}
}

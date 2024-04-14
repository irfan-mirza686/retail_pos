<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceHistory extends Model
{
    public function employees(){ 
		return $this->belongsTo('App\Models\Employee','employee_id');
	}
	
	public function users(){ 
		return $this->belongsTo('App\User','createdBy');
	}
	public function gate(){ 
		return $this->belongsTo('App\Models\Gate','gate_id');
	}
}

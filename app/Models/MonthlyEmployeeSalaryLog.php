<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyEmployeeSalaryLog extends Model
{
    public function gates(){ 
		return $this->belongsTo('App\Models\Gate','gate_id');
	}
}

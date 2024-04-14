<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySalary extends Model
{
    public function employee(){ 
		return $this->belongsTo('App\Models\Employee','employee_id');
	}
}

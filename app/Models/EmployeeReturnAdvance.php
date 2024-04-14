<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeReturnAdvance extends Model
{
    public function employees(){ 
        return $this->belongsTo('App\Models\Employee','employee_id');
    }
}

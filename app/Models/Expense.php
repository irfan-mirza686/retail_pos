<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public function user(){ 
        return $this->belongsTo('App\User','user_id');
    }
    public function gates(){ 
        return $this->belongsTo('App\Models\Gate','gate_id');
    }
    public function category(){ 
        return $this->belongsTo('App\Models\ExpenseCategory','exp_category_id');
    }
    
}

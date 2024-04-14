<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function suppliers(){ 
        return $this->belongsTo('App\Models\Supplier','supplier_id');
    }
    public function users(){ 
        return $this->belongsTo('App\User','added_by');
    }
}

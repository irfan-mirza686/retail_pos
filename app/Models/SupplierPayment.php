<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
     public function users(){ 
        return $this->belongsTo('App\User','user_id');
    }
    public function suppliers(){ 
        return $this->belongsTo('App\Models\Supplier','supplier_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function areawisesale(){
        return $this->hasManyThrough(Sale::class,Customer::class);
    }
    public function advanceAmount(){
        return $this->hasManyThrough(AdvanceCustomerPayment::class,Customer::class);
    }
    public function receivablePayable(){
        return $this->hasManyThrough(ReceivablePayable::class,Customer::class);
    }
}

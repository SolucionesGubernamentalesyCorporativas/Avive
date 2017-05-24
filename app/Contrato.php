<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    //
    public function membresia(){
        return $this->belongsTo('App\Membresia');
    }
    public function pago(){
        return $this->belongsTo('App\Pago');
    }
}

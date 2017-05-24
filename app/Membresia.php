<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    //
    public function contratos()
    {
        return $this->hasMany('App\Contrato');
    }
}

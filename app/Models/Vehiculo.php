<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = "vehiculo";
    
    protected $fillable = [
        'id_copropietario',
        'id_estacionamiento',
        'color',
        'marca',
        'placa',
        'estado'
    ];

    protected $timestamp = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacionamiento extends Model
{
    use HasFactory;
    protected $table = "estacionamiento";
    
    protected $fillable = [
        'id_pabellon',
        'numero_estacionamiento',
        'estado'
    ];

    protected $timestamp = false;
}

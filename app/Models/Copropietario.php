<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copropietario extends Model
{
    use HasFactory;
    protected $table = "copropietario";
    
    protected $fillable = [
        'id_persona',
        'id_condominio',
        'id_apartamento',
        'cant_residentes',
        'cant_mascotas',
        'estado'
    ];

    protected $timestamp = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartamento extends Model
{
    use HasFactory;
    protected $table = "apartamento";
    
    protected $fillable = [
        'id_condominio',
        'nombre',
        'numero_apartamento',
        'estado'
    ];

    protected $timestamp = false;
}

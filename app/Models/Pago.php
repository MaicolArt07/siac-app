<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $table = "pago";
    
    protected $fillable = [
        'id_copropietario',
        'id_numerobanca',
        'id_articulo',
        'id_tipopago',
        'descripcion',
        'debe',
        'haber',
        'nro_transferencia',
        'fecha',
        'estado'
    ];

    protected $timestamp = false;
}

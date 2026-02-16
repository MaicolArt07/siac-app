<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;
    protected $table = "articulo";
    protected $fillable = [
        'id_periodo',
        'id_condominio',
        'id_tipoarticulo',
        'id_tipocuenta',
        'descripcion',
        'monto',
        'estado'
    ];

    protected $timestamp = false;
}

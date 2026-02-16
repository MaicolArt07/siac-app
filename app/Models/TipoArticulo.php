<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoArticulo extends Model
{
    use HasFactory;
    protected $table = "tipo_articulo";
    protected $fillable = [
        'nombre',
    ];

    protected $timestamp = false;
}

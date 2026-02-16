<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;
    protected $table = "recibo";
    protected $fillable = [
        'id_copropietario',
        'link',
        'estado'
    ];

    protected $timestamp = false;
}

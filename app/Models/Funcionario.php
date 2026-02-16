<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;
    
    protected $table = "funcionario";
    protected $fillable = [
        'id_persona',
        'id_cargo',
        'salario',
        'estado',
    ];

    protected $timestamp = false;
}

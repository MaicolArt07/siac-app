<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class numeroBanca extends Model
{
    use HasFactory;

    protected $table = 'numero_banca';
    protected $fillable = ['nombre', 'estado'];
    
}

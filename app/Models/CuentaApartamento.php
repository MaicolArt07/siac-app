<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaApartamento extends Model
{
    use HasFactory;
    protected $table = 'cuenta_apartamento';
    protected $fillable = ['id_pago','fecha','estado'];
}

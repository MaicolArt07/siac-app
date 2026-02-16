<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensaControl extends Model
{
    use HasFactory;
    
    protected $table = "expensa_control";
    protected $fillable = ['id_apartamento', 'monto', 'estado'];
}

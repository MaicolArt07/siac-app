<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Copropietario;
use Barryvdh\DomPDF\Facade\Pdf;

class ResiboController extends Controller
{
    public function generatePDF()
    {
        $cantidad_copropietarios = Copropietario::count();
        $pdf = Pdf::loadView('admin.resiboPDF', compact('cantidad_copropietarios'));
        return $pdf->stream('resibo.pdf');
    }
}

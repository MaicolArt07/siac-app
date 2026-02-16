<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Copropietario;

use Illuminate\Http\Request;
class ReporteIngresoController extends Controller
{


    public function generateReporteIngreso()
    {
        $cantidad_copropietarios = Copropietario::count();
        $pdf = Pdf::loadView('admin.generateReporteIngreso');
        return $pdf->stream('generateReporteIngreso.pdf');
    }
}

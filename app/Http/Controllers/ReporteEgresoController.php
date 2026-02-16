<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Copropietario;

use Illuminate\Http\Request;
class ReporteEgresoController extends Controller
{

    public function generateReporteEgreso()
    {
        $cantidad_copropietarios = Copropietario::count();
        $pdf = Pdf::loadView('admin.generateReporteEgreso', compact('cantidad_copropietarios'));
        return $pdf->stream('generateReporteEgreso.pdf');
    }

}

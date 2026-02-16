<?php

namespace App\Livewire;

use App\Models\Gestion;
use App\Models\Periodo;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteIngresoLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idPais = "";

    public $openModalNew = false;
    public $openModalEdit = false;
    public $idGestion = "";
    public $periodo = "";
    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idGestion', 'periodo']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function selectedGestion($id)
    {
        $this->idGestion = $id;
    }

    public function render()
    {
        $gestiones = Gestion::where('estado', 1)->orderBy('id', 'desc')->get();
        $periodos = collect();
        if(isset($this->idGestion) && !empty($this->idGestion))
        {
            $periodos = Periodo::where('id_gestion', $this->idGestion)->get();
        }

        return view('livewire.reportes.ingreso', compact('gestiones', 'periodos'));
    }

    public function seleccionarGestion($gestionId)
    {
        $this->idGestion = $gestionId;
    }

    public function generateReporte()
    {
        $data = [
            'gestion' => $this->idGestion,
            'periodo' => $this->periodo
        ];

        // dd($idCopropietario);

        $pdf = PDF::loadView('admin.generateReporteIngreso', $data);
        $pdfPath = 'generateReporteIngreso'; // Cambiar el nombre del archivo PDF si es necesario

        // Storage::put($pdfPath, $pdf->output());

        // Emitir evento para notificar al frontend
        $this->dispatch('pdfGenerated', [
            'url' => $pdfPath,
            'gestion' => $this->idGestion,
            'periodo' => $this->periodo, 
        ]);

        $this->resetAttribute();
    }
}

<?php

namespace App\Livewire;

use App\Models\Gestion;
use App\Models\Periodo;
use App\Models\Recibo;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class ReciboLivewire extends Component
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
        // $recibos = DB::table('v_recibo')->paginate(5);

        $recibos = DB::table('v_recibo')
                            ->where(function($query) {
                                $query->where('nombre', 'like', '%' . $this->search . '%')
                                    ->orWhere('apellido', 'like', '%' . $this->search . '%')
                                    ->orWhere('ci', 'like', '%' . $this->search . '%')
                                    ->orWhere('numero_apartamento', 'like', '%' . $this->search . '%');

                            })
                            ->orderBy('id', 'desc')
                            ->paginate(5);

        return view('livewire.recibo.recibo-livewire', compact('recibos'));
    }

    
    #[On('delete')]
    public function eliminarEstado($id)
    {

        $recibo = Recibo::find($id);
        $estado = $recibo->estado;

        if($estado == 1)
        {
            $recibo->estado = 0;
        }else{
            $recibo->estado = 1;
        }
        $recibo->save();
    }
}

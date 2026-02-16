<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Periodo;
use App\Models\Gestion;
use Illuminate\Support\Facades\DB;
use App\Livewire\Form\PeriodoForm;

class PeriodoLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idPeriodo = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public PeriodoForm $periodo;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idPeriodo', 'periodo']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $periodos = DB::table("v_periodo")
                        ->where('gestion', 'like', '%' . $this->search . '%')
                        ->orWhere('periodo', 'like', '%' . $this->search . '%')
                        ->orWhere('sigla', 'like', '%' . $this->search . '%')
                        ->orderBy('id','desc')
                        ->paginate(5);

        $gestiones = Gestion::all();
        return view('livewire.periodo.periodo-livewire', compact('periodos', 'gestiones'));
    }

    public function created()
    {
        $this->periodo->validate();
        $periodo = Periodo::create([
            'id_gestion' => $this->periodo->id_gestion, 
            'nombre' => $this->periodo->nombre, 
            'sigla' => $this->periodo->sigla, 
        ]);

        $response = $periodo ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idPeriodo = $id;
        $periodo = Periodo::find($id);
        
        $this->periodo->fill([
            'id_gestion' => $periodo->id_gestion, 
            'nombre' => $periodo->nombre, 
            'sigla' => $periodo->sigla, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idPeriodo;

        $this->periodo->validate();

        $periodo = Periodo::find($id);
        $periodo = $periodo->update($this->periodo->only('id_gestion', 'nombre', 'sigla'));

        $response = $periodo ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {

        $periodo = Periodo::find($id);
        $estado = $periodo->estado;

        if($estado == 1)
        {
            $periodo->estado = 0;
        }else{
            $periodo->estado = 1;
        }
        $periodo->save();
    }
}

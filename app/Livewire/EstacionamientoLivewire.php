<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Form\EstacionamientoForm;
use App\Models\Estacionamiento;
use App\Models\Pabellon;

use Illuminate\Support\Facades\DB;

class EstacionamientoLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idEstacionamiento = "";

    public $openModalNew = false;
    public $openModalEdit = false;
    public EstacionamientoForm $estacionamiento;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'estacionamiento', 'idEstacionamiento']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $estacionamientos = DB::table('v_estacionamiento')
                                ->where('numero_estacionamiento', 'like', '%' . $this->search . '%')
                                ->orWhere('numero_pabellon', 'like', '%' . $this->search . '%')
                                ->orderBy('id','desc')
                                ->paginate(5);

        $pabellones = Pabellon::where('estado', 1)->get();

        return view('livewire.estacionamiento.estacionamiento-livewire', compact('estacionamientos', 'pabellones'));
    }

    public function created()
    {
        $this->estacionamiento->validate();
        $estacionamiento = Estacionamiento::create([
            'id_pabellon' => $this->estacionamiento->id_pabellon, 
            'numero_estacionamiento' => $this->estacionamiento->numero_estacionamiento
        ]);

        $response = $estacionamiento ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idEstacionamiento = $id;
        $estacionamiento = Estacionamiento::find($id);
        
        $this->estacionamiento->fill([
            'id_pabellon' => $estacionamiento->id_pabellon, 
            'numero_estacionamiento' => $estacionamiento->numero_estacionamiento, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idEstacionamiento;

        $this->estacionamiento->validate();
        $estacionamiento = Estacionamiento::find($id);
        $estacionamiento = $estacionamiento->update($this->estacionamiento->only('id_pabellon','numero_estacionamiento'));

        $response = $estacionamiento ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function eliminarEstado($id)
    {

        $estacionamiento = Estacionamiento::find($id);
        $estado = $estacionamiento->estado;

        if($estado == 1)
        {
            $estacionamiento->estado = 0;
        }else{
            $estacionamiento->estado = 1;
        }
        $estacionamiento->save();
    }
}

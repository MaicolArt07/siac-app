<?php

namespace App\Livewire;

use App\Models\Cargo;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Form\CargoForm;
class CargoLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idCargo = "";

    public $openModalNew = false;
    public $openModalEdit = false;
    public CargoForm $cargo;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'cargo', 'idCargo']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $cargos = Cargo::where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                        ->orderBy('id','desc')
                        ->paginate(5);

        return view('livewire.cargo.cargo-livewire', compact('cargos'));
    }

    public function created()
    {
        $this->cargo->validate();

        $cargo =  Cargo::create([
            'nombre' => $this->cargo->nombre, 
            'descripcion' => $this->cargo->descripcion, 
        ]);

        $response = $cargo ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idCargo = $id;
        $cargo = Cargo::find($id);
        
        $this->cargo->fill([
            'nombre' => $cargo->nombre, 
            'descripcion' => $cargo->descripcion, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idCargo;

        $this->cargo->validate();
        $cargo = Cargo::find($id);
        $cargo = $cargo->update($this->cargo->only('nombre','descripcion'));

        $response = $cargo ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function eliminarEstado($id)
    {

        $cargo = Cargo::find($id);
        $estado = $cargo->estado;

        if($estado == 1)
        {
            $cargo->estado = 0;
        }else{
            $cargo->estado = 1;
        }
        $cargo->save();
    }
}

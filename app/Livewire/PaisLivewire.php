<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Pais;
use App\Livewire\Form\PaisForm;

class PaisLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idPais = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public PaisForm $pais;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idPais', 'pais']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $paises = Pais::where('nombre', 'like', '%' . $this->search . '%')
                            ->orderBy('id','desc')
                            ->paginate(5);

        return view('livewire.pais.pais-livewire', compact('paises'));
    }

    public function created()
    {
        $this->pais->validate();

        $pais = Pais::create([
            'nombre' => $this->pais->nombre, 
        ]);

        $response = $pais ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idPais = $id;
        $pais = Pais::find($id);
        
        $this->pais->fill([
            'nombre' => $pais->nombre, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idPais;

        $this->pais->validate();
        $pais = Pais::find($id);
        $pais = $pais->update($this->pais->only('nombre'));

        $response = $pais ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {

        $pais = Pais::find($id);
        $estado = $pais->estado;

        if($estado == 1)
        {
            $pais->estado = 0;
        }else{
            $pais->estado = 1;
        }
        $pais->save();
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Genero;
use App\Livewire\Form\GeneroForm;

class GeneroLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idGenero = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public GeneroForm $genero;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idGenero', 'genero']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $generos = Genero::where('nombre', 'like', '%' . $this->search . '%')
                            ->orderBy('id','desc')
                            ->paginate(5);

        return view('livewire.genero.genero-livewire', compact('generos'));
    }

    public function created()
    {
        $this->genero->validate();

        $genero = Genero::create([
            'nombre' => $this->genero->nombre, 
        ]);

        $response = $genero ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idGenero = $id;
        $genero = Genero::find($id);
        
        $this->genero->fill([
            'nombre' => $genero->nombre, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idGenero;

        $this->genero->validate();
        $genero = Genero::find($id);
        $genero = $genero->update($this->genero->only('nombre'));

        $response = $genero ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {

        $genero = Genero::find($id);
        $estado = $genero->estado;

        if($estado == 1)
        {
            $genero->estado = 0;
        }else{
            $genero->estado = 1;
        }
        $genero->save();
    }
}

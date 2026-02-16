<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Gestion;
use App\Livewire\Form\GestionForm;

class GestionLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idGestion = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public GestionForm $gestion;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idGestion', 'gestion']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $gestiones = Gestion::where('gestion', 'like', '%' . $this->search . '%')
                            ->orWhere('nombre', 'like', '%' . $this->search . '%')
                            ->orderBy('id','desc')
                            ->paginate(5);

        return view('livewire.gestion.gestion-livewire', compact('gestiones'));
    }

    public function created()
    {
        $this->gestion->validate();

        $gestion = Gestion::create([
            'gestion' => $this->gestion->gestion, 
            'nombre' => $this->gestion->nombre, 
        ]);

        $response = $gestion ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idGestion = $id;
        $gestion = Gestion::find($id);
        
        $this->gestion->fill([
            'gestion' => $gestion->gestion, 
            'nombre' => $gestion->nombre, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idGestion;

        $this->gestion->validate();
        $gestion = Gestion::find($id);
        $gestion = $gestion->update($this->gestion->only('gestion', 'nombre'));

        $response = $gestion ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {

        $gestion = Gestion::find($id);
        $estado = $gestion->estado;

        if($estado == 1)
        {
            $gestion->estado = 0;
        }else{
            $gestion->estado = 1;
        }
        $gestion->save();
    }
}

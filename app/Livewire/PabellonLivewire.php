<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Form\PabellonForm;
use App\Models\Pabellon;

class PabellonLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idPabellon = "";

    public $openModalNew = false;
    public $openModalEdit = false;
    public PabellonForm $pabellon;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'pabellon', 'idPabellon']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $pabellones = Pabellon::where('numero_pabellon', 'like', '%' . $this->search . '%')
                                ->orderBy('id','desc')
                                ->paginate(5);

        return view('livewire.pabellon.pabellon-livewire', compact('pabellones'));

    }

    public function created()
    {
        $this->pabellon->validate();

        $pabellon =  Pabellon::create([
            'numero_pabellon' => $this->pabellon->numero_pabellon, 
        ]);

        $response = $pabellon ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idPabellon = $id;
        $pabellon = Pabellon::find($id);
        
        $this->pabellon->fill([
            'numero_pabellon' => $pabellon->numero_pabellon, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idPabellon;

        $this->pabellon->validate();
        $pabellon = Pabellon::find($id);
        $pabellon = $pabellon->update($this->pabellon->only('numero_pabellon'));

        $response = $pabellon ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function eliminarEstado($id)
    {

        $pabellon = Pabellon::find($id);
        $estado = $pabellon->estado;

        if($estado == 1)
        {
            $pabellon->estado = 0;
        }else{
            $pabellon->estado = 1;
        }
        $pabellon->save();
    }
}

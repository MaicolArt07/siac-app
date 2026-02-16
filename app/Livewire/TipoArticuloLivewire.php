<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\TipoArticulo;
use App\Livewire\Form\TipoArticuloForm;

class TipoArticuloLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idTipoArticulo = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public TipoArticuloForm $tipoarticulo;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idTipoArticulo', 'tipoarticulo']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $tipoarticulos = TipoArticulo::where('nombre', 'like', '%' . $this->search . '%')
                                    ->orderBy('id','desc')
                                    ->paginate(5);

        return view('livewire.tipoarticulo.tipo-articulo-livewire', compact('tipoarticulos'));
    }

    public function created()
    {
        $this->tipoarticulo->validate();

        $tipoarticulo = TipoArticulo::create([
            'nombre' => $this->tipoarticulo->nombre, 
        ]);

        $response = $tipoarticulo ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idTipoArticulo = $id;
        $tipoarticulo = TipoArticulo::find($id);
        
        $this->tipoarticulo->fill([
            'nombre' => $tipoarticulo->nombre, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idTipoArticulo;

        $this->tipoarticulo->validate();
        $tipoarticulo = TipoArticulo::find($id);
        $tipoarticulo = $tipoarticulo->update($this->tipoarticulo->only('nombre'));

        $response = $tipoarticulo ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {
        $tipoarticulo = TipoArticulo::find($id);
        $estado = $tipoarticulo->estado;

        if($estado == 1)
        {
            $tipoarticulo->estado = 0;
        }else{
            $tipoarticulo->estado = 1;
        }
        $tipoarticulo->save();
    }
}

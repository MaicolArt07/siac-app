<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\TipoPago;
use App\Livewire\Form\TipoPagoForm;

class TipoPagoLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idTipoPago = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public TipoPagoForm $tipopago;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idTipoPago', 'tipopago']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        $tipopagos = TipoPago::where('nombre', 'like', '%' . $this->search . '%')
                                    ->orderBy('id','desc')
                                    ->paginate(5);

        return view('livewire.tipopago.tipo-pago-livewire', compact('tipopagos'));
    }

    public function created()
    {
        $this->tipopago->validate();

        $tipopago = TipoPago::create([
            'nombre' => $this->tipopago->nombre, 
        ]);

        $response = $tipopago ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idTipoPago = $id;
        $tipopago = TipoPago::find($id);
        
        $this->tipopago->fill([
            'nombre' => $tipopago->nombre, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idTipoPago;

        $this->tipopago->validate();
        $tipopago = TipoPago::find($id);
        $tipopago = $tipopago->update($this->tipopago->only('nombre'));

        $response = $tipopago ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {
        $tipopago = TipoPago::find($id);
        $estado = $tipopago->estado;

        if($estado == 1)
        {
            $tipopago->estado = 0;
        }else{
            $tipopago->estado = 1;
        }
        $tipopago->save();
    }
}

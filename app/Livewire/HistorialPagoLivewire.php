<?php

namespace App\Livewire;

use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class HistorialPagoLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idGestion = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    // public GestionForm $gestion;

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
        // $pagos = DB::table('v_pagos')->where('gestion', 'like', '%' . $this->search . '%')
        //                                 ->orWhere('nombre', 'like', '%' . $this->search . '%')
        //                                 ->orderBy('id','desc')
        //                                 ->paginate(5);
        $pagos = DB::table('v_pago')
                            ->where(function($query) {
                                $query->where('nombre', 'like', '%' . $this->search . '%')
                                    ->orWhere('apellido', 'like', '%' . $this->search . '%')
                                    ->orWhere('ci', 'like', '%' . $this->search . '%')
                                    ->orWhere('descripcion', 'like', '%' . $this->search . '%');

                            })
                            ->orderBy('id', 'desc')
                            ->paginate(5);

        return view('livewire.historial.historial-pago', compact('pagos'));

    }

    // public function created()
    // {
    //     $this->gestion->validate();

    //     $gestion = Gestion::create([
    //         'gestion' => $this->gestion->gestion, 
    //         'nombre' => $this->gestion->nombre, 
    //     ]);

    //     $response = $gestion ? true : false;
    //     $this->dispatch('notificar', message: $response);
    //     $this->resetAttribute();
    // }

    // public function edit($id)
    // {
    //     $this->idGestion = $id;
    //     $gestion = Gestion::find($id);
        
    //     $this->gestion->fill([
    //         'gestion' => $gestion->gestion, 
    //         'nombre' => $gestion->nombre, 
    //     ]);

    //     $this->openModalEdit = true;
    // }

    // public function update()
    // {
    //     $id = $this->idGestion;

    //     $this->gestion->validate();
    //     $gestion = Gestion::find($id);
    //     $gestion = $gestion->update($this->gestion->only('gestion', 'nombre'));

    //     $response = $gestion ? true : false;
    //     $this->resetAttribute();
    //     $this->dispatch('notificar', message: $response);
    // }

    #[On('delete')]
    public function destroy($id)
    {

        $pago = Pago::find($id);
        $estado = $pago->estado;

        if($estado == 1)
        {
            $pago->estado = 0;
        }else{
            $pago->estado = 1;
        }
        $pago->save();
    }
}

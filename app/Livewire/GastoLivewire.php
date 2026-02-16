<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Gasto;
use App\Livewire\Form\GastoForm;
use App\Models\Gestion;
use App\Models\Periodo;
use App\Models\Articulo;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class GastoLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idGasto = "";
    public $idPeriodo = "";
    public $idGestion = "";
    public $openModalNew = false;
    public $openModalEdit = false;

    public GastoForm $gasto;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idGasto', 'gasto', 'idGasto', 'idPeriodo', 'idGestion']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        $this->resetValidation();
    }

    public function render()
    {
        // $gastos = DB::table("v_gasto")->paginate(5);

        $gastos = DB::table('v_gasto')
                    ->where(function($query) {
                        $query->where('descripcion', 'like', '%' . $this->search . '%')
                            ->orWhere('monto', 'like', '%' . $this->search . '%')
                            ->orWhere('sigla', 'like', '%' . $this->search . '%')
                            ->orWhere('gestion', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(5);

        $gestiones = Gestion::where('estado', 1)->get();
        
        $periodos = collect();
        $articulos = collect();

        if(isset($this->idGestion) && !empty($this->idGestion))
        {
            $periodos = Periodo::where('id_gestion', $this->idGestion)->get();
        }

        if(isset($this->idPeriodo) && !empty($this->idPeriodo))
        {
            $articulos = Articulo::where('id_periodo', $this->idPeriodo)->where('estado', 1)->where('id_tipoarticulo', 2)->get();
        }

        $gestionActual = Carbon::now()->year; // Obtener la gestión actual (año actual)
        $mes = Carbon::now()->month;

        $articulosEdit = DB::table("v_articulo")->where('periodo', $mes)->where('gestion', $gestionActual)->where('estado', 1)->where('id_tipoarticulo', 2)->get();

        return view('livewire.gasto.gasto-livewire', compact('gastos', 'periodos', 'articulos', 'gestiones', 'articulosEdit'));
    }

    public function seleccionarGestion($gestionId)
    {
        $this->idGestion = $gestionId;
    }

    public function seleccionarPeriodo($periodoId)
    {
        $this->idPeriodo = $periodoId;
        // dd($this->idPeriodo);
    }


    public function created()
    {
        // $this->gasto->validate();

        $gasto = Gasto::create([
            'id_articulo' => $this->gasto->id_articulo, 
        ]);

        $response = $gasto ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idGasto = $id;
        $gasto = Gasto::find($id);
        
        $this->gasto->fill([
            'id_articulo' => $gasto->id_articulo, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idGasto;

        // $this->gasto->validate();
        
        $gasto = Gasto::find($id);
        $gasto = $gasto->update($this->gasto->only('id_articulo'));

        $response = $gasto ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {
        $gasto = Gasto::find($id);
        $estado = $gasto->estado;

        if($estado == 1)
        {
            $gasto->estado = 0;
        }else{
            $gasto->estado = 1;
        }
        $gasto->save();
    }
}

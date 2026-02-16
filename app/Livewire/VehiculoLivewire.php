<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Vehiculo;
use App\Models\Estacionamiento;
use Illuminate\Support\Facades\DB;
use App\Livewire\Form\VehiculoForm;

class VehiculoLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $searchCopropietario;
    public $selectedCopropietario = false;
    public $obtenerIdCopropietario = "";
    public $idVehiculo = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public VehiculoForm $vehiculo;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'selectedCopropietario', 'obtenerIdCopropietario', 'idVehiculo', 'searchCopropietario', 'vehiculo']);
    }
    

    public function closeModal()
    {
        $this->resetAttribute();
        // $this->resetErrorBag();
        $this->resetValidation();
    }


    public function render()
    {
        $vehiculos = DB::table('v_vehiculo')
                            ->where('nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('apellido', 'like', '%' . $this->search . '%')
                            ->orWhere('ci', 'like', '%' . $this->search . '%')
                            ->orWhere('numero_estacionamiento', 'like', '%' . $this->search . '%')
                            ->orderBy('id','desc')
                            ->paginate(10);

        $copropietarios = collect();

        if(!empty($this->searchCopropietario))
        {
            $copropietarios = DB::table('v_copropietario')
                                    ->where('estado', 1) 
                                    ->where(function($query) {
                                        $query->where('nombre', 'like', '%' . $this->searchCopropietario . '%')
                                            ->orWhere('apellido', 'like', '%' . $this->searchCopropietario . '%')
                                            ->orWhere('ci', 'like', '%' . $this->searchCopropietario . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->limit(5)
                                    ->get();
        }

        $estacionamientos = Estacionamiento::all();

        return view('livewire.vehiculo.vehiculo-livewire', compact('vehiculos', 'copropietarios','estacionamientos'));

    }

    public function seleccionarCopropietario($id)
    {
        $this->obtenerIdCopropietario = $id;


        $this->selectedCopropietario = !$this->selectedCopropietario;

        if(!$this->selectedCopropietario)
        {
            $this->reset(['obtenerIdCopropietario']);
        }
    }

    public function created()
    {
        $this->vehiculo->id_copropietario = $this->obtenerIdCopropietario;
        $this->vehiculo->validate();

        $vehiculo = Vehiculo::create([
            'id_copropietario' => $this->vehiculo->id_copropietario, 
            'id_estacionamiento' => $this->vehiculo->id_estacionamiento, 
            'color' => $this->vehiculo->color, 
            'marca' => $this->vehiculo->marca, 
            'placa' => $this->vehiculo->placa, 
        ]);
        $response = $vehiculo ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idVehiculo = $id;
        $vehiculo = Vehiculo::find($id);
        $nombre_compropietario = DB::table('v_copropietario')->where('id', $vehiculo->id_copropietario)->first();
        $this->searchCopropietario = $nombre_compropietario->nombre;
        $this->obtenerIdCopropietario = $vehiculo->id_copropietario;
        $this->selectedCopropietario = true;

        $this->vehiculo->fill([
            'id_copropietario' => $this->obtenerIdCopropietario, 
            'id_estacionamiento' => $vehiculo->id_estacionamiento, 
            'color' => $vehiculo->color, 
            'marca' => $vehiculo->marca,
            'placa' => $vehiculo->placa, 
        ]);
        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idVehiculo;

        $this->vehiculo->fill([
            'id_copropietario' => $this->obtenerIdCopropietario, 
        ]);

        $this->vehiculo->validate();
        $vehiculo = Vehiculo::find($id);
        $vehiculo = $vehiculo->update($this->vehiculo->only('id_copropietario','id_estacionamiento','color','marca','placa'));
        // dd($this->vehiculo);
        $response = $vehiculo ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {  
        $vehiculo = Vehiculo::find($id);
        $estado = $vehiculo->estado;

        if($estado == 1)
        {
            $vehiculo->estado = 0;
        }else{
            $vehiculo->estado = 1;
        }
        $vehiculo->save();
    }
}

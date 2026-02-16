<?php

namespace App\Livewire;

use App\Models\Apartamento;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Persona;
use App\Models\Copropietario;
use App\Livewire\Form\CopropietarioForm;

class CopropietarioLivewire extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $searchPersona;
    public $selectedPersona = false;
    public $obtenerIdPersona = "";
    public $idCopropietario = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public CopropietarioForm $copropietario;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'selectedPersona', 'obtenerIdPersona', 'idCopropietario', 'searchPersona', 'copropietario']);
    }
    

    public function closeModal()
    {
        $this->resetAttribute();
        // $this->resetErrorBag();
        $this->resetValidation();
    }

    public function UserIdCondominio()
    {
        $userAuth = auth()->user();
        // Obtenemos el id del condominio del usuario para por defecto que se esta agregando un copropietario en ese condominio.
        $userCondominio = $userAuth->id_condominio;
        return $userCondominio;
    }

    public function render()
    {
        $userCondominio = $this->UserIdCondominio();
        $copropietarios = DB::table('v_copropietario')
                                ->where('id_condominio', $userCondominio)
                                ->where(function ($query) {
                                    $query->where('nombre', 'like', '%' . $this->search . '%')
                                        ->orWhere('apellido', 'like', '%' . $this->search . '%')
                                        ->orWhere('ci', 'like', '%' . $this->search . '%')
                                        ->orWhere('pais', 'like', '%' . $this->search . '%')
                                        ->orWhere('numero_apartamento', 'like', '%' . $this->search . '%');
                                })
                                ->orderBy('id', 'desc')
                                ->paginate(10);
    

        $personas = collect();

        if(!empty($this->searchPersona))
        {
            $personas = Persona::where('estado', 1)
                                ->where(function($query) {
                                    $query->where('nombre', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('apellido', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('ci', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('correo', 'like', '%' . $this->searchPersona . '%');
                                })
                                ->limit(5)
                                ->get();
        }

        $apartamentos = Apartamento::where('id_condominio', $userCondominio)->get();

        return view('livewire.copropietario.copropietario-livewire', compact('copropietarios', 'personas', 'apartamentos'));
    }

    public function seleccionarPersona($id)
    {
        $this->obtenerIdPersona = $id;
        $this->selectedPersona = !$this->selectedPersona;

        if(!$this->selectedPersona)
        {
            $this->reset(['obtenerIdPersona']);
        }
    }

    public function created()
    {
        $userAuth = auth()->user();
        // Obtenemos el id del condominio del usuario para por defecto que se esta agregando un copropietario en ese condominio.
        $user_condominio = $userAuth->id_condominio;
        // dd($user);
        $this->copropietario->id_persona = $this->obtenerIdPersona;
        $this->copropietario->validate();

        $copropietario = Copropietario::create([
            'id_persona' => $this->copropietario->id_persona, 
            'id_condominio' => $user_condominio,
            'id_apartamento' => $this->copropietario->id_apartamento, 
            'cant_residentes' => $this->copropietario->cant_residentes, 
            'cant_mascotas' => $this->copropietario->cant_mascotas, 
        ]);

        // Se procedera a dar de baja el apartamente porque ya se encuentra ocupado
        $apartamento = Apartamento::find($this->copropietario->id_apartamento);
        $apartamento->estado = 0;
        $apartamento->save();

        $response = $copropietario ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idCopropietario = $id;
        $copropietario = Copropietario::find($id);
        $nombre = DB::table('v_persona')->where('id', $copropietario->id_persona)->first();
        $this->searchPersona = $nombre->nombre;
        $this->obtenerIdPersona = $copropietario->id_persona;
        $this->selectedPersona = true;

        // dd($this->idCopropietario);
        $this->copropietario->fill([
            'id_persona' => $this->obtenerIdPersona, 
            'id_apartamento' => $copropietario->id_apartamento, 
            'cant_residentes' => $copropietario->cant_residentes, 
            'cant_mascotas' => $copropietario->cant_mascotas, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idCopropietario;

        $this->copropietario->fill([
            'id_persona' => $this->obtenerIdPersona
        ]);

        $this->copropietario->validate();

        $copropietarios = Copropietario::find($id);
        $estado_apartamento = $copropietarios->id_apartamento;

        // SE ACTIVARIA NUEVAMENTE EL APARTAMENTO
        if($estado_apartamento != $this->copropietario->id_apartamento)
        {
            $apartamento = Apartamento::find($copropietarios->id_apartamento);
            $apartamento->estado = 1;
            $apartamento->save();

            $apartamentoOcupado = Apartamento::find($this->copropietario->id_apartamento);
            $apartamentoOcupado->estado = 0;
            $apartamentoOcupado->save();
        }

        $copropietario = $copropietarios->update($this->copropietario->only('id_persona','id_apartamento','cant_residentes','cant_mascotas'));

        $response = $copropietario ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {  
        $copropietario = Copropietario::find($id);
        $estado = $copropietario->estado;

        $apartamento = Apartamento::find($copropietario->id_apartamento);


        if($estado == 1)
        {
            $copropietario->estado = 0;
            $apartamento->estado = 1;
        }else{
            $copropietario->estado = 1;
            $apartamento->estado = 0;
        }
        $copropietario->save();
        $apartamento->save();
    }
}

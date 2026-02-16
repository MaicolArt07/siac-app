<?php

namespace App\Livewire;

use App\Livewire\Form\FuncionarioForm;
use App\Models\Cargo;
use App\Models\Funcionario;
use Livewire\Component;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;
class FuncionarioLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $searchPersona;
    public $selectedPersona = false;
    public $obtenerIdPersona = "";
    public $idFuncionario = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public FuncionarioForm $funcionario;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'funcionario', 'selectedPersona', 'obtenerIdPersona', 'idFuncionario', 'searchPersona']);
        
        // Restablecer los errores de validación
    }
    

    public function closeModal()
    {
        $this->resetAttribute();
        // $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {

        $funcionarios = DB::table("v_funcionario")
                        ->where('persona', 'like', '%' . $this->search . '%')
                        ->orWhere('ci', 'like', '%' . $this->search . '%')
                        ->orderBy('id','desc')
                        ->paginate(5);

        $cargos = Cargo::all();         
        $personas = collect();

        if(!empty($this->searchPersona))
        {
            $personas = Persona::where('estado', 1)
                                ->where(function($query) {
                                    $query->where('nombre', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('apellido', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('ci', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('correo', 'like', '%' . $this->searchPersona . '%')
                                            ->orderBy('id','desc');
                                })
                                ->limit(5)
                                ->get();
        }
        return view('livewire.funcionario.funcionario-livewire', compact('funcionarios', 'personas', 'cargos'));
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

        $this->funcionario->id_persona = $this->obtenerIdPersona;
        $this->funcionario->validate();

        $funcionario =  Funcionario::create([
            'id_persona' => $this->funcionario->id_persona, 
            'id_cargo' => $this->funcionario->id_cargo, 
            'salario' => $this->funcionario->salario, 
        ]);

       $response = $funcionario ? true : false;
       $this->dispatch('notificar', message: $response);
       $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idFuncionario = $id;
        $funcionario = Funcionario::find($id);

        $nombre = DB::table('v_persona')->where('id', $funcionario->id_persona)->first();
        $this->searchPersona = $nombre->nombre;
        $this->obtenerIdPersona = $funcionario->id_persona;
        $this->selectedPersona = true;

        $this->funcionario->fill([
            'id_persona' => $this->obtenerIdPersona, 
            'id_cargo' => $funcionario->id_cargo, 
            'salario' => $funcionario->salario, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idFuncionario;

        $this->funcionario->fill([
            'id_persona' => $this->obtenerIdPersona
        ]);
        
        $this->funcionario->validate();
        $funcionario = Funcionario::find($id);
        $funcionario = $funcionario->update($this->funcionario->only('id_persona','id_cargo','salario'));

        $response = $funcionario ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {
        $funcionario = Funcionario::find($id);
        $estado = $funcionario->estado;

        if($estado == 1)
        {
            $funcionario->estado = 0;
        }else{
            $funcionario->estado = 1;
        }
        $funcionario->save();
    }
}

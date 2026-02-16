<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use App\Livewire\Form\UsersForm;
use App\Models\Condominio;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;

class UserLivewire extends Component
{
    use WithPagination;

    // public function __construct()
    // {
    //     // $this->middleware('can:login')->only('render');
    //     $this->middleware('can:login')->only('edit');
    // }

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $searchPersona;
    public $obtenerIdPersona = "";
    // public $obtenerIdUser = "";

    public $openModalEdit = false;
    public $openModalNew = false;
    public $openModalResetPassword = false;

    public $idUsuario;
    public $selectedRoles;
    // public $selectedRolesUser;
    public $selectedPersona = false;
    
    public UsersForm $usuarios;

    public $password = "";
    public $confirmPassword = "";
    

    public function seleccionarPersona($id)
    {
        $this->obtenerIdPersona = $id;
        $this->selectedPersona = !$this->selectedPersona;

        if(!$this->selectedPersona)
        {
            $this->reset(['obtenerIdPersona']);
        }
    }

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit','openModalResetPassword','obtenerIdPersona', 'usuarios', 'search', 'selectedPersona', 'searchPersona', 'selectedRoles', 'idUsuario', 'password', 'confirmPassword']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
        // $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('name','LIKE', '%'.$this->search.'%')
                    ->orWhere('email','LIKE', '%'.$this->search.'%')
                    ->paginate(5);

        $roles = Role::all();
        $personas = collect();
        $condominios = Condominio::all();

        if(!empty($this->searchPersona))
        {
            $personas = Persona::where('estado', 1)
                                ->where(function($query) {
                                    $query->where('nombre', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('apellido', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('ci', 'like', '%' . $this->searchPersona . '%')
                                            ->orWhere('correo', 'like', '%' . $this->searchPersona . '%');
                                })
                                ->whereNotIn('id', function($query) {
                                    $query->select('id_persona')->from('users');
                                })
                                ->limit(5)
                                ->get();

                              

                                
        }

        return view('livewire.user.user-livewire', compact('users', 'roles', 'personas','condominios'));
    }

    public function edit($id)
    {
        $this->idUsuario = $id;
        $user = User::find($id);
        $this->usuarios->email = $user->email;
        
        // Obtener los roles del usuario y establecerlos como seleccionados
        $selectedRoles = $user->roles->pluck('id')->toArray();
        // $this->selectedRoles = $selectedRoles;
        $this->selectedRoles = $selectedRoles;
        // foreach ($selectedRoles as $rol) 
        // {
        //     $this->selectedRoles[$rol] = true;
        // }

        $this->openModalEdit = true;
    }

    
    public function update()
    {
        $id = $this->idUsuario;
        $request = User::find($id);
        // $selectedRoles = array_keys(array_filter($this->selectedRoles));
        $selectedRoles = $this->selectedRoles;

        // dd($selectedRoles);

        $request->roles()->sync($selectedRoles);

        // DB::table('model_has_roles')->update([
        //     'role_id' => $selectedRoles
        // ]);
        // $request->role_id = $selectedRoles;
        // $request->save();
        $this->dispatch('notificar', message: true);
        $this->resetAttribute();

    }

    public function create()
    {
        $this->usuarios->id_persona = $this->obtenerIdPersona;
        $this->usuarios->validate();

        // dd($this->usuarios->persona_id);
        // Buscamos el dato nombre y apellido en la persona
        $persona = Persona::find($this->obtenerIdPersona);

        $nombre_persona = $persona->nombre ." ". $persona->apellido;



        $usuario =  User::create([
            'id_persona' => $this->usuarios->id_persona, 
            'id_condominio' => $this->usuarios->id_condominio, 
            'name' => $nombre_persona,
            'email' => $this->usuarios->email,
            'password' => Hash::make($this->password),
        ]);

       $response = $usuario ? true : false;
       $this->dispatch('notificar', message: $response);
       $this->resetAttribute();

    }

    // public function changePassword()
    // {
    //     dd($this->password);
    // }
    
    public function resetPassword($id)
    {
        $this->openModalResetPassword = true;
        $this->idUsuario = $id;
    }

    public function updatePassword()
    {
        $id = $this->idUsuario;
        // dd($id);
        $user = User::find($id);
        $usuario = $user->update([
            'password' => Hash::make($this->usuarios->password)
        ]);

       $response = $usuario ? true : false;
       $this->dispatch('notificar', message: $response);
       $this->resetAttribute();
    }

    #[On('delete')]
    public function eliminar($id)
    {
        $user = User::find($id);
        $estado = $user->estado;

        if($estado == 1)
        {
            $user->estado = 0;
        }else{
            $user->estado = 1;
        }
        $user->save();
    }
}

<?php

namespace App\Livewire;

use App\Livewire\Form\RolForm;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
class RolLivewire extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    public $searchPermission;
    public $search;

    public $openModalEdit = false;
    public $openModalNew = false;
    public $selectedPermission = [];
    public $idRol;

    public RolForm $rol;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'rol', 'selectedPermission', 'searchPermission']);
    }
    
    public function closeModal()
    {
        $this->resetAttribute();
        // $this->resetErrorBag();
        $this->resetValidation();
        // $this->reset(['openModalNew', 'openModalEdit', 'search', 'rol', 'selectedPermission', 'searchPermission']);
    }

    public function render()
    {
        $roles = Role::all();
        // $permissions = Permission::paginate(5);
        $permissions = DB::table('v_permission')
                        ->where('name', 'like', '%' . $this->searchPermission . '%')
                        // ->orWhere('nombre', 'like', '%' . $this->searchPermission . '%')
                        ->paginate(5);

        return view('livewire.rol.rol-livewire', compact('roles','permissions'));
    }

    public function created()
    {
        $role = Role::create([
            'name' => $this->rol->name
        ]);
        $selectedPermission = array_keys(array_filter($this->selectedPermission));
        $role->permissions()->sync($selectedPermission);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->openModalEdit = true;
        $this->idRol = $id;
        $rol = Role::find($id);
        $this->rol->name = $rol->name;
        
        // Obtener los permisos asociados al rol
        $selectedPermissions = $rol->permissions->pluck('id')->toArray();

        // Inicializar el array selectedPermission con los permisos asociados al rol
        foreach ($selectedPermissions as $permissionId) 
        {
            $this->selectedPermission[$permissionId] = true;
        }
    }

    public function update()
    {
        $this->openModalEdit = false;

        $id = $this->idRol;
        $rol = Role::find($id);
        $rol->update(['name' => $this->rol->name]);
        
        $selectedPermission = array_keys(array_filter($this->selectedPermission));
        $rol->permissions()->sync($selectedPermission);
        $this->dispatch('notificar', message: true);
        $this->resetAttribute();
    }

    #[On('delete')]
    public function eliminarEstado($id)
    {

        $rol = Role::find($id);
        $estado = $rol->estado;

        if($estado == 1)
        {
            $rol->estado = 0;
        }else{
            $rol->estado = 1;
        }
        $rol->save();
    }
}

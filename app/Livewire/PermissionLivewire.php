<?php

namespace App\Livewire;

use Spatie\Permission\Models\Permission;
use Livewire\Component;
use App\Livewire\Form\PermissionForm;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class PermissionLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $idPermission;
    public $openModalEdit = false;
    public $openModalNew = false;
    public $search;

    public PermissionForm $permission;

    public function closeModal()
    {
        $this->reset(['openModalEdit', 'openModalNew', 'permission']);
        // $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $permissions = Permission::where("name", 'LIKE', '%' . $this->search . '%')
                        ->orWhere("description", 'LIKE', '%' . $this->search . '%')
                        ->paginate(5);
                        
        return view('livewire.permission.permission-livewire', compact('permissions'));
    }

    public function created()
    {
        $permission = Permission::create([
            'name' => $this->permission->name,
            'description' => $this->permission->description
        ]);
        $response = $permission ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->reset(['openModalEdit', 'openModalNew', 'permission']);
    }

    public function edit($id)
    {
        $this->idPermission = $id;
        $permission = Permission::find($id);
        $this->permission->name = $permission->name;
        $this->permission->description = $permission->description;
        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idPermission;
        $permissionPost = Permission::find($id);
        $permission = $permissionPost->update($this->permission->only('name', 'description'));
        $response = $permission ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->reset(['openModalEdit', 'openModalNew', 'permission']);
    }

    #[On('delete')]
    public function delete($id)
    {
        $permission = Permission::find($id);
        $estado = $permission->estado;

        if($estado == 1)
        {
            $permission->estado = 0;
        }else{
            $permission->estado = 1;
        }
        $permission->save();
    }
}

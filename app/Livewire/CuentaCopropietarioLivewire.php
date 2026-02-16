<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Persona;
use App\Models\Copropietario;

class CuentaCopropietarioLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;

    public function render()
    {
        $copropietarios = DB::table('v_copropietario')
                            ->where(function($query) {
                                $query->where('nombre', 'like', '%' . $this->search . '%')
                                    ->orWhere('apellido', 'like', '%' . $this->search . '%')
                                    ->orWhere('ci', 'like', '%' . $this->search . '%')
                                    ->orWhere('pais', 'like', '%' . $this->search . '%')
                                    ->orWhere('numero_apartamento', 'like', '%' . $this->search . '%');
                            })
                            ->orderBy('id', 'desc')
                            ->paginate(10);

        return view('livewire.cuentaCopropietario.cuenta-copropietario-livewire', compact('copropietarios'));
    }


    
}

<?php

namespace App\Livewire;

use App\Models\Deuda;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;


class DeudaLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;

    public function render()
    {
        $deudas = DB::table('v_deuda')
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido', 'like', '%' . $this->search . '%')
                    ->orWhere('ci', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(5);
        return view('livewire.deuda.deuda-livewire', compact('deudas'));
    }
    
    #[On('delete')]
    public function destroy($id)
    {

        $deuda = Deuda::find($id);
        $estado = $deuda->estado;

        if($estado == 1)
        {
            $deuda->estado = 0;
        }else{
            $deuda->estado = 1;
        }
        $deuda->save();
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Apartamento;
use App\Livewire\Form\ApartamentoForm;

class ApartamentoLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $idApartamento = "";

    public $openModalNew = false;
    public $openModalEdit = false;

    public ApartamentoForm $apartamento;

    public function resetAttribute()
    {
        $this->reset(['openModalNew', 'openModalEdit', 'search', 'idApartamento', 'apartamento']);
    }

    public function closeModal()
    {
        $this->resetAttribute();
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

        $apartamentos = Apartamento::where('numero_apartamento', 'like', '%' . $this->search . '%')
                                    ->where('id_condominio', $userCondominio)
                                    ->orderBy('id','desc')
                                    ->paginate(4);

        return view('livewire.apartamento.apartamento-livewire', compact('apartamentos'));
    }

    public function created()
    {
        $this->apartamento->validate();
        $userCondominio = $this->UserIdCondominio();

        $apartamento = Apartamento::create([
            'id_condominio' => $userCondominio,
            'numero_apartamento' => $this->apartamento->numero_apartamento, 
        ]);

        $response = $apartamento ? true : false;
        $this->dispatch('notificar', message: $response);
        $this->resetAttribute();
    }

    public function edit($id)
    {
        $this->idApartamento = $id;
        $apartamento = Apartamento::find($id);
        
        $this->apartamento->fill([
            'numero_apartamento' => $apartamento->numero_apartamento, 
        ]);

        $this->openModalEdit = true;
    }

    public function update()
    {
        $id = $this->idApartamento;

        $this->apartamento->validate();
        $apartamento = Apartamento::find($id);
        $apartamento = $apartamento->update($this->apartamento->only('numero_apartamento'));

        $response = $apartamento ? true : false;
        $this->resetAttribute();
        $this->dispatch('notificar', message: $response);
    }

    #[On('delete')]
    public function destroy($id)
    {

        $apartamento = Apartamento::find($id);
        $estado = $apartamento->estado;

        if($estado == 1)
        {
            $apartamento->estado = 0;
        }else{
            $apartamento->estado = 1;
        }
        $apartamento->save();
    }
}

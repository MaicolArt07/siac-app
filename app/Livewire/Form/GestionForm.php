<?php

namespace App\Livewire\Form;

use Livewire\Form;

class GestionForm extends Form
{

    public $nombre;
    public $gestion;


    public function rules()
    {
        return [
            'nombre' => 'required|numeric|min:1',
            'gestion' => 'required|numeric|min:1',
        ];
    }
    
    public function messages()
    {
        return [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.numeric' => 'El campo Nombre debe ser un número.',
            'nombre.min' => 'El campo Nombre debe tener al menos :max caracteres.',
    
            'gestion.required' => 'El campo Gestión es obligatorio.',
            'gestion.numeric' => 'El campo Gestión debe ser un número.',
            'gestion.min' => 'El campo Gestión debe tener al menos :min caracteres.',
        ];
    }
    
    public function validationAttributes()
    {
        return [
            'nombre' => 'Nombre',
            'gestion' => 'Gestión',
        ];
    }
    
}

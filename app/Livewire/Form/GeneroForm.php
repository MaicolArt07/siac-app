<?php

namespace App\Livewire\Form;

use Livewire\Form;

class GeneroForm extends Form
{

    public $nombre;


    public function rules()
    {
        return [
            'nombre' => 'required|string|min:1',
        ];
    }
    
    public function messages()
    {
        return [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El campo Nombre debe ser una cadena de caracteres .',
            'nombre.min' => 'El campo Nombre debe tener al menos :max caracteres.',
        ];
    }
    
    public function validationAttributes()
    {
        return [
            'nombre' => 'Nombre',
        ];
    }
    
}

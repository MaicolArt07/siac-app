<?php

namespace App\Livewire\Form;

use Livewire\Form;

class TipoArticuloForm extends Form
{

    public $nombre;


    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
        ];
    }
    
    public function messages()
    {
        return [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El campo Nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El campo Nombre no puede exceder los :max caracteres.',
        ];
    }
    
    public function validationAttributes()
    {
        return [
            'nombre' => 'Nombre',
        ];
    }
    
}

<?php

namespace App\Livewire\Form;

use Livewire\Form;

class CargoForm extends Form
{

    public $nombre;
    public $descripcion;

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El campo nombre no debe exceder los :max caracteres.',
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de caracteres.',
            'descripcion.max' => 'El campo descripción no debe exceder los :max caracteres.',
        ];
    }
    public function validationAttributes()
    {
        return [
            'nombre' => 'Nombre',
            'descripcion' => 'Descripción',
        ];
    }
}

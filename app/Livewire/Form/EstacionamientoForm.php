<?php

namespace App\Livewire\Form;

use Livewire\Form;

class EstacionamientoForm extends Form
{

    public $id_pabellon;
    public $numero_estacionamiento;


    public function rules()
    {
        return [
            'id_pabellon' => 'required|exists:pabellon,id',
            'numero_estacionamiento' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'id_pabellon.required' => 'El campo id de pabellon es obligatorio.',
            'id_pabellon.exists' => 'El id de pabellon proporcionado no es válido.',
            'numero_estacionamiento.required' => 'El campo número de estacionamiento es obligatorio.',
            'numero_estacionamiento.string' => 'El campo número de estacionamiento debe ser una cadena de caracteres.',
            'numero_estacionamiento.max' => 'El campo número de estacionamiento no debe exceder los :max caracteres.',
        ];
    }

    public function validationAttributes()
    {
        return [
            'id_pabellon' => 'ID del Pabellón',
            'numero_estacionamiento' => 'Número de Estacionamiento',
        ];
    }
}

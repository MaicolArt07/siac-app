<?php

namespace App\Livewire\Form;

use Livewire\Form;

class ApartamentoForm extends Form
{

    public $numero_apartamento;

    public function rules()
    {
        return [
            'numero_apartamento' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'numero_apartamento.required' => 'El campo número de apartamento es obligatorio.',
            'numero_apartamento.string' => 'El campo número de apartamento debe ser una cadena de caracteres.',
            'numero_apartamento.max' => 'El campo número de apartamento no debe exceder los :max caracteres.',
        ];
    }
    public function validationAttributes()
    {
        return [
            'numero_apartamento' => 'Número de Apartamento'
        ];
    }
}

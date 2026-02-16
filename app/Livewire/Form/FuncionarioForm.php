<?php

namespace App\Livewire\Form;

use Livewire\Form;

class FuncionarioForm extends Form
{

    public $id_persona;
    public $id_cargo;
    public $salario;

    public function rules()
    {
        return [
            'id_persona' => 'required|exists:persona,id',
            'id_cargo' => 'required|exists:cargo,id',
            'salario' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    public function messages()
    {
        return [
            'id_persona.required' => 'Debes buscar y seleccionar una persona.',
            'id_persona.exists' => 'El campo ID de persona debe ser un valor numérico.',
            'id_cargo.required' => 'El campo ID de cargo es requerido.',
            'id_cargo.exists' => 'El campo ID de cargo debe ser un valor numérico.',
            'salario.required' => 'El campo salario es requerido.',
            'salario.numeric' => 'El campo salario debe ser un valor numérico.',
            'salario.regex' => 'El campo salario debe tener el formato válido.',
        ];
    }
    public function validationAttributes()
    {
        return [
            'id_persona' => 'ID de persona',
            'id_cargo' => 'ID de cargo',
            'salario' => 'Salario',
        ];
    }
}

<?php

namespace App\Livewire\Form;

use Livewire\Form;

class CopropietarioForm extends Form
{

    public $id_persona;
    public $id_apartamento;
    public $cant_residentes;
    public $cant_mascotas;

    public function rules()
    {
        return [
            'id_persona' => 'required|exists:persona,id',
            'id_apartamento' => 'required|exists:apartamento,id',
            'cant_residentes' => 'required|numeric|min:1',
            'cant_mascotas' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'id_persona.required' => 'El campo ID de persona es obligatorio.',
            'id_persona.exists' => 'El ID de persona no existe en la tabla de personas.',

            'id_apartamento.required' => 'El campo ID de apartamento es obligatorio.',
            'id_apartamento.exists' => 'El ID de apartamento no existe en la tabla de apartamentos.',

            'cant_residentes.required' => 'El campo cantidad de residentes es obligatorio.',
            'cant_residentes.numeric' => 'La cantidad de residentes debe ser un valor numérico.',
            'cant_residentes.min' => 'La cantidad de residentes debe ser al menos 1.',

            'cant_mascotas.required' => 'El campo cantidad de mascotas es obligatorio.',
            'cant_mascotas.numeric' => 'La cantidad de mascotas debe ser un valor numérico.',
            'cant_mascotas.min' => 'La cantidad de mascotas no puede ser menor que 0.',
        ];
    }


    public function validationAttributes()
    {
        return [
            'id_persona' => 'ID de persona',
            'id_apartamento' => 'ID de apartamento',
            'cant_residentes' => 'Cantidad de residentes',
            'cant_mascotas' => 'Cantidad de mascotas',
        ];
    }

}

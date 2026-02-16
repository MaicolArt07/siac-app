<?php

namespace App\Livewire\Form;

use Livewire\Component;
use Livewire\Form;

class UsersForm extends Form
{
    public $id_persona;
    public $id_condominio;
    public $name;
    public $email;
    public $password;

    public function rules()
    {
        return [
            'id_persona' => 'required|exists:persona,id',
            'id_condominio' => 'required|exists:condominio,id',
        ];
    }

    public function messages()
    {
        return [
            'id_persona.required' => 'Debes buscar y seleccionar una persona.',
            'id_persona.exists' => 'El campo ID de persona debe ser un valor numérico.',
            'id_condominio.required' => 'Debes buscar y seleccionar un condominio.',
            'id_condominio.exists' => 'El campo ID de persona debe ser un valor numérico.',
        ];
    }
    public function validationAttributes()
    {
        return [
            'id_persona' => 'ID de persona',
            'id_condominio' => 'ID de condominio',
        ];
    }
}

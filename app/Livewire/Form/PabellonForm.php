<?php

namespace App\Livewire\Form;

use Livewire\Form;

class PabellonForm extends Form
{

    public $numero_pabellon;

    public function rules()
    {
        return [
            'numero_pabellon' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'numero_pabellon.required' => 'El campo número de pabellon es obligatorio.',
            'numero_pabellon.string' => 'El campo número de pabellon debe ser una cadena de caracteres.',
            'numero_pabellon.max' => 'El campo número de pabellon no debe exceder los :max caracteres.',
        ];
    }
    public function validationAttributes()
    {
        return [
            'numero_pabellon' => 'Número de pabellón'
        ];
    }
}

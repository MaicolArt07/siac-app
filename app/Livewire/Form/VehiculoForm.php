<?php

namespace App\Livewire\Form;

use Livewire\Form;

class VehiculoForm extends Form
{
    public $id_copropietario;
    public $id_estacionamiento;
    public $color;
    public $marca;
    public $placa;

    public function rules()
    {
        return [
            'id_copropietario' => 'required|integer|exists:copropietario,id',
            'id_estacionamiento' => 'required|integer|exists:estacionamiento,id',
            'color' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'id_copropietario.required' => 'El campo id_copropietario es obligatorio.',
            'id_copropietario.integer' => 'El campo id_copropietario debe ser un número entero.',
            'id_copropietario.exists' => 'El valor proporcionado para id_copropietario no existe en la base de datos.',
    
            'id_estacionamiento.required' => 'El campo id_estacionamiento es obligatorio.',
            'id_estacionamiento.integer' => 'El campo id_estacionamiento debe ser un número entero.',
            'id_estacionamiento.exists' => 'El valor proporcionado para id_estacionamiento no existe en la base de datos.',
    
            'color.required' => 'El campo color es obligatorio.',
            'color.string' => 'El campo color debe ser una cadena de caracteres.',
            'color.max' => 'El campo color no puede exceder los :max caracteres.',
    
            'marca.required' => 'El campo marca es obligatorio.',
            'marca.string' => 'El campo marca debe ser una cadena de caracteres.',
            'marca.max' => 'El campo marca no puede exceder los :max caracteres.',
    
            'placa.required' => 'El campo placa es obligatorio.',
            'placa.string' => 'El campo placa debe ser una cadena de caracteres.',
            'placa.regex' => 'El formato de la placa no es válido.',
            'placa.max' => 'El campo placa no puede exceder los :max caracteres.',
            'placa.exists' => 'El valor proporcionado para la placa no existe en la base de datos.',
        ];
    }
    
    
    public function validationAttributes()
    {
        return [
            'id_copropietario' => 'ID del Copropietario',
            'id_estacionamiento' => 'ID del Estacionamiento',
            'color' => 'Color',
            'marca' => 'Marca',
            'placa' => 'Placa',
        ];
    }
}

<?php

namespace App\Livewire\Form;

use Livewire\Form;

class ArticuloForm extends Form
{

    public $id_periodo;
    public $id_tipoarticulo;
    public $id_tipocuenta;
    public $descripcion;
    public $monto;


    public function rules()
    {
        return [
            'id_periodo' => 'required|exists:periodo,id',
            'id_tipoarticulo' => 'required|exists:tipo_articulo,id',
            'id_tipocuenta' => 'required|exists:tipo_cuenta,id',
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'id_periodo.required' => 'El campo período es obligatorio.',
            'id_periodo.exists' => 'El valor seleccionado para el campo período no es válido.',
            'id_tipoarticulo.required' => 'El campo tipo de artículo es obligatorio.',
            'id_tipoarticulo.exists' => 'El valor seleccionado para el campo tipo de artículo no es válido.',
            'id_tipocuenta.required' => 'El campo tipo de cuenta es obligatorio.',
            'id_tipocuenta.exists' => 'El valor seleccionado para el campo tipo de cuenta no es válido.',
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de caracteres.',
            'descripcion.max' => 'El campo descripción no debe exceder los :max caracteres.',
            'monto.required' => 'El campo monto es obligatorio.',
            'monto.numeric' => 'El campo monto debe ser un valor numérico.',
            'monto.min' => 'El campo monto debe ser como mínimo :min.',
        ];
    }

    public function validationAttributes()
    {
        return [
            'id_periodo' => 'Período',
            'id_tipoarticulo' => 'Tipo de Artículo',
            'id_tipocuenta' => 'Tipo de Cuenta',
            'descripcion' => 'Descripción',
            'monto' => 'Monto',
        ];
    }
}

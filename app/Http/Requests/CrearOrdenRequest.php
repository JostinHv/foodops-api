<?php

namespace App\Http\Requests;

class CrearOrdenRequest extends ValidacionRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente' => 'required|string|max:255',
            'mesa_id' => 'required|exists:mesas,id',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:items_menus,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'cliente.required' => 'El nombre del cliente es obligatorio',
            'cliente.max' => 'El nombre del cliente no puede exceder los 255 caracteres',
            'mesa_id.required' => 'Debe seleccionar una mesa',
            'mesa_id.exists' => 'La mesa seleccionada no existe',
            'productos.required' => 'Debe agregar al menos un producto',
            'productos.array' => 'El formato de los productos es inválido',
            'productos.min' => 'Debe agregar al menos un producto',
            'productos.*.producto_id.required' => 'El ID del producto es obligatorio',
            'productos.*.producto_id.exists' => 'Uno de los productos seleccionados no existe',
            'productos.*.cantidad.required' => 'La cantidad es obligatoria para cada producto',
            'productos.*.cantidad.integer' => 'La cantidad debe ser un número entero',
            'productos.*.cantidad.min' => 'La cantidad debe ser al menos 1',
        ];
    }
}

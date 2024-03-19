<?php

namespace App\Imports;

use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $usuario = Auth::user();

        // Realiza la validación de los datos de la fila antes de intentar crear un modelo
        $validator = validator($row, [
            'codigo' => 'required|unique:productos,codigoProducto,null,id,id_establecimiento,' . $usuario->establecimiento_id,
            'estado' => 'required',
            'precio' => 'required|numeric',
            'nombre' => 'required',
            'descripcion' => 'required',
            'stock' => 'required|numeric',
        ]);

        // Si la validación falla, devuelve null para omitir la fila
        if ($validator->fails()) {
            return null;
        }

        // Si la validación pasa, crea y devuelve el modelo Producto
        return new Producto([
            'codigoProducto' => $row['codigo'],
            'estado' => $row['estado'],
            'precioProducto' => $row['precio'],
            'precioOriginal' => $row['precio'],
            'nombreProducto' => $row['nombre'],
            'descripcionProducto' => $row['descripcion'],
            'numeroStock' => $row['stock'],
            'id_establecimiento' => $usuario->establecimiento_id,
            'visible' => 1
        ]);
    }
}


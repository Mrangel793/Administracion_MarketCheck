<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


public function model(array $row)
{
    $usuario = Auth::user();

    // Verificar si ya existe un producto con el mismo código de barras en el establecimiento
    $existingProduct = Producto::where('codigoProducto', $row['codigo'])
                                ->where('id_establecimiento', $usuario->establecimiento_id)
                                ->first();

    if($existingProduct){
        // Puedes manejar el error como prefieras, por ejemplo, lanzando una excepción
        throw new \Exception('Ya existe un producto con el mismo código de barras en este establecimiento.');
    }

    return new Producto([
        'codigoProducto' => $row['codigo'],
        'estado' => $row['estado'],
        'precioProducto' => $row['precio'],
        'precioOriginal' => $row['precio'],
        'nombreProducto' => $row['nombre'],
        'descripcionProducto' => $row['descripcion'],
        'numeroStock' => $row['stock'],
        'id_establecimiento' => $usuario->establecimiento_id,
    ]);
}

}

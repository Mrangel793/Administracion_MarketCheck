<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Producto([
            'codigoProducto' => $row['codigo'],
            'estado' => $row['estado'],
            'precioProducto' => $row['precio'],
            'nombreProducto' => $row['nombre'],
            'descripcionProducto' => $row['descripcion'],
            'numeroStock' => $row['stock'],
        ]);
    }
}

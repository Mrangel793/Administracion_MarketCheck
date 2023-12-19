<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprasProductos extends Model
{
    use HasFactory;
    protected $fillable = [
        'producto_id',
        'compra_id',
        'nombre',
        'cantidad',
        'precio',
        'total'
    ];
    public $timestamps = false;

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'ComprasProductos', 'compra_id', 'producto_id');
    }

    public function compra()
    {
        return $this->belongsToMany(Compra::class, 'ComprasProductos', 'producto_id','compra_id' );
    }
}

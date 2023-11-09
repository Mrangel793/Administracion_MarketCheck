<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Categoria;
use App\Models\Establecimiento;
use App\Models\SubCategoria;
use App\Models\Oferta;

class Producto extends Model
{


    public function categoria(){
        return $this->belongsTo(Categoria::class,'id_categoria');
    }

    public function establecimiento(){
        return $this->belongsTo(Establecimiento::class,'id_establecimiento');
    }
    public function subcategoria(){
        return $this->belongsTo(SubCategoria::class,'id_subcategoria');
    }

    public function ofertas()
    {
        return $this->belongsToMany(Oferta::class, 'oferta_productos', 'id_producto', 'id_oferta')
            ->withPivot('precio_oferta');
    }



    use HasFactory;
    protected $fillable = ['codigoProducto', 'estado', 'precioProducto','precioOriginal' ,'nombreProducto', 'descripcionProducto',
    'numeroStock','id_categoria', 'id_establecimiento'];
    protected $attributes = [
        'precioProducto' => 0, // Puedes definir un valor por defecto apropiado
    ];
    public $timestamps = false;
}  





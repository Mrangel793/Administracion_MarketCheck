<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Establecimiento;
use App\Models\SubCategoria;

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

    use HasFactory;
    protected $fillable = ['codigoProducto', 'estado', 'precioProducto', 'nombreProducto', 'descripcionProducto',
    'numeroStock','id_categoria', 'id_establecimiento'];
    public $timestamps = false;
}  

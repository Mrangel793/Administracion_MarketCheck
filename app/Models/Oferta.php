<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Establecimiento;
use App\Models\Oferta_Producto;


class Oferta extends Model
{
    use HasFactory;
    protected $fillable = [
        'estado', 
        'fecha_inicio', 
        'fecha_fin',
        'nombre',
        'descripcion',
        'imagen',
        'numero_stock', 
        'establecimiento_id'];
    public $timestamps = false;
    


    public function establecimiento(){
        return $this->belongsTo(Establecimiento::class,'id_establecimiento');
    }

    public function productos()
{
    return $this->belongsToMany(Producto::class, 'oferta_productos', 'id_oferta', 'id_producto')
    ->withPivot('precio_oferta');
}


    
}

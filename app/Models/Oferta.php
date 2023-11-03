<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Establecimiento;

class Oferta extends Model
{
    use HasFactory;
    protected $fillable = ['estado', 'fecha_inicio', 'fecha_fin','nombre',
    'descripcion','imagen','numero_stock','id_categoria', 'establecimiento_id'];
    public $timestamps = false;
    
    public function categoria(){
        return $this->belongsTo(Categoria::class,'id_categoria');
    }

    public function establecimiento(){
        return $this->belongsTo(Establecimiento::class,'establecimiento_id');
    }
    
}

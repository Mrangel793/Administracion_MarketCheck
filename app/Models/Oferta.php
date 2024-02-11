<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Producto;
use App\Models\Establecimiento;

use App\Models\Oferta_Producto;


class Oferta extends Model{
    use HasFactory;

    protected static function boot(){
        parent::boot();
        // Evento que se ejecuta antes de eliminar un Establecimiento
        static::deleting(function ($offer) {
            $offer->productos()->delete();
            //$offer->images()->delete();
        });
    }
    protected $fillable = [
        'estado', 
        'fecha_inicio', 
        'fecha_fin',
        'nombre',
        'descripcion',
        'imagen',
        'establecimiento_id'
    ];
    public $timestamps = false;
    

    public function establecimiento(){
        return $this->belongsTo(Establecimiento::class,'establecimiento_id');
    }

    public function productos(){
        return $this->belongsToMany(Producto::class, 'oferta_productos', 'id_oferta', 'id_producto')
        ->withPivot('precio_oferta', 'porcentaje');
    }

   /* public function images(){
        return $this->hasMany(Image::class,'oferta_id');
    }*/
        
}

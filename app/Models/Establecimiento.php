<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\User;
use App\Models\Image;
use App\Models\Compra;
use App\Models\Producto;



class Establecimiento extends Model
{ 
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        // Evento que se ejecuta antes de eliminar un Establecimiento
        static::deleting(function ($establecimiento) {
            $establecimiento->users()->delete();
            $establecimiento->oferta()->delete();
            $establecimiento->producto()->delete();
            $establecimiento->compra()->delete();
            //$establecimiento->image()->delete();
        });
    }

    public function users(){
        return $this->hasMany(User::class,'establecimiento_id');
    }

    public function oferta(){
        return $this->hasMany(Oferta::class,'establecimiento_id'); 
    }

    public function producto(){
        return $this->hasMany(Producto::class,'id_establecimiento');
    }
    
    public function compra(){
        return $this->hasMany(Compra::class,'establecimiento_id');
    }

    public function image(){
        return $this->hasMany(Image::class,'establecimiento_id');
    }

    protected $fillable =
    [
    "Nit", 
    "Estado", 
    "NombreEstablecimiento", 
    "DireccionEstablecimiento",
    "CorreoEstablecimiento",
    "Lema", 
    "ColorInterfaz", 
    "Imagen", 
    "Logo"
    ];

    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\User;
use App\Models\Compra;
use App\Models\Oferta;
use App\Models\Producto;
//use App\Models\Image;


class Establecimiento extends Model{ 
    use HasFactory;

    protected static function boot(){
        parent::boot();
        // Evento que se ejecuta antes de eliminar un Establecimiento
        static::deleting(function ($establecimiento) {
            $establecimiento->compra()->delete();
            $establecimiento->oferta()->delete();
            $establecimiento->producto()->delete();
            $establecimiento->users()->delete();
            //$establecimiento->images()->delete();
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

    /*public function images(){
        return $this->hasMany(Image::class,'establecimiento_id');
    }*/

    protected $fillable =[
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

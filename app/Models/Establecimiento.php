<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\User;
use App\Models\Producto;
use App\Models\Compra;



class Establecimiento extends Model
{ 
    use HasFactory;

    public function users(){
        return $this->hasMany(User::class,'establecimiento_id');
    }

    public function oferta(){
        return $this->hasMany(Oferta::class,'id_establecimiento');
    }

    public function producto(){
        return $this->hasMany(Producto::class,'id_establecimiento');
    }
    
    public function compra(){
        return $this->hasMany(Compra::class,'establecimiento_id');
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
    "Logo"];

    public $timestamps = false;
}

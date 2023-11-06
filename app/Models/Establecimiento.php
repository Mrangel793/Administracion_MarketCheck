<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Producto;



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

    protected $fillable = ["Nit", "Estado", "NombreEstablecimiento", "DireccionEstablecimiento","CorreoEstablecimiento",
    "Lema", "ColorInterfaz", "Imagen"];
    public $timestamps = false;
}

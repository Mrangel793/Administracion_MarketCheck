<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategoria;
use App\Models\Oferta;
use App\Models\Producto;

class Categoria extends Model
{ 



    use HasFactory;
    protected $fillable = ['nombre','imagen'];
    public $timestamps = false;
    
    public function subcategorias(){
        return $this->hasMany(SubCategoria::class,'categoria_id');
    }
    

    public function producto(){
        return $this->hasMany(Producto::class,'id_categoria');
    }
}

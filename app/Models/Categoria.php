<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Producto;
use App\Models\SubCategoria;

use App\Models\Oferta;//->???

class Categoria extends Model{ 
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen'
    ];
    public $timestamps = false;
    
    public function subcategorias(){
        return $this->hasMany(SubCategoria::class,'categoria_id');
    }

    public function producto(){
        return $this->hasMany(Producto::class,'id_categoria');
    }
}

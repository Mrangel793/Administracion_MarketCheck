<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Producto;
use App\Models\SubCategoria;


class Categoria extends Model{ 
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen'
    ];
    public $timestamps = false;
    
    

    public function producto(){
        return $this->hasMany(Producto::class,'id_categoria');
    }
}

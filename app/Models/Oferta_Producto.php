<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Oferta;

class Oferta_Producto extends Model
{
    public function oferta(){
        return $this->belongsTo(Oferta::class,'id_oferta');
    }

    public function producto(){
        return $this->belongsTo(Producto::class,'id_producto');
    }

    use HasFactory;
}

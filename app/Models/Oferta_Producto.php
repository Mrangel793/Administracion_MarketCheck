<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Oferta;

class Oferta_Producto extends Model{
    use HasFactory;

    protected $table = 'oferta_productos';

    protected $fillable = [
        'id_producto', 
        'id_oferta', 
        'porcentaje', 
        'precio_oferta'
    ];

    public $timestamps = false;

    protected $primaryKey = null;
    public $incrementing = false;
    
}

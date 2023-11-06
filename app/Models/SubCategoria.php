<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Producto;

class SubCategoria extends Model
{
    use HasFactory;
    protected $fillable = ['nombre','imagen','categoria_id'];
    public $timestamps = false;
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }
    public function producto(){
        return $this->hasMany(Producto::class);
    }
}

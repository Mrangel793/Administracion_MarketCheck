<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Establecimiento;
use App\Models\User;
use App\Models\Producto;
use App\Models\CompraProductos;



class Compra extends Model
{
    use HasFactory;
    protected $fillable = ['hora','fecha','total','estado','establecimiento_id','user_id'];
    public $timestamps = false;

    public function establecimiento(){
        return $this->belongsTo(Establecimiento::class,'establecimiento_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function productos(){
        return $this->belongsToMany(Producto::class,'compras_productos', 'compra_id', 'producto_id')
            ->withPivot('id','cantidad', 'precio');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\User;
use App\Models\Producto;
use App\Models\Establecimiento;

use App\Models\CompraProductos;


class Compra extends Model{
    use HasFactory;

    protected $fillable = [
        'hora',
        'fecha',
        'total',
        'estado',
        'pin',
        'establecimiento_id',
        'user_id',
        'seller_id'
    ];

    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function seller(){
        return $this->belongsTo(User::class,'seller_id');
    }

    public function establecimiento(){
        return $this->belongsTo(Establecimiento::class,'establecimiento_id');
    }
    
    public function productos(){
        return $this->belongsToMany(Producto::class,'compras_productos', 'compra_id', 'producto_id')
            ->withPivot('id','cantidad', 'precio');
    }

}

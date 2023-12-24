<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Oferta;
use App\Models\Establecimiento;


class Image extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'imagePath',
        'created',
        'establecimiento_id',
        'oferta_id'
         
    ];

    public $timestamps = false;

    public function establecimiento(){
        return $this->belongsTo(Establecimiento::class,'establecimiento_id'); 
    }

    public function oferta(){
        return $this->belongsTo(Oferta::class,'oferta_id'); 
    }

    
    
}

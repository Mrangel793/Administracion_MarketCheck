<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
class Lista extends Model
{
    use HasFactory;

    protected $table = 'listas';

    protected $fillable = [
        'listName', 
        'user_id', 
        'productos'
    ];

    public $timestamps = false;


    public function usuario(){
        return $this->belongsTo(User::class,'user_id'); 
    }
}

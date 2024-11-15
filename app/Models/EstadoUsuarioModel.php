<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoUsuarioModel extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'estadousuario';
    protected $fillable = [
        'id',
        'nombre'
    ];
    
    use HasFactory;
}

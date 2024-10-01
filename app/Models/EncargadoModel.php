<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncargadoModel extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'encargado';
    protected $fillable = [
        'nombre',
        'apellido_p',
        'apellido_m',
        'id_laboratorio',
    ];
    
    public $timestamps = false;
    use HasFactory;
}

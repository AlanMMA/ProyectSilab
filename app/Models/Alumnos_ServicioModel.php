<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnos_ServicioModel extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'alumnos_servicio';
    protected $fillable = [
        'id',
        'no_control',
        'nombre',
        'apellido_pS',
        'apellido_mS'
    ];

    public $timestamps = false;
    use HasFactory;
}

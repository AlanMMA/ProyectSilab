<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratorioModel extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'laboratorio';
    protected $fillable = [
        'id',
        'nombre',
    ];

    public $timestamps = false;

    use HasFactory;
}

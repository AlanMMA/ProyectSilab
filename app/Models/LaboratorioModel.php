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
        'num_max_encargado',
    ];

    public function encargados()
    {
        return $this->hasMany(EncargadoModel::class, 'id_laboratorio', 'id');
    }

    public $timestamps = false;

    use HasFactory;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitanteModel extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'solicitante';
    protected $fillable = [
        'id',
        'nombre',
        'apellido_p',
        'apellido_m',
        'id_area',
        'tipo',
        'numero_control'
    ];

    public $timestamps = false;

    public function area():BelongsTo{
        return $this->BelongsTo(AreaModel::class, 'id_area');
    }
    use HasFactory;
}
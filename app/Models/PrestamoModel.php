<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestamoModel extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'prestamo';
    protected $fillable = [
        'id',
        'fecha',
        'id_encargado',
        'id_solicitante'
    ];

    public $timestamps = false;
    
    public function encargadoP(): BelongsTo{
        return $this->belongsTo(EncargadoModel::class, 'id_encargado');
    }

    public function solicitanteP(): BelongsTo{
        return $this->belongsTo(SolicitanteModel::class, 'id_soliciante');
    }

    use HasFactory;
}

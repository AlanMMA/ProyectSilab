<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePrestamoModel extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'detalle_prestamo';
    protected $fillable = [
        'id',
        'id_prestamo',
        'fecha_prestamo',
        'fecha_devolucion',
        'fecha_entrega',
        'id_material',
        'cantidad',
        'EstadoPrestamo',
        'observacion',
    ];

    public $timestamps = false;

    public function prestamo(): BelongsTo
    {
        return $this->belongsTo(PrestamoModel::class, 'id_prestamo');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(MaterialModel::class, 'id_material');
    }

    public function materialDP(): BelongsTo
    {
        return $this->belongsTo(MaterialModel::class, 'id_material');
    }

    use HasFactory;
}

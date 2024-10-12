<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncargadoModel extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'encargado';
    protected $fillable = [
        'id',
        'nombre',
        'apellido_p',
        'apellido_m',
        'id_laboratorio',
    ];

    public function laboratorio(): BelongsTo
    {
        return $this->BelongsTo(LaboratorioModel::class, 'id_laboratorio');
    }

    public $timestamps = false;

    use HasFactory;
}

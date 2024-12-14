<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class localizacion extends Model
{
    use HasFactory;
    protected $primarykey = 'id';
    protected $table = 'localizacion';
    protected $fillable = [
        'id',
        'nombre',
        'id_laboratorio'
    ];

    
    public function laboratorio(): BelongsTo
    {
        return $this->belongsTo(LaboratorioModel::class, 'id_laboratorio');
    }
    public $timestamps = false;
}

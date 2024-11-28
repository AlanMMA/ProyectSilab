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
        'id_encargado'
    ];

    
    public function encargado(): BelongsTo
    {
        return $this->belongsTo(EncargadoModel::class, 'id_encargado');
    }
    public $timestamps = false;
}

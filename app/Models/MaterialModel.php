<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialModel extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'material';
    protected $fillable = [
        'id',
        'nombre',
        'id_marca',
        'modelo',
        'id_categoria',
        'stock',
        'descripcion',
        'id_localizacion',
        'id_laboratorio',
    ];

    public function marca(): BelongsTo
    {
        return $this->BelongsTo(MarcaModel::class, 'id_marca');
    }

    public function categoria(): BelongsTo
    {
        return $this->BelongsTo(CategoriaModel::class, 'id_categoria');
    }

    // public function encargado(): BelongsTo
    // {
    //     return $this->BelongsTo(EncargadoModel::class, 'id_encargado');
    // }

    public function laboratorio(): BelongsTo 
    {
        return $this->belongsTo(LaboratorioModel::class, 'id_laboratorio');
    }

    public function localizacion(): BelongsTo{
        return $this->belongsTo(localizacion::class, 'id_localizacion');
    }

    public $timestamps = false;

    use HasFactory;
}

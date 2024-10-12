<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaModel extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'marca';
    protected $fillable = [
        'id',
        'nombre',
    ];

    public $timestamps = false;

    use HasFactory;
}

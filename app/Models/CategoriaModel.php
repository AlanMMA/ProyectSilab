<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaModel extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'categoria';
    protected $fillable = [
        'id',
        'nombre'
    ];

    public $timestamps = false;
    use HasFactory;

}

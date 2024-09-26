<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolModel extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'roles';
    protected $fillable = [
        'id',
        'nombre'
    ];

    public $timestamps = false;
    use HasFactory;
}

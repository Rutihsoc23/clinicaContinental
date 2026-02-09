<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoCita extends Model
{
    protected $table = 'estados_citas';

    protected $fillable = ['nombre_estado'];
}
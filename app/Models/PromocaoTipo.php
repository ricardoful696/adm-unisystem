<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocaoTipo extends Model
{
    use HasFactory;

    protected $table = 'promocao_tipo';
    protected $primaryKey = 'promocao_tipo_id';

    protected $fillable = [
        'nome'
    ];

    public $timestamps = false;

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDesconto extends Model
{
    use HasFactory;

    protected $table = 'tipo_desconto';
    protected $primaryKey = 'tipo_desconto_id';
    protected $fillable = [
        'descricao'
    ];

    public $timestamps = false; 

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $table = 'cupom';
    protected $primaryKey = 'cupom_id';
    protected $fillable = [
        'chave',
        'ativo',
        'campanha_id',
        'qtd_uso',
    ];

    public $timestamps = false; 

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function campanha()
    {
        return $this->belongsTo(Campanha::class, 'campanha_id');
    }

}

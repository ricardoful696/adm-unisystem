<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoPrecoEspecifico extends Model
{
    use HasFactory;
    
    protected $table = 'produto_preco_especifico';
    protected $primaryKey = 'produto_preco_especifico_id';

    protected $fillable = [
        'produto_id',
        'data',
        'status',
        'valor'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}

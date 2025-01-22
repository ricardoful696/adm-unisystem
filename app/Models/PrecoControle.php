<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecoControle extends Model
{
    use HasFactory;

    protected $table = 'preco_controle';
    protected $primaryKey = 'preco_controle_id';

    protected $fillable = [
        'produto_preco_id',
        'empresa_id',
        'data_inicial',
        'data_final'
    ];

    public $timestamps = false;

    public function produtoPreco()
    {
        return $this->belongsTo(ProdutoPreco::class, 'produto_preco_id');
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}

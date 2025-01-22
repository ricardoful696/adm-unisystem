<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produto';
    protected $primaryKey = 'produto_id';

    protected $fillable = [
        'categoria_produto_id',
        'empresa_id',
        'titulo',
        'subtitulo',
        'descricao',
        'url_capa',
        'venda_individual',
        'venda_combo',
        'venda_qtd_min',
        'venda_qtd_max',
        'venda_qtd_max_diaria',
        'qtd_entrada_saida',
        'promocao',
        'ativo'
    ];

    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
 
     public function produtoPreco()
    {
        return $this->hasMany(ProdutoPreco::class, 'produto_id');
    }

    public function produtoPrecoDia()
    {
        return $this->hasMany(ProdutoPrecoDia::class, 'produto_id');
    }

    public function produtoPrecoEspecifico()
    {
        return $this->hasMany(ProdutoPrecoEspecifico::class, 'produto_id');
    }

    public function categoriaProduto()
    {
        return $this->belongsTo(CategoriaProduto::class, 'categoria_produto_id');
    }

    public function loteProduto()
    {
        return $this->hasMany(LoteProduto::class, 'produto_id');
    }
}

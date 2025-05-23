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
        'venda_qtd_min',
        'venda_qtd_max',
        'venda_qtd_max_diaria',
        'promocao',
        'ativo',
        'principal',
        'qtd_entrada_saida',
        'termos_condicoes',
        'combo_id',
        'bilhete',
        'produtos_fixos_combo',
        'promocao_id'
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

    public function promocaoRelacionada()
    {
        return $this->belongsTo(Promocao::class, 'promocao_id');
    }

    public function combo()
    {
        return $this->hasMany(ComboProduto::class, 'combo_id', 'combo_id')
                    ->with('produto'); // Carrega o produto associado
    }
}

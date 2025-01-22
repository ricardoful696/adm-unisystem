<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoPreco extends Model
{
    use HasFactory;
    
    protected $table = 'produto_preco';
    protected $primaryKey = 'produto_preco_id';

    protected $fillable = [
        'produto_id',
        'valor',
        'valor_promocional'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
    public function precoControle()
    {
        return $this->hasMany(PrecoControle::class, 'produto_preco_id');
    }
}

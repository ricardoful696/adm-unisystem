<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaProduto extends Model
{
    use HasFactory;
    
    protected $table = 'venda_produto';
    protected $primaryKey = 'venda_produto_id';

    protected $fillable = [
        'venda_id',
        'produto_id',
        'quantidade',
        'preco',
        'data_destino'
    ];

    public $timestamps = false;

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}

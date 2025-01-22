<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProduto extends Model
{
    use HasFactory;
    protected $table = 'tipo_produto';
    protected $primaryKey = 'tipo_produto_id';
    protected $fillable = [
        'descricao'
    ];

    public $timestamps = false;

    public function categoriaProduto()
    {
        return $this->belongsTo(CategoriaProduto::class, 'tipo_produto_id');
    }
}

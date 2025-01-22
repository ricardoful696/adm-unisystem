<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProduto extends Model
{
    use HasFactory;
    
    protected $table = 'categoria_produto';
    protected $primaryKey = 'categoria_produto_id';
    protected $fillable = [
        'empresa_id',
        'tipo_produto_id'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->hasMany(Produto::class, 'categoria_produto_id');
    }

    public function tipoProduto()
    {
        return $this->belongsTo(TipoProduto::class, 'tipo_produto_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
 
}

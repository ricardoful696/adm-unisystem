<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboProduto extends Model
{
    use HasFactory;

    protected $table = 'combo_produto';
    protected $primaryKey = 'combo_produto_id';

    protected $fillable = [
        'produto_id',
        'combo_id'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function combo()
    {
        return $this->belongsTo(Produto::class, 'combo_id', 'combo_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoPrecoDia extends Model
{
    use HasFactory;
    
    protected $table = 'produto_preco_dia';
    protected $primaryKey = 'produto_preco_dia_id';

    protected $fillable = [
        'produto_id',
        'valor',
        'dia_semana'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}

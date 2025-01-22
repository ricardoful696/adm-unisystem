<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteProduto extends Model
{
    use HasFactory;

    protected $table = 'lote_produto';
    protected $primaryKey = 'lote_produto_id';
    protected $fillable = [
        'produto_id',
        'lote_id'
    ];

    public $timestamps = false; 

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
    
}

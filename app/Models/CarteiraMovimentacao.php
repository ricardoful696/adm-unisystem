<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteiraMovimentacao extends Model
{
    use HasFactory;

    protected $table = 'carteira_movimentacao';
    protected $primaryKey = 'carteira_movimentacao_id';
    protected $fillable = [
        'carteira_id',
        'descricao',
        'valor',
        'data',
        'hora'
    ];

    public $timestamps = false; 

    public function carteira()
    {
        return $this->belongsTo(Carteira::class, 'carteira_id');
    }
}

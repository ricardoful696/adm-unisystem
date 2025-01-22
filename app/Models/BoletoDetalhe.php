<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoletoDetalhe extends Model
{
    use HasFactory;

    protected $table = 'boleto_detalhe';
    protected $primaryKey = 'boleto_detalhe_id';
    protected $fillable = [
        'venda_id',
        'metodo_pagamento_id',
        'codigo_barras',
        'vencimento'
    ];

    public $timestamps = false; 

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function meotodoPagamento()
    {
        return $this->belongsTo(MetodoPagamento::class, 'metodo_pagamento_id');
    }
}

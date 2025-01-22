<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfiPayBoletoResponse extends Model
{
    use HasFactory;
    
    protected $table = 'efipay_boleto_response';
    protected $primaryKey = 'response_id';

    protected $fillable = [
        'venda_id',
        'codigo',
        'valor',
        'empresa_pagamento_id',
        'charge_id',
        'status'
    ];

    public $timestamps = false;

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }
    public function empresaPagamento()
    {
        return $this->belongsTo(EmpresaPagamento::class, 'empresa_pagamento_id');
    }
}

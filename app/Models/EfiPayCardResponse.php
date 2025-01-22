<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfiPayCardResponse extends Model
{
    use HasFactory;
    
    protected $table = 'efipay_card_response';
    protected $primaryKey = 'response_id';

    protected $fillable = [
        'venda_id',
        'charge_id',
        'installment_value',
        'installments',
        'payment_method',
        'status',
        'total',
        'empresa_pagamento_id'
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

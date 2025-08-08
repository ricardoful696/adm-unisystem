<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagarmeParametro extends Model
{
    use HasFactory;

    protected $table = 'pagarme_parametro';
    protected $primaryKey = 'pagarme_parametro_id';
    protected $fillable = [
        'empresa_pagamento_id',
        'conta_pagarme_id',
        'api_key',
        'empresa_id',
        'chave_desenvolvimento',
        'chave_producao',
        'producao',
        'homologacao'
    ];

    public $timestamps = false;

    public function empresaPagamento()
    {
        return $this->belongsTo(EmpresaPagamento::class, 'empresa_pagamento_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}

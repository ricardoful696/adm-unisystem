<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametroEmpresa extends Model
{
    protected $table = 'parametro_empresa';
    protected $primaryKey = 'parametro_empresa_id';
    protected $fillable = [
        'empresa_id',
        'empresa_pagamento_id',
        'politica_privacidade',
        'aceita_pix',
        'aceita_cartao',
        'aceita_boleto',
        'ingresso_impresso',
        'termos_voucher',
        'cartao_mastercard',
        'cartao_cielo',
        'cartao_visa',
        'cartao_amex',
        'cartao_elo',
        'parcelas_cartao_max',
        'cupom_desconto',
        'chave_pix',
        'valor_max_diario_venda',
        'valor_max_diario_venda_ativo',
        'valor_max_diario_compra_visitante',
        'valor_max_diario_compra_visitante_ativo',
        'validacao_email'
    ];

    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
    public function empresaPagamento()
    {
        return $this->belongsTo(EmpresaPagamento::class, 'empresa_pagamento_id');
    }
}

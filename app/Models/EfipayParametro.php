<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfipayParametro extends Model
{
    use HasFactory;

    protected $table = 'efipay_parametro';
    protected $primaryKey = 'efipay_parametro_id';
    protected $fillable = [
        'empresa_pagamento_id',
        'client_id_homologacao',
        'cliente_secret_homologacao',
        'client_id_producao',
        'client_secret_producao',
        'certificado_homologacao',
        'certificado_producao',
        'empresa_id',
        'identificador_conta',
        'cartao_rota',
        'pix_rota',
        'boleto_rota'
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

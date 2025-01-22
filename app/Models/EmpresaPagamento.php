<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaPagamento extends Model
{
    use HasFactory;

    protected $table = 'empresa_pagamento';
    protected $primaryKey = 'empresa_pagamento_id';
    protected $fillable = [
        'descricao'
    ];

    public $timestamps = false;

    public function venda()
    {
        return $this->hasMany(Venda::class, 'empresa_pagamento_id', 'empresa_pagamento_id');
    }
    public function efipayParametro()
    {
        return $this->hasMany(EfipayParametro::class, 'empresa_pagamento_id', 'empresa_pagamento_id');
    }
    public function parametroEmpresa()
    {
        return $this->hasMany(ParametroEmpresa::class, 'empresa_pagamento_id', 'empresa_pagamento_id');
    }
    
}

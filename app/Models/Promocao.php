<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocao extends Model
{
    use HasFactory;

    protected $table = 'promocao';
    protected $primaryKey = 'promocao_id';

    protected $fillable = [
        'produto_id',
        'nome',
        'descricao',
        'promocao_tipo_id',
        'ativo',
        'data_inicio',
        'data_final',
        'maximo_disponivel'
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
    public function promocaoTipo()
    {
        return $this->belongsTo(PromocaoTipo::class, 'promocao_tipo_id');
    }

    public function promocaoCompreGanhe()
    {
        return $this->hasMany(PromocaoCompreEGanhe::class, 'promocao_id', 'promocao_id');
    }

    public function promocaoNivel()
    {
        return $this->hasMany(PromocaoNivel::class, 'promocao_id', 'promocao_id');
    }

    public function promocaoCompraAntecipada()
    {
        return $this->hasMany(PromocaoCompraAntecipada::class, 'promocao_id', 'promocao_id');
    }

    public function promocaoDescontoFixo()
    {
        return $this->hasMany(PromocaoDescontoFixo::class, 'promocao_id', 'promocao_id');
    }

    public function promocaoDataEspecifica()
    {
        return $this->hasMany(PromocaoDataEspecifica::class, 'promocao_id', 'promocao_id');
    }

}

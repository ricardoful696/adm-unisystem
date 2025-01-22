<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresa';
    protected $primaryKey = 'empresa_id';
    protected $fillable = [
        'cnpj',
        'nome',
        'nome_fantasia',
        'telefone1',
        'telefone2',
        'email',
        'cep',
        'estado',
        'cidade',
        'endereco',
        'endereco_numero',
        'endereco_complemento',
        'url_capa',
        'url_site',
        'url_facebook',
        'url_instagram',
        'url_googleplus',
        'url_youtube',
        'url_promocional',
        'url_regras',
        'ativo'
    ];

    public $timestamps = false; 

    public function usuario()
    {
        return $this->hasMany(Usuario::class, 'empresa_id');
    }

    public function parametroEmpresa()
    {
        return $this->hasOne(ParametroEmpresa::class, 'empresa_id');
    }
    public function empresaImg()
    {
        return $this->hasOne(EmpresaImg::class, 'empresa_id');
    }

    public function calendarioParametro()
    {
        return $this->hasOne(CalendarioParametro::class, 'empresa_id');
    }
    public function produto()
    {
        return $this->hasMany(Produto::class, 'empresa_id');
    }

    public function precoControle()
    {
        return $this->hasMany(PrecoControle::class, 'empresa_id');
    }

    public function calendario()
    {
        return $this->hasMany(Calendario::class, 'empresa_id');
    }

    public function efipayParametro()
    {
        return $this->hasOne(EfipayParametro::class, 'empresa_id');
    }


}

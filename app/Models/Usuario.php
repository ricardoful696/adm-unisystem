<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable; 
    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    
    // Para que o Laravel não trate a chave primária como um inteiro
    protected $keyType = 'string';  // Adicione esta linha
    public $incrementing = false;  

    protected $fillable = [
        'empresa_id',
        'tipo_usuario_id',
        'documento',
        'nome',
        'sobrenome',
        'sexo',
        'data_nascimento',
        'telefone',
        'email',
        'cep',
        'estado',
        'cidade',
        'bairro',
	'rua',
        'endereco',
        'endereco_numero',
        'endereco_complemento',
        'ativo',
        'email_confirmado',
        'senha' 
    ];

    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario_id');
    }
    public function venda()
    {
        return $this->hasMany(Venda::class, 'usuario_id');
    }
    public function getAuthPassword()
    {
        return $this->senha; 
    }
}


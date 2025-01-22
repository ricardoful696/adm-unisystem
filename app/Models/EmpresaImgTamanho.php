<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaImgTamanho extends Model
{
    use HasFactory;

    protected $table = 'empresa_img_tamanho';
    protected $primaryKey = 'empresa_img_tamanho_id';
    protected $fillable = [
        'header',
        'footer',
        'logo',
        'login_desktop',
        'login_mobile'
    ];

    public $timestamps = false;
}

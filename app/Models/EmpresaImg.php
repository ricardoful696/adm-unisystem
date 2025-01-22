<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaImg extends Model
{
    use HasFactory;

    protected $table = 'empresa_img';
    protected $primaryKey = 'empresa_img_id';
    protected $fillable = [
        'empresa_id',
        'footer',
        'header',
        'logo',
        'login_desktop',
        'login_mobile'
    ];

    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

}

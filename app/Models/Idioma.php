<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class Idioma extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoIdioma';
    protected $table      = 'idiomas';    
    
    protected $fillable = [
        'codigoUsuario',
        'descricaoIdioma',
        'leituraIdioma',
        'redacaoIdioma',
        'conversacaoIdioma',
        'codigoPessoaAlteracao',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

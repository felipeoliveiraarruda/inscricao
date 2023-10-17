<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pae extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'pae';
    protected $primaryKey = 'codigoPae';

    protected $fillable = [
        'codigoInscricao',
        'partipacaoPae',
        'remuneracaoPae',
        'resultadoPae',
        'classificacaoPae',
        'resultadoPae',
        'observacoesPae',
        'codigoPessoaAlteracao',
    ];

    public function inscricoes()
    {
        return $this->hasMany(\App\Models\Inscricao::class);
    }

    public function desempenho_academico()
    {
        return $this->belongsTo(\App\Models\PAE\DesempenhoAcademico::class);
    }
}
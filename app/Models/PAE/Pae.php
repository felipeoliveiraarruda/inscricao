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
        'codigoCurso',
        'codigoArea',
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

    public function listarPae($codigoPae)
    {
        $pae = Pae::join('analise_curriculo', 'analise_curriculo_arquivos.codigoAnaliseCurriculo', '=', 'analise_curriculo.codigoAnaliseCurriculo')
                  ->join('arquivos', 'analise_curriculo_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                  ->join('pae', 'pae.codigoPae', '=', 'analise_curriculo.codigoPae')
                  ->where('pae.codigoPae', $codigoPae)
                  ->get();
    }

    public function obterPae($codigoPae)
    {
        $pae = Pae::join('inscricoes', 'pae.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                  ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id' )
                  ->where('pae.codigoPae', $codigoPae)
                  ->first();
        return $pae;
    }
}
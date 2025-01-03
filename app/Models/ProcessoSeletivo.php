<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class ProcessoSeletivo extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoProcessoSeletivo';
    protected $table      = 'processo_seletivo';

    protected $fillable = [
        'codigoInscricao',  
        'statusProcessoSeletivo',
        'codigoPessoaAlteracao'
    ];

    public static function obterAprovado($codigoUsuario = '')
    {
        if (empty($codigoUsuario))
        {
            $aprovado = ProcessoSeletivo::join('inscricoes', 'inscricoes.codigoInscricao', '=', 'processo_seletivo.codigoInscricao')
                                        ->where('inscricoes.codigoUsuario', '=', Auth::user()->id)
                                        ->where('processo_seletivo.statusProcessoSeletivo', '=', 'A')
                                        ->max('processo_seletivo.codigoProcessoSeletivo');  
        }
        else
        {
            $aprovado = ProcessoSeletivo::join('inscricoes', 'inscricoes.codigoInscricao', '=', 'processo_seletivo.codigoInscricao')
                                        ->where('inscricoes.codigoUsuario', '=', $codigoUsuario)
                                        ->where('processo_seletivo.statusProcessoSeletivo', '=', 'A')
                                        ->max('processo_seletivo.codigoProcessoSeletivo');  
        }

        return $aprovado;
    }
    
    public static function obterInscricaoAprovado($codigoProcessoSeletivo)
    {
        $edital = ProcessoSeletivo::select('inscricoes.codigoInscricao', 'inscricoes_disciplinas.codigoInscricaoDisciplina')
                                  ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'processo_seletivo.codigoInscricao')
                                  ->leftJoin('inscricoes_disciplinas', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                                  //->where('inscricoes.codigoUsuario', '=', Auth::user()->id)
                                  ->where('processo_seletivo.codigoProcessoSeletivo', '=', $codigoProcessoSeletivo)
                                  ->first();  

        return $edital;
    }
}

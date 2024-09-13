<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class InscricoesResumoEscolar extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoInscricaoResumoEscolar';
    protected $table      = 'inscricoes_resumo_escolar';
    
    protected $fillable = [
        'codigoInscricao',
        'codigoResumoEscolar',
        'codigoHistorico',
        'codigoDiploma',
        'codigoPessoaAlteracao',
    ];

    public static function obterTotal($codigoInscricao)
    {
        $total = InscricoesResumoEscolar::join('inscricoes', 'inscricoes_resumo_escolar.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                        ->join('resumo_escolar', 'inscricoes_resumo_escolar.codigoResumoEscolar', '=', 'resumo_escolar.codigoResumoEscolar')
                                        ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                        ->count();                               
        return $total;
    }
}

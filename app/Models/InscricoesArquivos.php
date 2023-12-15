<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesArquivos extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoInscricaoArquivo';
    protected $table = 'inscricoes_arquivos';

    protected $fillable = [
        'codigoInscricao',
        'codigoArquivo',        
        'codigoPessoaAlteracao'
    ];

    public static function obterTotal($codigoInscricao)
    {
        $total = InscricoesArquivos::join('inscricoes', 'inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                   ->join('arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                                   ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                   ->count();

        return $total;
    }
}

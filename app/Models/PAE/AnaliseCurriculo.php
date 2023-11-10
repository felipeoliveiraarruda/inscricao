<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AnaliseCurriculo extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'analise_curriculo';
    protected $primaryKey = 'codigoAnaliseCurriculo';

    protected $fillable = [
        'codigoPae',
        'codigoArquivo',
        'pontuacaoAnaliseCurriculo',
        'statusAnaliseCurriculo',
        'justificativaAnaliseCurriculo',
        'codigoPessoaAlteracao',
    ];

    public function pae()
    {
        return $this->hasMany(\App\Models\PAE\Pae::class);
    }

    public function arquivo()
    {
        return $this->hasMany(\App\Models\Arquivo::class);
    }

    /*public static function listarAnalise($user_id, $codigoEdital)
    {
        $analise = AnaliseCurriculo::join('pae', 'analise_curriculo.codigoPae', '=', 'pae.codigoPae')
                                   ->join('inscricoes', 'pae.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                   ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                   ->join('tipo_analise', 'tipo_analise.codigoTipoAnalise', '=', 'analise_curriculo.codigoTipoAnalise')
                                   ->where('users.id', $user_id)
                                   ->where('inscricoes.codigoEdital', $codigoEdital)->get();

        return $analise;
    }*/

    public static function obterAnalise($codigoPae, $codigoArquivo)
    {
        $analise = AnaliseCurriculo::join('pae', 'analise_curriculo.codigoPae', '=', 'pae.codigoPae')
                                   ->join('arquivos', 'arquivos.codigoArquivo', '=', 'analise_curriculo.codigoArquivo')
                                   ->where('pae.codigoPae', $codigoPae)
                                   ->where('arquivos.codigoArquivo', $codigoArquivo)
                                   ->first();                                         

        return $analise;
    }

    public static function obterTotalAnalise($codigoPae)
    {
        $analise = AnaliseCurriculo::join('pae', 'analise_curriculo.codigoPae', '=', 'pae.codigoPae')
                                   ->where('pae.codigoPae', $codigoPae)
                                   ->count();                                         

        return $analise;
    }
}

<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class Avaliacao extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'avaliacao_pae';
    protected $primaryKey = 'codigoAvaliacao';

    protected $fillable = [
        'codigoAvaliadorPae',
        'codigoTipoAnalise',
        'pontuacaoAvaliacao',
        'totalAvaliacao',
        'codigoPessoaAlteracao',
    ];

    public function avaliadores_pae()
    {
        return $this->hasMany(\App\Models\Avaliadores::class);
    }

    public function tipo_analise()
    {
        return $this->hasMany(\App\Models\PAE\TipoAnalise::class);
    }

    public function obterAvaliacao($codigoPae, $codigoTipoDocumento)
    {
        $avaliacao = Avaliacao::join('tipo_analise', 'tipo_analise.codigoTipoAnalise', 'avaliacao_pae.codigoTipoAnalise')
                                ->rightJoin('avaliadores_pae', 'avaliacao_pae.codigoAvaliadorPae', '=', 'avaliadores_pae.codigoAvaliadorPae')
                                ->rightJoin('avaliadores', 'avaliadores_pae.codigoAvaliador', '=', 'avaliadores.codigoAvaliador')
                                ->where('avaliadores_pae.codigoPae', $codigoPae)
                                ->where('avaliadores.codigoUsuario', Auth::user()->id)
                              ->where('tipo_analise.codigoTipoDocumento', $codigoTipoDocumento)
                              ->first(); 
        return $avaliacao;   
                     
    }

    public function listarAvaliacao($codigoPae, $codigoTipoDocumento)
    {
        $avaliacao = Avaliacao::join('tipo_analise', 'tipo_analise.codigoTipoAnalise', 'avaliacao_pae.codigoTipoAnalise')
                              ->rightJoin('avaliadores_pae', 'avaliacao_pae.codigoAvaliadorPae', '=', 'avaliadores_pae.codigoAvaliadorPae')
                              ->rightJoin('avaliadores', 'avaliadores_pae.codigoAvaliador', '=', 'avaliadores.codigoAvaliador')
                              ->where('avaliadores_pae.codigoPae', $codigoPae)
                              ->where('avaliadores.codigoUsuario', Auth::user()->id)
                              ->where('tipo_analise.codigoTipoDocumento', $codigoTipoDocumento)
                              ->first(); 
        return $avaliacao;
    }
}

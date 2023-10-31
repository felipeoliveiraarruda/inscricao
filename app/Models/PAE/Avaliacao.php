<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Avaliacao extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'avaliacao_pae';
    protected $primaryKey = 'codigoAvaliacao';

    protected $fillable = [
        'codigoPae',
        'codigoTipoAnalise',
        'pontuacaoAvaliacao',
        'totalAvaliacao',
        'codigoPessoaAlteracao',
    ];

    public function pae()
    {
        return $this->hasMany(\App\Models\PAE\Pae::class);
    }

    public function tipo_analise()
    {
        return $this->hasMany(\App\Models\PAE\TipoAnalise::class);
    }

    public function obterAvaliacao($codigoPae, $codigoTipoDocumento)
    {
        $avaliacao = Avaliacao::join('tipo_analise', 'tipo_analise.codigoTipoAnalise', 'avaliacao_pae.codigoTipoAnalise')
                              ->where('avaliacao_pae.codigoPae', $codigoPae)
                              ->where('tipo_analise.codigoTipoDocumento', $codigoTipoDocumento)
                              ->first(); 
        return $avaliacao;
    }
}
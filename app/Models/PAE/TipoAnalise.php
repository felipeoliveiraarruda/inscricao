<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TipoAnalise extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'tipo_analise';
    protected $primaryKey = 'codigoTipoAnalise';

    protected $fillable = [
        'tipoAnalise',
        'valorTipoAnalise',
        'calculoTipoAnalise',
        'pontuacaoTipoAnalise',
        'maximoTipoAnalise',
        'statusTipoAnalise',
        'codigoPessoaAlteracao',
    ];

    public function obterTipoAnaliseNome($tipoAnalise)
    {
        $tipo = TipoAnalise::where('tipoAnalise', $tipoAnalise)->first();
        return $tipo;
    }

    public function obterTipoAnaliseCodigoDocumento($codigoTipoDocumento)
    {
        $tipo = TipoAnalise::where('codigoTipoDocumento', $codigoTipoDocumento)->first();
        return $tipo;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesExperiencias extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoInscricaoExperiencia';
    protected $table = 'inscricoes_experiencias';

    protected $fillable = [
        'codigoInscricao',
        'codigoExperiencia',        
        'codigoPessoaAlteracao'
    ];

    public static function obterTotal($codigoInscricao, $codigoTipoExperiencia)
    {
        $total = InscricoesExperiencias::join('inscricoes', 'inscricoes_experiencias.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                       ->join('experiencias', 'inscricoes_experiencias.codigoExperiencia', '=', 'experiencias.codigoExperiencia')
                                       ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                       ->where('experiencias.codigoTipoExperiencia', $codigoTipoExperiencia)
                                       ->count();

        return $total;
    }
}

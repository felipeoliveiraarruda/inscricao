<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class InscricoesRecursosFinanceiros extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoInscricoesRecursosFinanceiros';
    protected $table      = 'inscricoes_recursos_financeiros';
    
    protected $fillable = [
        'codigoInscricao',
        'codigoRecursoFinanceiro',
        'codigoPessoaAlteracao',
    ];

    public function obterTotal($codigoInscricao)
    {
        $total = InscricoesRecursosFinanceiros::join('inscricoes', 'inscricoes_recursos_financeiros.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                              ->join('recursos_financeiros', 'inscricoes_recursos_financeiros.codigoRecursoFinanceiro', '=', 'recursos_financeiros.codigoRecursoFinanceiro')
                                              ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                              ->count();                               
        return $total;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;
use Carbon\Carbon;

class Edital extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoEdital';
    protected $table      = 'editais';
    protected $dates      = ['dataInicioEdital', 'dataFinalEdital', 'dataDoeEdital'];
    
    protected $fillable = [
        'codigoCurso',
        'codigoUsuario',
        'codigoNivel',
        'nivelEdital',
        'linkEdital',
        'dataInicioEdital',
        'dataFinalEdital',
        'dataInicioRecurso',
        'dataFinalRecurso',
        'dataExameEdital',
        'dataDoeEdital',
        'codigoPessoaAlteracao',
    ];

    protected $casts = [
        'dataInicioEdital' => 'datetime',
        'dataFinalEdital' => 'datetime',
        'dataDoeEdital' => 'datetime',
        'dataInicioRecurso' => 'datetime',
        'dataFinalRecurso' => 'datetime',
        'dataExameEdital' => 'datetime',
     ];

    public function inscricoes()
    {
        return $this->hasMany(\App\Models\Inscricao::class);
    }

    public function niveis()
    {
        return $this->hasMany(\App\Models\Niveis::class);
    }

    public static function obterNumeroEdital($codigoEdital, $curso = false)
    {
        $edital = Edital::where('codigoEdital', $codigoEdital)->first();

        $ano      = $edital->dataInicioEdital->format('Y');
        $semestre = ($edital->dataInicioEdital->format('m') < 7 ? "01" : "02");

        if ($curso)
        {
            $retorno = array();
            $sigla = Utils::obterSiglaCurso($edital->codigoCurso);
            $retorno['sigla']  = $sigla;
            $retorno['edital'] = "{$semestre}/{$ano}";

            return $retorno;
        }
        else
        {
            return "{$semestre}/{$ano}";
        }
    }

    public static function obterNivelEdital($codigoEdital)
    {
        $nivel = Edital::select('niveis.siglaNivel')
                       ->join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                       ->where('editais.codigoEdital', $codigoEdital)->first();
        return $nivel->siglaNivel;
    }

    public static function obterSemestreAno($codigoEdital, $curso = false)
    {
        $edital = Edital::find($codigoEdital);

        if($edital->codigoNivel == 1 || $edital->codigoNivel == 6)
        {
            if($edital->codigoNivel == 1)
            {
                $edital = Edital::select(\DB::raw('(YEAR(editais.dataInicioEdital)) AS ano, IF(MONTH(editais.dataInicioEdital) >= 1 AND MONTH(editais.dataInicioEdital) < 5, 1, 2) AS semestre'))
                                ->where('codigoEdital', $codigoEdital)->first();  
            }
            else            
            {
                $edital = Edital::select(\DB::raw('(YEAR(editais.dataInicioEdital)) AS ano, IF(MONTH(editais.dataInicioEdital) < 7, 1, 2) AS semestre'))
                                ->where('codigoEdital', $codigoEdital)->first();  
            }
        }
        else
        {
            if($edital->codigoNivel == 3)
            {
                $edital = Edital::select(\DB::raw('(YEAR(editais.dataDoeEdital) + 1) AS ano, IF(MONTH(editais.dataDoeEdital) > 7, 1, 2) AS semestre'))
                                ->where('codigoEdital', $codigoEdital)->first();   
            }
            else            
            {
                if ($codigoEdital >= 5)
                {
                    $edital = Edital::select(\DB::raw('(YEAR(editais.dataInicioEdital)) AS ano, IF(MONTH(editais.dataInicioEdital) < 7, 2, 1) AS semestre'))
                                    ->where('codigoEdital', $codigoEdital)->first();  
                }
                else
                {
                    $edital = Edital::select(\DB::raw('(YEAR(editais.dataInicioEdital) + 1) AS ano, IF(MONTH(editais.dataInicioEdital) > 7, 1, 2) AS semestre'))
                                    ->where('codigoEdital', $codigoEdital)->first();
                }
            }
        }

        if ($curso)
        {
            return "{$edital->semestre}/{$edital->ano}";
        }
        else
        {
            return "{$edital->ano}{$edital->semestre}";
        }
    }

    public static function obterPeriodoRecurso($codigoEdital)
    {
        $edital = Edital::whereBetween(\DB::raw('NOW()'), [\DB::raw('dataInicioRecurso'), \DB::raw('dataFinalRecurso')])->count();
        
        if ($edital > 0)
        {
            return true;
        }
        else
        {
            return false;
        }   
    }

    public static function obterCursoEdital($codigoEdital)
    {
        $edital = Edital::find($codigoEdital);
        
        return $edital->codigoCurso;  
    }

    public static function obterEditalInscricao($codigoInscricao)
    {
        $dados = Edital::select(\DB::raw('editais.*, users.name, users.email'))
                       ->join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                       ->join('users', 'editais.codigoUsuario', '=', 'users.id')
                       ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                       ->where('inscricoes.codigoInscricao', $codigoInscricao)->first();

        return $dados;
    }

    public static function listarEditalDeferimento()
    {
        $disciplina = array();

        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                         ->whereRaw('NOW() BETWEEN editais.dataInicioRecurso AND editais.dataFinalRecurso')
                         ->get();

        foreach($editais as $edital)                         
        {
           $temp = Utils::listarOferecimentoPosDocente($edital->codigoCurso, Auth::user()->codpes, '04/03/2024', '16/06/2024', 'S');

           if (!empty($temp))
           {
                array_push($disciplina, $temp[0]['sgldis']);
           }
        }

        $editais = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')
                         ->join('inscricoes', 'editais.codigoEdital', '=', 'inscricoes.codigoEdital')
                         ->join('inscricoes_disciplinas', 'inscricoes.codigoInscricao', '=', 'inscricoes_disciplinas.codigoInscricao')
                         ->whereIn('inscricoes_disciplinas.codigoDisciplina', $disciplina)
                         ->groupBy('editais.codigoEdital')
                         ->get();

        return $editais;
    }
}

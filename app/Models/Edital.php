<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

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

        if($edital->codigoNivel == 1)
        {
            $edital = Edital::select(\DB::raw('(YEAR(editais.dataInicioEdital)) AS ano, IF(MONTH(editais.dataInicioEdital) < 7, 1, 2) AS semestre'))
                            ->where('codigoEdital', $codigoEdital)->first();  
        }
        else
        {
            $edital = Edital::select(\DB::raw('(YEAR(editais.dataInicioEdital) + 1) AS ano, IF(MONTH(editais.dataInicioEdital) > 7, 1, 2) AS semestre'))
                            ->where('codigoEdital', $codigoEdital)->first();   
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
}

<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PAE\Conceito;

class DesempenhoAcademico extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'desempenho_academico';
    protected $primaryKey = 'codigoDesempenhoAcademico';

    protected $fillable = [
        'codigoPae',
        'codigoConceito',
        'quantidadeDesempenhoAcademico',
        'codigoPessoaAlteracao',
    ];

    public function pae()
    {
        return $this->hasMany(\App\Models\PAE\Pae::class);
    }

    public function conceito()
    {
        return $this->hasMany(\App\Models\PAE\Conceito::class);
    }

    public static function listarDesempenho($user_id, $codigoEdital)
    {
        $desempenho = DesempenhoAcademico::join('pae', 'desempenho_academico.codigoPae', '=', 'pae.codigoPae')
                                         ->join('inscricoes', 'pae.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                         ->join('users', 'inscricoes.codigoUsuario', '=', 'users.id')
                                         ->join('conceito', 'conceito.codigoConceito', '=', 'desempenho_academico.codigoConceito')
                                         ->where('users.id', $user_id)
                                         ->where('inscricoes.codigoEdital', $codigoEdital)->get();

        return $desempenho;
    }

    public static function obterDesempenho($codigoPae, $codigoConceito)
    {
        $desempenho = DesempenhoAcademico::join('pae', 'desempenho_academico.codigoPae', '=', 'pae.codigoPae')
                                         ->join('conceito', 'conceito.codigoConceito', '=', 'desempenho_academico.codigoConceito')
                                         ->where('pae.codigoPae', $codigoPae)
                                         ->where('conceito.codigoConceito', $codigoConceito)
                                         ->first();                                         

        return $desempenho;
    }
}

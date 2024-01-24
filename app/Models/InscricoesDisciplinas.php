<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesDisciplinas extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoInscricaoDisciplina';
    protected $table = 'inscricoes_disciplinas';

    protected $fillable = [
        'codigoInscricao',
        'codigoDisciplina',        
        'codigoPessoaAlteracao'
    ];

    public static function obterTotal($codigoInscricao)
    {
        $total = InscricoesDisciplinas::join('inscricoes', 'inscricoes_disciplinas.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                      ->where('inscricoes.codigoInscricao', $codigoInscricao)   
                                      ->whereNull('inscricoes_disciplinas.deleted_at')                                   
                                      ->count();
        return $total;
    }

    public static function listarDisciplinas($codigoEdital)
    {
        $disciplinas = InscricoesDisciplinas::join('inscricoes', 'inscricoes_disciplinas.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                            ->where('inscricoes.codigoEdital', $codigoEdital)
                                            ->groupBy('inscricoes_disciplinas.codigoDisciplina')
                                            ->orderBy('inscricoes_disciplinas.codigoDisciplina', 'asc')
                                            ->get();
        
        return $disciplinas;
    }
}
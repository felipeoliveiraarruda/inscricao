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
    use HasApiTokens, HasFactory, Notifiable;

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
                                      ->count();
        return $total;
    }
}
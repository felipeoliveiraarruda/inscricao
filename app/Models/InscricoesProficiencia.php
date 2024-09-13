<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesProficiencia extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoInscricaoProficiencia';
    protected $table = 'inscricoes_proficiencia';

    protected $fillable = [
        'codigoInscricao',
        'codigoPessoaOrientador',        
        'codigoPessoaAlteracao'
    ];

    public static function obterTotal($codigoInscricao)
    {
        $total = InscricoesProficiencia::join('inscricoes', 'inscricoes_proficiencia.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                      ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                      ->count();

        return $total;
    }
}

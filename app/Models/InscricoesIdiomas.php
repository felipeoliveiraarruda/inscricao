<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesIdiomas extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoInscricaoIdioma';
    protected $table = 'inscricoes_idiomas';

    protected $fillable = [
        'codigoInscricao',
        'codigoIdioma',        
        'codigoPessoaAlteracao'
    ];

    public static function obterTotal($codigoInscricao)
    {
        $total = InscricoesIdiomas::join('inscricoes', 'inscricoes_idiomas.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                  ->join('idiomas', 'inscricoes_idiomas.codigoIdioma', '=', 'idiomas.codigoIdioma')
                                  ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                  ->count();
        return $total;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesPessoais extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoInscricaoPessoal';
    protected $table = 'inscricoes_pessoais';

    protected $fillable = [
        'codigoInscricao',
        'codigoPessoal',        
        'codigoPessoaAlteracao'
    ];

    public static function obterTotal($codigoInscricao)
    {
        $total = InscricoesPessoais::join('inscricoes', 'inscricoes_pessoais.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                   ->join('pessoais', 'inscricoes_pessoais.codigoPessoal', '=', 'pessoais.codigoPessoal')
                                   ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                   ->count();                               
        return $total;
    }
}

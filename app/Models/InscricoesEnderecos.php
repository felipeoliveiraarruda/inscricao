<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesEnderecos extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoInscricaoEndereco';
    protected $table = 'inscricoes_enderecos';

    protected $fillable = [
        'codigoInscricao',
        'codigoEndereco',        
        'codigoPessoaAlteracao'
    ];

    public function obterTotal($codigoInscricao)
    {
        $total = InscricoesEnderecos::join('inscricoes', 'inscricoes_enderecos.codigoInscricao', '=', 'inscricoes.codigoInscricao')
                                    ->join('enderecos', 'inscricoes_enderecos.codigoEndereco', '=', 'enderecos.codigoEndereco')
                                    ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                    ->count();
        return $total;
    }
}

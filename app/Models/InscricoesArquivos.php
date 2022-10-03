<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesArquivos extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'inscricoes_arquivos';

    protected $fillable = [
        'codigoInscricao',
        'codigoArquivo',        
        'codigoPessoaAlteracao'
    ];
}

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

    protected $table = 'inscricoes_pessoais';

    protected $fillable = [
        'codigoInscricao',
        'codigoPessoal',        
        'codigoPessoaAlteracao'
    ];
}

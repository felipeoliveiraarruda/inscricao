<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Endereco extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoEndereco';
    protected $table      = 'enderecos';
        
    protected $fillable = [
        'codigoUsuario',
        'cepEndereco',
        'logradouroEndereco',
        'numeroEndereco',
        'complementoEndereco',
        'bairroEndereco',
        'localidadeEndereco',
        'ufEndereco',
        'codigoPessoaAlteracao',
    ];
}

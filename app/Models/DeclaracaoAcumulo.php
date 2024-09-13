<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class DeclaracaoAcumulo extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoDeclaracaoAcumulo';
    protected $table      = 'declaracao_acumulo';    
    
    protected $fillable = [
        'codigoInscricao',
        'atividadeRemunerada',
        'outroRendimento',
        'bolsaDeclaratoria',
        'codigoPessoaAlteracao',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class RecursoFinanceiro extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoRecursoFinanceiro';
    protected $table      = 'recursos_financeiros';
    
    protected $fillable = [
        'codigoUsuario',
        'bolsaRecursoFinanceiro',
        'solicitarRecursoFinanceiro',
        'orgaoRecursoFinanceiro',
        'tipoBolsaFinanceiro',
        'inicioRecursoFinanceiro',
        'finalRecursoFinanceiro',
        'codigoPessoaAlteracao',
    ];

    protected $casts = [
        'inicioRecursoFinanceiro' => 'date:d/m/Y',
        'finalRecursoFinanceiro' => 'date:d/m/Y',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;
use Carbon\Carbon;

class Regulamento extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoRegulamento';
    protected $table      = 'regulamentos';
    protected $dates      = ['dataInicioRegulamento', 'dataFinalRegulamento'];
    
    protected $fillable = [
        'codigoCurso',
        'descricaoRegulamento',
        'linkRegulamento',
        'dataInicioRegulamento',
        'dataFinalRegulamento',
        'codigoPessoaAlteracao',
    ];                  
    
    protected $casts = [
        'dataInicioRegulamento' => 'datetime',
        'dataFinalRegulamento' => 'datetime',
    ];
}

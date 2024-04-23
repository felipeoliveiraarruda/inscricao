<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class ResumoEscolar extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoResumoEscolar';
    protected $table      = 'resumo_escolar';
    
    protected $fillable = [
        'codigoUsuario',
        'escolaResumoEscolar',
        'especialidadeResumoEscolar',
        'inicioResumoEscolar',
        'finalResumoEscolar',
        'codigoPessoaAlteracao',
    ];

    protected $casts = [
        'inicioResumoEscolar' => 'date:d/m/Y',
        'finalResumoEscolar' => 'date:d/m/Y',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

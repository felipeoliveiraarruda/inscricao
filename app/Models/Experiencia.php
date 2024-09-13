<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class Experiencia extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoExperiencia';
    protected $table      = 'experiencias';    
    
    protected $fillable = [
        'codigoUsuario',
        'codigoTipoExperiencia',
        'codigoTipoEntidade',
        'entidadeExperiencia',
        'posicaoExperiencia',
        'inicioExperiencia',
        'finalExperiencia',
        'codigoPessoaAlteracao',
    ];

    protected $casts = [
        'inicioExperiencia' => 'date:d/m/Y',
        'finalExperiencia' => 'date:d/m/Y',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function tipo_experiencia()
    {
        return $this->belongsTo(\App\Models\TipoExperiencia::class);
    }

    public function tipo_entidade()
    {
        return $this->belongsTo(\App\Models\TipoEntidade::class);
    }
}

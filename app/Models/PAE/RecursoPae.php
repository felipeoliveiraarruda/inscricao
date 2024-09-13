<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class RecursoPae extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'recurso_pae';
    protected $primaryKey = 'codigoRecurso';

    protected $fillable = [
        'codigoPae',
        'codigoCurso',
        'justificativaRecurso',
        'analiseRecurso',
        'statusRecurso',
        'codigoPessoaAlteracao',
    ];

    public function pae()
    {
        return $this->hasMany(\App\Models\PAE\Pae::class);
    }
}
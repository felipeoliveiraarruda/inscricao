<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TipoEntidade extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoTipoEntidade';
    protected $table      = 'tipo_entidade';


    protected $fillable = [
        'tipoEntidade',
        'codigoPessoaAlteracao'
    ];

    public function experiencias()
    {
        return $this->hasMany(\App\Models\Experiencia::class);
    }

    public static function listarTipoEntidade()
    {
        $entidades = TipoEntidade::whereNotIn('codigoTipoDocumento', [1])->get();

        return $entidades;
    }
}

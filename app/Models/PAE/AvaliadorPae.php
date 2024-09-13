<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AvaliadorPae extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table      = 'avaliadores_pae';
    protected $primaryKey = 'codigoAvaliadorPae';

    protected $fillable = [
        'codigoAvaliador',
        'codigoPae',
        'codigoPessoaAlteracao',
    ];

    public function obterCodigoAvaliadorPae($codigoAvaliador, $codigoPae)
    {
        $codigo = AvaliadorPae::select('codigoAvaliadorPae')
                              ->join('avaliadores', 'avaliadores_pae.codigoAvaliador', '=', 'avaliadores.codigoAvaliador')
                              ->where('avaliadores.codigoUsuario', $codigoAvaliador)
                              ->where('avaliadores_pae.codigoPae', $codigoPae)
                              ->first();
        
        return $codigo->codigoAvaliadorPae;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class DadosPessoais extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoPessoal';
    protected $table      = 'pessoais';    
    
    protected $fillable = [
        'codigoUsuario',
        'dataNascimentoPessoal',
        'sexoPessoal',
        'estadoCivilPessoal',
        'naturalidadePessoal',
        'estadoPessoal',
        'paisPessoal',
        'dependentePessoal',
        'racaPessoal',
        'especialPessoal',
        'tipoEspecialPessoal',
        'codigoPessoaAlteracao',
    ];

    protected $casts = [
        'dataNascimentoPessoal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /*public static function obterDadosPessoais($user_id)
    {
        \DB::enableQueryLog();

        $pessoal = DadosPessoais::select(\DB::raw('pessoais.*, users.*, documentos.*, inscricoes_pessoais.codigoInscricao'))
                                ->join('users', 'users.id', '=', 'pessoais.codigoUsuario')
                                ->leftJoin('documentos', 'users.id', '=', 'documentos.codigoUsuario')
                                ->leftJoin('inscricoes_pessoais', 'inscricoes_pessoais.codigoPessoal', '=', 'pessoais.codigoPessoal')
                                ->where('pessoais.codigoUsuario', $user_id)
                                ->get();

        dd(\DB::getQueryLog());                                

        return $pessoal;                                 
    }*/
}

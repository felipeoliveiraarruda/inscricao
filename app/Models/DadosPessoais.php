<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class DadosPessoais extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoPessoal';
    protected $table      = 'pessoais';    
    
    protected $fillable = [
        'codigoUsuario',
        'dataNascimentoPessoal',
        'sexoPessoal',
        'estadoCivilPessoal',
        'natualidadePessoal',
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
}

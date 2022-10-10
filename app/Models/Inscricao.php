<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Inscricao extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoInscricao';
    protected $table      = 'inscricoes';

    protected $fillable = [
        'codigoEdital',
        'codigoUsuario',    
        'numeroInscricao',    
        'situacaoInscricao',
        'codigoPessoaAlteracao'
    ];

    public function user()
    {
        return $this->belongsTo(App\Models\User::class);
    }

    public function editais()
    {
        return $this->belongsTo(App\Models\Edital::class);
    }
    
    public function gerarNumeroInscricao($codigoEdital)
    {
        $total = Inscricao::where('codigoEdital', $codigoEdital)->count() + 1;
        return $total;
    }

    public function verificarInscricao($codigoEdital, $user_id)
    {
        $total = Inscricao::where('codigoEdital', $codigoEdital)->where('codigoUsuario', $user_id)->count();
        return $total;
    }
}

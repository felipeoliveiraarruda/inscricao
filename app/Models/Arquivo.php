<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Arquivo extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoArquivo';

    protected $fillable = [
        'codigoUsuario',
        'codigoTipoDocumento',
        'linkArquivo',
        'codigoPessoaAlteracao',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function tipo_documentos()
    {
        return $this->belongsTo(\App\Models\TipoDocumento::class);
    }

    public function verificarArquivo($codigoInscricao, $codigoTipoDocumento)
    {
        $arquivos = Arquivo::join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                           ->where('codigoInscricao',       $codigoInscricao)
                           ->whereIn('codigoTipoDocumento', $codigoTipoDocumento)
                           ->count();

        return $arquivos;                  
    }
}

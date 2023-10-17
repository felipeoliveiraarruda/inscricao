<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InscricoesDocumentos extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoInscricaoDocumento';
    protected $table = 'inscricoes_documentos';

    protected $fillable = [
        'codigoInscricao',
        'codigoDocumento',        
        'codigoPessoaAlteracao'
    ];
}

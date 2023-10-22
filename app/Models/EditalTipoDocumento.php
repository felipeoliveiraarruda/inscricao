<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class EditalTipoDocumento extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoEditalTipoDocumento';
    protected $table = 'editais_tipo_documentos';

    protected $fillable = [
        'codigoEdital',
        'codigoTipoDocumento',        
        'codigoPessoaAlteracao'
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Utils;

class Documento extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoDocumento';
    protected $table      = 'documentos';    
    
    protected $fillable = [
        'codigoDocumento',
        'codigoUsuario',
        'tipoDocumento',
        'numeroRG',
        'ufEmissorRG',
        'orgaoEmissorRG',
        'dataEmissaoRG',
        'numeroDocumento',
        'codigoPessoaAlteracao',
    ];

    protected $casts = [
        'dataEmissaoRG' => 'date:d/m/Y',
     ];

     public function user()
     {
         return $this->belongsTo(\App\Models\User::class);
     }
}

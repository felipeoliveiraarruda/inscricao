<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoDocumento';
    protected $table      = 'documentos';    
    
    protected $fillable = [
        'codigoDocumento',
        'codigoUsuario',
        'numeroRG',
        'ufEmissorRG',
        'orgaoEmissorRG',
        'dataEmissaoRG',
        'codigoPessoaAlteracao',
    ];

    protected $casts = [
        'dataEmissaoRG' => 'date',
     ];

     public function user()
     {
         return $this->belongsTo(\App\Models\User::class);
     }
}

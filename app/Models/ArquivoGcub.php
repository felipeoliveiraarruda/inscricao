<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ArquivoGcub extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'arquivos_gcub';
    protected $primaryKey = 'codigoArquivo';

    protected $fillable = [
        'codigoArquivo',
        'codigoGcub',
        'codigoTipoDocumento',
        'linkArquivo',
    ];

    public static function listarArquivos($codigoGcub)
    {
        $arquivos = ArquivoGcub::join('tipo_documentos', 'arquivos_gcub.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                               ->where('arquivos_gcub.codigoGcub', $codigoGcub)                            
                               ->get();
        return $arquivos;
    }
}
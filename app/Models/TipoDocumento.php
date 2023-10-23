<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TipoDocumento extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'codigoTipoDocumento';

    protected $fillable = [
        'tipoDocumento',
        'codigoPessoaAlteracao'
    ];

    public function arquivos()
    {
        return $this->hasMany(\App\Models\Arquivo::class);
    }

    public static function listarTipoDocumentos($codigoEdital)
    {
        $tipos = TipoDocumento::select(\DB::raw('tipo_documentos.*, editais.codigoEdital'))
                              ->join('editais_tipo_documentos', 'tipo_documentos.codigoTipoDocumento', '=', 'editais_tipo_documentos.codigoTipoDocumento')
                              ->join('editais', 'editais_tipo_documentos.codigoEdital', '=', 'editais.codigoEdital')
                              ->where('editais.codigoEdital', '=', $codigoEdital)
                              ->where('tipo_documentos.ativoTipoDocumento', '=', 'S')
                              ->orderBy('tipo_documentos.tipoDocumento', 'asc')
                              ->get();

        return $tipos;                  
    }

    public static function listarTipoDocumentosPessoal()
    {
        $tipos = TipoDocumento::whereIn('codigoTipoDocumento', [1,2,3,4,26])->get();

        return $tipos;                  
    }

    public static function obterCodigoTipoDocumentoNome($tipoDocumento)
    {       
        $tipo = TipoDocumento::select('codigoTipoDocumento')->where('tipoDocumento', '=', $tipoDocumento)->first();
        return $tipo;    
    }
}
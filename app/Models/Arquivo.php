<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Arquivo extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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

    public function listarArquivos($codigoUsuario, $codigoTipoDocumento = 0, $codigoInscricao = 0)
    {
        if ($codigoTipoDocumento == 0)
        {
            $arquivos = Arquivo::join('tipo_documentos', 'tipo_documentos.codigoTipoDocumento', '=', 'arquivos.codigoTipoDocumento')
                               ->where('arquivos.codigoUsuario', $codigoUsuario)
                               ->get();
        }
        else
        {
            $arquivos = Arquivo::select(\DB::raw('arquivos.*, tipo_documentos.*, inscricoes_arquivos.codigoInscricaoArquivo'))
                               ->join('tipo_documentos', 'tipo_documentos.codigoTipoDocumento', '=', 'arquivos.codigoTipoDocumento')
                               ->leftJoin('inscricoes_arquivos', 'inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                               ->where('arquivos.codigoUsuario',  $codigoUsuario)                               
                               ->whereIn('arquivos.codigoTipoDocumento', $codigoTipoDocumento)
                               ->get();
        }

        return $arquivos;
    }

    public static function verificarArquivo($codigoInscricao, $codigoTipoDocumento)
    {
        $arquivos = Arquivo::join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                           ->where('codigoInscricao',       $codigoInscricao)
                           ->whereIn('codigoTipoDocumento', $codigoTipoDocumento)
                           ->count();

        return $arquivos;                  
    }
    
    public static function listarArquivosPae($codigoPae, $codigoTipoDocumento = '', $total = false)    
    {
       if ($total)       
       {            
            $analise = Arquivo::join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                              ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_arquivos.codigoInscricao')
                              ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                              ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                              ->where('pae.codigoPae', $codigoPae)
                              ->where('arquivos.codigoTipoDocumento', $codigoTipoDocumento)
                              ->count();
       }
       else
       {
            if ($codigoTipoDocumento == '')
            {
                $analise = Arquivo::selectRaw('tipo_documentos.codigoTipoDocumento, tipo_documentos.tipoDocumento, COUNT(tipo_documentos.codigoTipoDocumento) AS total')
                                  ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                                  ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_arquivos.codigoInscricao')
                                  ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                                  ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                                  ->groupBy('tipo_documentos.codigoTipoDocumento')
                                  ->where('pae.codigoPae', $codigoPae)
                                  ->get();  
            }
            else
            {
                $analise = Arquivo::join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                                  ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_arquivos.codigoInscricao')
                                  ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                                  ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                                  ->where('pae.codigoPae', $codigoPae)
                                  ->where('arquivos.codigoTipoDocumento', $codigoTipoDocumento)
                                  ->get();  
            }
   
       }
       
        return $analise;
    }

    public static function verificaArquivosPae($codigoPae)    
    {
        $lattes = Arquivo::listarArquivosPae($codigoPae, 9, true);
        $ficha  = Arquivo::listarArquivosPae($codigoPae, 22, true);

        if ($lattes > 0 && $ficha > 0)
        {
            return true;
        }

        return false;
    }
}

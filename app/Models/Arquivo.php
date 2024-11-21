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
        else if ($codigoInscricao != 0)
        {
            $arquivos = Arquivo::select(\DB::raw('arquivos.*, tipo_documentos.*, inscricoes_arquivos.codigoInscricaoArquivo'))
                               ->join('tipo_documentos', 'tipo_documentos.codigoTipoDocumento', '=', 'arquivos.codigoTipoDocumento')
                               ->join('users', 'users.id', '=', 'arquivos.codigoUsuario')
                               ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')
                               ->leftJoin('inscricoes_arquivos', function($join)
                               {
                                   $join->on('inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                   $join->on('arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo');
                               })
                               ->where('inscricoes.codigoInscricao',  $codigoInscricao)                               
                               ->whereIn('arquivos.codigoTipoDocumento', $codigoTipoDocumento)
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
                $analise = Arquivo::selectRaw('arquivos.*, tipo_documentos.codigoTipoDocumento, tipo_documentos.tipoDocumento, COUNT(tipo_documentos.codigoTipoDocumento) AS total')
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

        //if ($lattes > 0 && $ficha > 0)
        if ($ficha > 0)
        {
            return true;
        }

        return false;
    }

    public static function obterArquivosHistorico($codigoInscricao, $todos = false, $codigoArquivo = '')
    {
        if ($todos)
        {
            $arquivos = Arquivo::select(\DB::raw('arquivos.*, tipo_documentos.*, inscricoes.*, inscricoes_arquivos.codigoInscricaoArquivo'))
                               ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                               ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')
                               ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                               
                               ->leftJoin('inscricoes_arquivos', function($join)
                               {
                                   $join->on('inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                   $join->on('inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo');
                               })    
                               ->where('inscricoes.codigoInscricao', $codigoInscricao)
                               ->where('arquivos.codigoTipoDocumento', 5)
                               ->get();
        }
        else
        {
            if ($codigoArquivo == '')
            {
                $arquivos = Arquivo::select(\DB::raw('arquivos.*, tipo_documentos.*, inscricoes.*, inscricoes_arquivos.codigoInscricaoArquivo'))
                                ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                                ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')
                                ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                               
                                ->leftJoin('inscricoes_arquivos', function($join)
                                {
                                    $join->on('inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                    $join->on('inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo');
                                })    
                                ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                ->where('arquivos.codigoTipoDocumento', 5)
                                ->first();
            }
            else
            {
                $arquivos = Arquivo::select(\DB::raw('arquivos.*, tipo_documentos.*, inscricoes.*, inscricoes_arquivos.codigoInscricaoArquivo'))
                                ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                                ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')
                                ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                               
                                ->leftJoin('inscricoes_arquivos', function($join)
                                {
                                    $join->on('inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                    $join->on('inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo');
                                })    
                                ->where('inscricoes.codigoInscricao', $codigoInscricao)
                                ->where('arquivos.codigoTipoDocumento', 5)
                                ->where('arquivos.codigoArquivo', $codigoArquivo)
                                ->first();
            }
        }

        return $arquivos;           
    }

    public static function obterArquivosDiploma($codigoInscricao, $todos = false, $codigoArquivo = '')
    {
        if ($todos)
        {
            $arquivos = Arquivo::select(\DB::raw('arquivos.*, tipo_documentos.*, inscricoes.*, inscricoes_arquivos.codigoInscricaoArquivo'))
                               ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                               ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')
                               ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                               
                               ->leftJoin('inscricoes_arquivos', function($join)
                               {
                                   $join->on('inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                   $join->on('inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo');
                               })    
                               ->where('inscricoes.codigoInscricao', $codigoInscricao)
                               ->whereIn('arquivos.codigoTipoDocumento', array(6, 7))
                               ->get();
        }
        else
        {
            $arquivos = Arquivo::select(\DB::raw('arquivos.*, tipo_documentos.*, inscricoes.*, inscricoes_arquivos.codigoInscricaoArquivo'))
                               ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                               ->leftJoin('users', 'users.id', '=', 'arquivos.codigoUsuario')
                               ->leftJoin('inscricoes', 'users.id', '=', 'inscricoes.codigoUsuario')                               
                               ->leftJoin('inscricoes_arquivos', function($join)
                               {
                                   $join->on('inscricoes_arquivos.codigoInscricao', '=', 'inscricoes.codigoInscricao');
                                   $join->on('inscricoes_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo');
                               })    
                               ->where('inscricoes.codigoInscricao', $codigoInscricao)
                               ->whereIn('arquivos.codigoTipoDocumento', array(6, 7))
                               ->where('arquivos.codigoArquivo', $codigoArquivo)
                               ->first();
        }

        return $arquivos;           
    }

    public static function obterArquivosCurriculo($codigoInscricao)
    {
        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                            ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                            ->where('inscricoes_arquivos.codigoInscricao', $codigoInscricao)
                            ->whereIn('arquivos.codigoTipoDocumento', array(8, 9))
                            ->first();
        return $arquivos;           
    }

    public static function obterArquivosCpf($codigoInscricao)
    {
        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                            ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                            ->where('inscricoes_arquivos.codigoInscricao', $codigoInscricao)
                            ->whereIn('arquivos.codigoTipoDocumento', array(1, 3))
                            ->first();
        return $arquivos;           
    }

    public static function obterArquivosRg($codigoInscricao)
    {
        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                            ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                            ->where('inscricoes_arquivos.codigoInscricao', $codigoInscricao)
                            ->whereIn('arquivos.codigoTipoDocumento', array(2))
                            ->first();
        return $arquivos;           
    }

    public static function obterArquivosRne($codigoInscricao)
    {
        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                            ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                            ->where('inscricoes_arquivos.codigoInscricao', $codigoInscricao)
                            ->whereIn('arquivos.codigoTipoDocumento', array(4))
                            ->first();
        return $arquivos;           
    }

    public static function obterArquivosRequerimento($codigoInscricao)
    {
        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                            ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                            ->where('inscricoes_arquivos.codigoInscricao', $codigoInscricao)
                            ->whereIn('arquivos.codigoTipoDocumento', array(28))
                            ->first();
        return $arquivos;           
    }

    public static function obterArquivosPreProjeto($codigoInscricao)
    {
        $arquivos = Arquivo::join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                            ->join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                            ->where('inscricoes_arquivos.codigoInscricao', $codigoInscricao)
                            ->whereIn('arquivos.codigoTipoDocumento', array(10))
                            ->first();
        return $arquivos;           
    }

    public static function listarArquivosAnalisePae($codigoPae, $group = false)    
    {
       if ($group)
       {
            $analise = Arquivo::join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                              ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_arquivos.codigoInscricao')
                              ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                              ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                              ->where('pae.codigoPae', $codigoPae)
                              ->whereIn('arquivos.codigoTipoDocumento', array(12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 24, 25))
                              ->groupBy('arquivos.codigoTipoDocumento')
                              ->get();  
       }
       else
       {       
            $analise = Arquivo::join('inscricoes_arquivos', 'arquivos.codigoArquivo', '=', 'inscricoes_arquivos.codigoArquivo')
                              ->join('inscricoes', 'inscricoes.codigoInscricao', '=', 'inscricoes_arquivos.codigoInscricao')
                              ->join('tipo_documentos', 'arquivos.codigoTipoDocumento', '=', 'tipo_documentos.codigoTipoDocumento')
                              ->join('pae', 'inscricoes.codigoInscricao', '=', 'pae.codigoInscricao')
                              ->where('pae.codigoPae', $codigoPae)
                              ->whereIn('arquivos.codigoTipoDocumento', array(12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 24, 25))
                              ->get(); 
       }        

        return $analise;
    }

}

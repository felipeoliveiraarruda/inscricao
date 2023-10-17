<?php

namespace App\Models\PAE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AnaliseCurriculoArquivo extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table      = 'analise_curriculo_arquivos';
    protected $primaryKey = 'codigoAnaliseCurriculoArquivo';

    protected $fillable = [
        'codigoAnaliseCurriculo',
        'codigoArquivo',
        'codigoPessoaAlteracao',
    ];

    public function analise_curriculo()
    {
        return $this->hasMany(\App\Models\PAE\AnaliseCurriculo::class);
    }

    public function arquivo()
    {
        return $this->hasMany(\App\Models\Arquivo::class);
    }

    public static function obterAnaliseCurriculoArquivo($codigoAnaliseCurriculoArquivo)
    {
        $analise = AnaliseCurriculoArquivo::where('codigoAnaliseCurriculoArquivo', $codigoAnaliseCurriculoArquivo)->first();

        return $analise;
    }

    public static function obterAnaliseCurriculoArquivoTotal($codigoPae, $codigoAnaliseCurriculo)
    {
        $analise = AnaliseCurriculoArquivo::join('analise_curriculo', 'analise_curriculo_arquivos.codigoAnaliseCurriculo', '=', 'analise_curriculo.codigoAnaliseCurriculo')
                                          ->join('arquivos', 'analise_curriculo_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                                          ->join('pae', 'pae.codigoPae', '=', 'analise_curriculo.codigoPae')
                                          ->where('pae.codigoPae', $codigoPae)
                                          ->where('analise_curriculo.codigoAnaliseCurriculo', $codigoAnaliseCurriculo)->count();
        return $analise;
    }

    public static function listarAnaliseCurriculoArquivo($codigoPae, $total = false)
    {
        if ($total)
        {
            $analise = AnaliseCurriculoArquivo::join('analise_curriculo', 'analise_curriculo_arquivos.codigoAnaliseCurriculo', '=', 'analise_curriculo.codigoAnaliseCurriculo')
                                              ->join('arquivos', 'analise_curriculo_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                                              ->join('pae', 'pae.codigoPae', '=', 'analise_curriculo.codigoPae')
                                              ->where('pae.codigoPae', $codigoPae)->count();
        }
        else
        {
            $analise = AnaliseCurriculoArquivo::join('analise_curriculo', 'analise_curriculo_arquivos.codigoAnaliseCurriculo', '=', 'analise_curriculo.codigoAnaliseCurriculo')
                                              ->join('arquivos', 'analise_curriculo_arquivos.codigoArquivo', '=', 'arquivos.codigoArquivo')
                                              ->join('pae', 'pae.codigoPae', '=', 'analise_curriculo.codigoPae')
                                              ->where('pae.codigoPae', $codigoPae)->get();
        }


        return $analise;
    }
}

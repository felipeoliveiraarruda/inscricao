<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class Utils extends Model
{
    use HasFactory;

    public static function listarCurso()
    {
        $link = env('URL_API_EEL')."posgraduacao/curso/listar";

        $response = Http::withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link);

        return $response->json();
    }

    public static function obterCurso($codcur)
    {
        $link = env('URL_API_EEL')."posgraduacao/curso/obter/";

        $response = Http::asForm()->withHeaders(
        [
            'x-api-key' => env('KEY_API_EEL')
        ])->post($link,
        [
            'codcur' => $codcur
        ]);

        $temp = $response->json(); 
 
        return $temp[0];
    }

    public static function obterSiglCurso($codcur)
    {
        switch ($codcur) 
        {
            case 97001:
                return 'PPGBI';
                break;
            case 97002:
                return 'PPGEM';
                break;
            case 97003:
                return 'PPGEQ';
                break;
            case 97004:
                return 'PPGPE';
                break;                                                
        }
    }    

    public static function obterNivelEdital($nivel)
    {
        switch ($nivel) 
        {
            case 'AE':
                return 'Aluno Especial';
                break;
            case 'DD':
                return 'Doutorado Direto';
                break;
            case 'DF':
                return 'Doutorado Fluxo Direto';
                break;
            case 'ME':
                return 'Mestrado';
                break;
                                                
        }
    }

    public static function obterEndereco($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        $link = "https://viacep.com.br/ws/{$cep}/json/";

        $response = Http::get($link);

        $temp = $response->json(); 
 
        return $temp;
    }

    public static function obterDadosSysUtils($tipo)
    {
        if($tipo == 'sexo')
        {
            $coluna = 'dadosSexo';
        }
        
        if ($tipo == 'raça')
        {
            $coluna = 'dadosRaca';
        }

        if ($tipo == 'civil')
        {
            $coluna = 'dadosEstadoCivil';
        }

        $dados = DB::table('sys_utils')->select($coluna)->first();
        return Str::of($dados->$coluna)->explode('|');
    }
}

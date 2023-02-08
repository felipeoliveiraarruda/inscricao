<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
 
        //dd($response->json());

        return $response->json();
    }

    public function obterCurso($codcur)
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

    public function obterSiglCurso($codcur)
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

    public function obterNivelEdital($nivel)
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

    public function obterEndereco($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        $link = "https://viacep.com.br/ws/{$cep}/json/";

        $response = Http::get($link);

        $temp = $response->json(); 
 
        return $temp;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Utils extends Model
{
    use HasFactory;

    public function listarCurso()
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
}

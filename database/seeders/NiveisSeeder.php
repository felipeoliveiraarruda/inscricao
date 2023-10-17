<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NiveisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *     
     * @return void
     */
    public function run()
    {
        DB::table('niveis')->insert(
            [
                [
                    'descricaoNivel'        => 'Aluno Especial',
                    'siglaNivel'            => 'AE',
                    'ativoNivel'            => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'descricaoNivel'        => 'Doutorado Direto',
                    'siglaNivel'            => 'DD',
                    'ativoNivel'            => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'descricaoNivel'        => 'Doutorado Fluxo Contínuo',
                    'siglaNivel'            => 'DF',
                    'ativoNivel'            => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'descricaoNivel'        => 'Mestrado',
                    'siglaNivel'            => 'ME',
                    'ativoNivel'            => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],                  
                [
                    'descricaoNivel'        => 'PAE',
                    'siglaNivel'            => 'PA',
                    'ativoNivel'            => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],                 
            ]
        );     
    }
}

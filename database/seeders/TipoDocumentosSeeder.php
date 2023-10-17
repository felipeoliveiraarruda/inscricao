<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipoDocumentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_documentos')->insert(
            [
                [
                    'tipoDocumento'         => 'CPF',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'RG',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],   
                [
                    'tipoDocumento'         => 'Passaporte',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'RNE',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],    
                [
                    'tipoDocumento'         => 'Hist�rico Escolar',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Diploma',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],  
                [
                    'tipoDocumento'         => 'Declara��o de Conclus�o',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],   
                [
                    'tipoDocumento'         => 'Curr�culo Vitae',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Curr�culo Lattes',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Pr�-projeto',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],    
                [
                    'tipoDocumento'         => 'Taxa Inscri��o',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'tipoDocumento'         => 'Artigo em Peri�dico Internacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],  
                [
                    'tipoDocumento'         => 'Artigo em Peri�dico Nacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Trabalho Completo - Anais Congresso Internacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],   
                [
                    'tipoDocumento'         => 'Trabalho Completo - Anais Congresso Nacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'tipoDocumento'         => 'Resumo em Congresso Internacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Resumo em Congresso Nacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Resumo Expandido em Congresso Internacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'tipoDocumento'         => 'Resumo Expandido em Congresso Nacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Cap�tulo de livros',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Patentes registradas',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,                    
                ],                                                                                                                 
            ]
        );       
    }
}

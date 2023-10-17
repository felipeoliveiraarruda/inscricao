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
                    'tipoDocumento'         => 'Histórico Escolar',
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
                    'tipoDocumento'         => 'Declaração de Conclusão',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],   
                [
                    'tipoDocumento'         => 'Currí­culo Vitae',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Currí­culo Lattes',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoDocumento'         => 'Pré-projeto',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],    
                [
                    'tipoDocumento'         => 'Taxa Inscrição',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'tipoDocumento'         => 'Artigo em Periódico Internacional',
                    'ativoTipoDocumento'    => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],  
                [
                    'tipoDocumento'         => 'Artigo em Periódico Nacional',
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
                    'tipoDocumento'         => 'Capí­tulo de livros',
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

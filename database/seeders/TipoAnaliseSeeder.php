<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipoAnaliseSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipo_analise')->insert(
            [
                [
                    'tipoAnalise'           => 'Iniciação Científica',
                    'calculoTipoAnalise'    => '[IC] * 0.5',
                    'pontuacaoTipoAnalise'  => 'Meses',
                    'maximoTipoAnalise'     => 12,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoAnalise'           => 'Monitoria / Estágio',
                    'calculoTipoAnalise'    => '[Me] * 0.5',
                    'pontuacaoTipoAnalise'  => 'Meses',
                    'maximoTipoAnalise'     => 8,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'tipoAnalise'           => 'Artigo em Periódico Internacional',
                    'calculoTipoAnalise'    => '[Pi] * 2.00',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],  
                [
                    'tipoAnalise'           => 'Artigo em Periódico Nacional',
                    'calculoTipoAnalise'    => '[Pn] * 1.00',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoAnalise'           => 'Trabalho Completo - Anais Congresso Internacional',
                    'calculoTipoAnalise'    => '[Tci] * 0.75',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],   
                [
                    'tipoAnalise'           => 'Trabalho Completo - Anais Congresso Nacional',
                    'calculoTipoAnalise'    => '[Tcn] * 0.50',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'tipoAnalise'           => 'Resumo em Congresso Internacional',
                    'calculoTipoAnalise'    => '[Rci] * 0.50',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoAnalise'           => 'Resumo em Congresso Nacional',
                    'calculoTipoAnalise'    => '[Rcn] * 0.10',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoAnalise'           => 'Resumo Expandido em Congresso Internacional',
                    'calculoTipoAnalise'    => '[Recn] * 0.40',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'tipoAnalise'           => 'Resumo Expandido em Congresso Nacional',
                    'calculoTipoAnalise'    => '[Recn] * 0.30',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoAnalise'           => 'Capí­tulo de livros',
                    'calculoTipoAnalise'    => '[Cl] * 1.50',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoAnalise'           => 'Patentes registradas',
                    'calculoTipoAnalise'    => '[Pr] * 2.00',
                    'pontuacaoTipoAnalise'  => 'Quantidade',
                    'maximoTipoAnalise'     => 0,
                    'statusTipoAnalise'     => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],                  
            ]
        ); 
    }
}

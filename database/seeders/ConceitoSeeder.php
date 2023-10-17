<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConceitoSeeder extends Seeder
{
    public function run()
    {
        DB::table('conceito')->insert(
            [
                [
                    'descricaoConceito'     => 'A',
                    'valorConceito'         => 10.00,
                    'calculoConceito'       => '[Qa] * [Va]',
                    'statusConceito'        => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'descricaoConceito'     => 'B',
                    'valorConceito'         => 7.50,
                    'calculoConceito'       => '[Qb] * [Vb]',
                    'statusConceito'        => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'descricaoConceito'     => 'C',
                    'valorConceito'         => 5.00,
                    'calculoConceito'       => '[Qc] * [Vc]',
                    'statusConceito'        => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ], 
                [
                    'descricaoConceito'     => 'D',
                    'valorConceito'         => 0.00,
                    'calculoConceito'       => '[Qd] * [Vd]',
                    'statusConceito'        => 'S',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],                                                    
            ]
        );   
    }
}

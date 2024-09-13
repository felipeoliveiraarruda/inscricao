<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipoEntidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_entidade')->insert(
            [
                [
                    'tipoEntidade'          => 'Sem Entidade',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoEntidade'          => 'Pública',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoEntidade'          => 'Privada',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],  
            ]
        ); 
    }
}

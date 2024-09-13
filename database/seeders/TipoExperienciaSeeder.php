<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipoExperienciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_experiencia')->insert(
            [
                [
                    'tipoExperiencia'       => 'Ensino',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],
                [
                    'tipoExperiencia'       => 'Profissional',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),                    
                    'codigoPessoaAlteracao' => 5840128,
                ],  
            ]
        ); 
    }
}

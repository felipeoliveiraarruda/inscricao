<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name'              => 'Administrador',
                    'email'             => 'dev.ci.eel@usp.br',
                    'email_verified_at' => Carbon::now(),
                    'password'          => '$2y$10$cl7CuNep2wDQNvE0CGHTBOzTYE5AC1iaLNzPqq.c4DRa4tcuIp/ti',
                    'remember_token'    => Str::random(60),
                    'cpf'               => '8800001',
                    'codpes'            => '8800001',
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),  
                ],
                [
                    'name'              => 'PPGPE',
                    'email'             => 'ppgpe@eel.usp.br',
                    'email_verified_at' => Carbon::now(),
                    'password'          => Hash::make('Ppgpe#23'),
                    'remember_token'    => Str::random(60),
                    'cpf'               => '8800002',
                    'codpes'            => '8800002',
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),  
                ],
                [
                    'name'              => 'PPGEM',
                    'email'             => 'ppgem-eel@usp.br',
                    'email_verified_at' => Carbon::now(),
                    'password'          => Hash::make('Ppgem#23'),
                    'remember_token'    => Str::random(60),
                    'cpf'               => '8800003',
                    'codpes'            => '8800003',
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),  
                ],
                [
                    'name'              => 'PAE',
                    'email'             => 'pae@eel.usp.br',
                    'email_verified_at' => Carbon::now(),
                    'password'          => Hash::make('Pae#23'),
                    'remember_token'    => Str::random(60),
                    'cpf'               => '8800004',
                    'codpes'            => '8800004',
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),  
                ],                
            ],
        );
    }
}

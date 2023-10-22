<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(NiveisSeeder::class);
        $this->call(TipoDocumentosSeeder::class);
        $this->call(SysUtilsSeeder::class);
        $this->call(ConceitoSeeder::class);
        $this->call(TipoAnaliseSeeder::class);
        $this->call(TipoEntidade::class);
        $this->call(TipoExperiencia::class);
    }
}

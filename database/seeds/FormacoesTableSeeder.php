<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormacoesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('formacoes')->insert([
            'nome' => 'Matemática'
        ]);
    }
}

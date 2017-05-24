<?php

use Illuminate\Database\Seeder;

class MembresiasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('membresias')->insert([
            'nombre' => 'Básica',
        ]);
        DB::table('membresias')->insert([
            'nombre' => 'Óptima',
        ]);
        DB::table('membresias')->insert([
            'nombre' => 'Integral',
        ]);
    }
}

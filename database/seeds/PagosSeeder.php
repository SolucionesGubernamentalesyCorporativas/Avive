<?php

use Illuminate\Database\Seeder;

class PagosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('pagos')->insert([
            'nombre' => '1 pago (-20%)',
        ]);

        DB::table('pagos')->insert([
            'nombre' => '3 pagos (-10%)',
        ]);

        DB::table('pagos')->insert([
            'nombre' => '6 pagos',
        ]);

    }
}

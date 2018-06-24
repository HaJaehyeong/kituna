<?php

use Illuminate\Database\Seeder;

class CoinStockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1 ; $i <= 97; $i++) {
            DB::table('coin_stock')->insert([
                'vd_id'     => $i,
                '1000'      => rand(7, 20),
                '500'       => rand(20, 50),
                '100'       => rand(30, 60),
                '50'        => 0,
                '10'        => 0,
                '5'         => 0,
                '1'         => 0
            ]);
        }
    }
}

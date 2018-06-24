<?php

use Illuminate\Database\Seeder;

class CarStockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0 ; $i < 11 ; $i++) {
            DB::table('car_stock')->inster([
                'cs_id' => null,
                'sp_login_id' => 'hajae',
                'stock_id'  => $i+1,
                'stock'     => rand(100, 250)
            ]);
        }
        
    }
}

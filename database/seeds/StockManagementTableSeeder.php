<?php

use Illuminate\Database\Seeder;

class StockManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drink = ['Coke_Zero', 'Coke', 'Pepsi', 'Cider', 'Narangd_Cider', 'Grape_Fanta', 
        'Orange_Fanta', 'Pineapple_Fanta', 'Pocari_Sweat', 'PowerAde', 'Gatorade'];

        for ($i = 1 ; $i <= count($drink) ; $i++) {
            DB::table('stock_management')->insert([
                'drink_id'          => $i,
                'stock_id'          => NULL,
                'buy_date'          => '2018-03-01 00:00:00',
                'expiration_date'   => '2020-03-01 00:00:00',
                'stock'             => rand(400, 1000)
            ]);
        }
        
    }
}

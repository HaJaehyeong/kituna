<?php

use Illuminate\Database\Seeder;

class ProductInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coke = ['Coke_Zero', 'Coke', 'Pepsi'];
        for ($i = 0 ; $i < count($coke) ; $i++) {
            DB::table('product_info')->insert([
                'drink_id'          => NULL,
                'drink_name'        => $coke[$i],
                'cp_id'             => 1,
                'drink_img_path'    => '/images/drink/'.$coke[$i].'.png',
                'drink_price'       => 200,
                'sell_price'        => 800,
                'max_stock'         => 2500
            ]);
        }

        $cider = ['Cider', 'Narangd_Cider'];
        for ($i = 0 ; $i < count($cider) ; $i++) {
            DB::table('product_info')->insert([
                'drink_id'          => NULL,
                'drink_name'        => $cider[$i],
                'cp_id'             => 2,
                'drink_img_path'    => '/images/drink/'.$cider[$i].'.png',
                'drink_price'       => 200,
                'sell_price'        => 800,
                'max_stock'         => 2500
            ]);
        }

        $fanta = ['Grape_Fanta', 'Orange_Fanta', 'Pineapple_Fanta'];
        for ($i = 0 ; $i < count($fanta) ; $i++) {
            DB::table('product_info')->insert([
                'drink_id'          => NULL,
                'drink_name'        => $fanta[$i],
                'cp_id'             => 3,
                'drink_img_path'    => '/images/drink/'.$fanta[$i].'.png',
                'drink_price'       => 200,
                'sell_price'        => 800,
                'max_stock'         => 2500
            ]);
        }

        $sports = ['Pocari_Sweat', 'PowerAde', 'Gatorade'];
        for ($i = 0 ; $i < count($sports) ; $i++) {
            DB::table('product_info')->insert([
                'drink_id'          => NULL,
                'drink_name'        => $sports[$i],
                'cp_id'             => 4,
                'drink_img_path'    => '/images/drink/'.$sports[$i].'.png',
                'drink_price'       => 200,
                'sell_price'        => 800,
                'max_stock'         => 2500
            ]);
        }
    }
}

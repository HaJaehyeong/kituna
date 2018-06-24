<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VdStockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        //
        // for ($i = 1 ; $i <= 97 ; $i++) {
        //     for ($j = 1 ; $j <= 8 ; $j++) {
        //         DB::table('vd_stock')->insert([
        //             'vd_id'     => $i,
        //             'stock_id'  => $j,
        //             'stock'     => rand(5, 35),
        //             'line'      => $j,
        //             'count'     => NULL,
        //             'max_stock' => 35
        //         ]);
        //     }
        // }
        $rand_drink_id = array();
        for ($i = 1 ; $i <= 97 ; $i++) {
            for ($j = 0 ; $j < 8 ; $j++) {
                $rand_drink_id[$j] = rand(1, 11);
                for ($k = 0 ; $k < $j ; $k++) {
                    if ($rand_drink_id[$j] == $rand_drink_id[$k]) {
                        $j--;
                        break;
                    }
                }
            }
            sort($rand_drink_id);
            for ($j = 0 ;$j < count($rand_drink_id) ; $j++) {
                DB::table('vd_stock')->insert([
                    'vd_id'     => $i,
                    'drink_id'  => $rand_drink_id[$j],
                    'stock'     => rand(5, 35),
                    'line'      => $j+1,
                    'count'     => NULL,
                    'max_stock' => 35
                ]);
            }
        }

        // for ($i = 1 ; $i <= 97 ; $i++) {
        //     for ($j = 0 ; $j < 8 ; $j++) {
        //         $check = true;
        //         $rand_line[$j] = rand(1, 11);
        //         for ($k = 0 ; $k < $j ; $k++) {
        //             if ($rand_line[$j] == $rand_line[$k]) {
        //                 $j--;
        //                 $check = false;
        //                 break;
        //             }
        //         }
        //         if ($check) {
        //             DB::table('vd_stock')->insert([
        //                 'vd_id'     => $i,
        //                 'stock_id'  => $j,
        //                 'stock'     => rand(5, 35),
        //                 'line'      => $rand_line[$j],
        //                 'count'     => NULL,
        //                 'max_stock' => 35
        //             ]);
        //         }
        //     }

        // 재고를 이용해 솔드아웃 설정
        $result = DB::table('vd_stock')->get();
        $soldout = array();               // 기본 값
        for ($i = 0 ;$i < 97 ;$i++) {
            $soldout[$i] = 0;
        }
        //  8개 미만의 재고를 가진 자판기는 매진임박(1)로 수정한다.
        for ($i = 0 ; $i < count($result) ; $i++) {
            if ($result[$i]->stock < 9 && $soldout[(int)($i/8)] == 0) {
                $soldout[(int)($i/8)] = 1;
            }
        }

        // 자판기의 재고에 따른 매진현황을 수정한다.
        for ($i = 0 ;$i < 97 ; $i++) {
            DB::table('vendingmachine')->where('vd_id', $i+1)->update(['vd_soldout' => $soldout[$i]]);
        }
        
    }
}

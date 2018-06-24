<?php

use Illuminate\Database\Seeder;

class SellDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        function makeDate($year) {
            if ($year == 2018) {
                $date = $year."-";
                // 월 구하기
                $month = rand(1, getdate()['mon']);
                // for ($i = 0 ; $i < (28/getdate()['mday']) ; $i++) {
                //     if ($month == getdate()['mon']) {
                //         $month = rand(1, getdate()['mon']);
                //     }
                // }
                
                if ($month < 10) {
                    $date .= '0'.$month;        // 만든 문자열을 날짜에 붙입니다.
                } else {
                    $date .= $month;
                }
                
                // 일 구하기
                if (getdate()['mday'] < 28 && $month == getdate()['mon']) {
                    $day = rand(1, getdate()['mday']);
                } else if ($month == 1  || $month == 3 || $month == 5 || $month == 7 || $month == 8 ||
                    $month == 10 || $month == 12) {
                    $day = rand(1, 31);
                } else if ($month == 2) {
                    $day = rand(1, 28);
                } else {
                    $day = rand(1, 30);
                }
                // 일 합치기
                if ($day < 10) {
                    $date .= '-0'.$day;        // 만든 문자열을 날짜에 붙입니다.
                } else {
                    $date .= '-'.$day;
                }

                $time = " ";
                $randTime = rand(0, 62);
                if      ($randTime >= 0  && $randTime <= 1) $randTime = "00:";
                else if ($randTime == 2) $randTime = "01:";else if ($randTime == 3) $randTime = "02:";
                else if ($randTime == 4) $randTime = "03:";else if ($randTime == 5) $randTime = "04:";
                else if ($randTime == 6) $randTime = "05:";else if ($randTime == 7) $randTime = "06:";
                else if ($randTime >= 8  && $randTime <= 9)  $randTime = "07:";
                else if ($randTime >= 10 && $randTime <= 12) $randTime = "08:";
                else if ($randTime >= 13 && $randTime <= 15) $randTime = "09:";
                else if ($randTime >= 16 && $randTime <= 17) $randTime = "10:";
                else if ($randTime >= 18 && $randTime <= 19) $randTime = "11:";
                else if ($randTime >= 20 && $randTime <= 23) $randTime = "12:";
                else if ($randTime >= 24 && $randTime <= 29) $randTime = "13:";
                else if ($randTime >= 30 && $randTime <= 34) $randTime = "14:";
                else if ($randTime >= 35 && $randTime <= 37) $randTime = "15:";
                else if ($randTime >= 38 && $randTime <= 40) $randTime = "16:";
                else if ($randTime >= 41 && $randTime <= 43) $randTime = "17:";
                else if ($randTime >= 44 && $randTime <= 47) $randTime = "18:";
                else if ($randTime >= 48 && $randTime <= 50) $randTime = "19:";
                else if ($randTime >= 51 && $randTime <= 53) $randTime = "20:";
                else if ($randTime >= 54 && $randTime <= 57) $randTime = "21:";
                else if ($randTime >= 58 && $randTime <= 60) $randTime = "22:";
                else if ($randTime >= 61 && $randTime <= 62) $randTime = "23:";
                $time .= $randTime;

                $rand = rand(0, 59);
                if ($rand < 10) 
                    $rand = "0".$rand.":";
                else
                    $rand = $rand.":";
                $time .= $rand;

                $rand = rand(0, 59);
                if ($rand < 10) 
                    $rand = "0".$rand;
                $time .= $rand;

                $date .= $time;
            } else {
                $date = $year."-";
                
                // 월 구하기
                $month = rand(1, 12);
                if ($month < 10) {
                    $date .= '0'.$month;        // 만든 문자열을 날짜에 붙입니다.
                } else {
                    $date .= $month;
                }
                
                // 일 구하기
                if ($month == 1  || $month == 3 || $month == 5 || $month == 7 || $month == 8 ||
                    $month == 10 || $month == 12) {
                    $day = rand(1, 31);
                } else if ($month == 2) {
                    $day = rand(1, 28);
                } else {
                    $day = rand(1, 30);
                }
                // 일 합치기
                if ($day < 10) {
                    $date .= '-0'.$day;        // 만든 문자열을 날짜에 붙입니다.
                } else {
                    $date .= '-'.$day;
                }

                // 시간 구하고 합치기
                $time = " ";
                $randTime = rand(0, 62);
                if      ($randTime >= 0  && $randTime <= 1) $randTime = "00:";
                else if ($randTime == 2) $randTime = "01:";else if ($randTime == 3) $randTime = "02:";
                else if ($randTime == 4) $randTime = "03:";else if ($randTime == 5) $randTime = "04:";
                else if ($randTime == 6) $randTime = "05:";else if ($randTime == 7) $randTime = "06:";
                else if ($randTime >= 8  && $randTime <= 9)  $randTime = "07:";
                else if ($randTime >= 10 && $randTime <= 12) $randTime = "08:";
                else if ($randTime >= 13 && $randTime <= 15) $randTime = "09:";
                else if ($randTime >= 16 && $randTime <= 17) $randTime = "10:";
                else if ($randTime >= 18 && $randTime <= 19) $randTime = "11:";
                else if ($randTime >= 20 && $randTime <= 23) $randTime = "12:";
                else if ($randTime >= 24 && $randTime <= 29) $randTime = "13:";
                else if ($randTime >= 30 && $randTime <= 34) $randTime = "14:";
                else if ($randTime >= 35 && $randTime <= 37) $randTime = "15:";
                else if ($randTime >= 38 && $randTime <= 40) $randTime = "16:";
                else if ($randTime >= 41 && $randTime <= 43) $randTime = "17:";
                else if ($randTime >= 44 && $randTime <= 47) $randTime = "18:";
                else if ($randTime >= 48 && $randTime <= 50) $randTime = "19:";
                else if ($randTime >= 51 && $randTime <= 53) $randTime = "20:";
                else if ($randTime >= 54 && $randTime <= 57) $randTime = "21:";
                else if ($randTime >= 58 && $randTime <= 60) $randTime = "22:";
                else if ($randTime >= 61 && $randTime <= 62) $randTime = "23:";
                $time .= $randTime;

                $rand = rand(0, 59);
                if ($rand < 10) 
                    $rand = "0".$rand.":";
                else
                    $rand = $rand.":";
                $time .= $rand;

                $rand = rand(0, 59);
                if ($rand < 10) 
                    $rand = "0".$rand;
                $time .= $rand;

                $date .= $time;
            }
            return $date;
        }

        $drink = array(); // 1, 2, 3, 6, 8
        $drink = ['Coke_Zero', 'Coke', 'Pepsi', 'Cider', 'Narangd_Cider', 'Grape_Fanta', 
        'Orange_Fanta', 'Pineapple_Fanta', 'Pocari_Sweat', 'PowerAde', 'Gatorade'];

        // 0:0~2, 1:3~11, 2:12~18, 3:19~25, 4:26~27, 5:28~29, 6:30~36, 
        // 7:36~37, 8:38~44, 9:45~46, 10:47~48
        for($i = 0 ;$i < 100000; $i++) {
            $rand_id = rand(1, 97);
            $rand = rand(0, 48);
            if      ($rand >=  0 && $rand <= 2)  $drink = "Coke_Zero";
            else if ($rand >=  3 && $rand <= 11) $drink = "Coke";
            else if ($rand >= 12 && $rand <= 18) $drink = "Pepsi";
            else if ($rand >= 19 && $rand <= 25) $drink = "Cider";
            else if ($rand >= 26 && $rand <= 27) $drink = "Narangd_Cider";
            else if ($rand >= 28 && $rand <= 29) $drink = "Grape_Fanta";
            else if ($rand >= 30 && $rand <= 36) $drink = "Orange_Fanta";
            else if ($rand >= 36 && $rand <= 37) $drink = "Pineapple_Fanta";
            else if ($rand >= 38 && $rand <= 44) $drink = "Pocari_Sweat";
            else if ($rand >= 45 && $rand <= 46) $drink = "PowerAde";
            else if ($rand >= 47 && $rand <= 48) $drink = "Gatorade";

            $getDrinkId = DB::table('product_info as pi')
            ->select(DB::raw('vs.line line, pi.drink_id drink_id'))
            ->join('vd_stock as vs', 'vs.drink_id', '=', 'pi.drink_id')
            ->where('pi.drink_name', $drink)
            ->where('vs.vd_id', $rand_id)->get();

            if (!isset($getDrinkId[0]->drink_id)) {
                $i--;
                continue;
            }

            if($i < 75000) {
                $date = makeDate('2017');
            } else {
                $date = makeDate('2018');
            }

            DB::table('sell_data')->insert([
                'vd_id'     => $rand_id,
                'line'      => $getDrinkId[0]->line,
                'drink_id'  => $getDrinkId[0]->drink_id,
                'sell_date' => $date,
                'in_coin'   => '800'
            ]);  
        }
        
    }
}

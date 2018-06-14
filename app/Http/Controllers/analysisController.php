<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class analysisController extends Controller
{
    public function analysisData($place) {
        $saveAll = array();
        // 장소가 all인경우
        if ($place == "all"){
            /**이번달 판매량
             * select    count(*) as count
             * from        sell_data 
             * where    date_format(sell_date, "%Y-%m") = date_format(now(), "%Y-%m")
             * group by  date_format(sell_date, "%Y-%m")
             */
            $getSalesThisMonth = DB::table('sell_data')
            ->select(DB::raw('count(*) as count'))
            ->where(DB::raw('date_format(sell_date, "%Y-%m")'), 
                    DB::raw('date_format(now(), "%Y-%m")'))
            ->groupBy(DB::raw('date_format(sell_date, "%Y-%m")'))->get();

            /**오늘 판매량과 매출
             * select    count(*) as count, count(sd.coin_1000)*1000+count(sd.coin_500)*500+count     * (sd.coin_100)*100 as getSales
             * from        sell_data 
             * where    date_format(sell_date, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")
             * group by  date_format(sell_date, "%Y-%m-%d")
             */
            $getSalesThisDay = DB::table('sell_data')
            ->select(DB::raw('count(*) as count'), 
            DB::raw('sum(coin_1000*1000+coin_500*500+coin_100*100) as getSales'))
            ->where(DB::raw('date_format(sell_date, "%Y-%m-%d")'), 
                    DB::raw('date_format(now(), "%Y-%m-%d")'))
            ->groupBy(DB::raw('date_format(sell_date, "%Y-%m-%d")'))->get();
            
            if (!isset($getSalesThisDay[0])) {
                $getSalesThisDay = array();
                $getSalesThisDay[0] = (object)array(
                    'count' => 0,
                    'getSales' => 0
                );
            }

            // 배열에 저장
            $saveAll[0] = $getSalesThisMonth;
            $saveAll[1] = $getSalesThisDay;

        } else {
            /**이번달 판매량
             * select    count(*) as count
             * from        sell_data as sd
             * join        vendingmachine as vd on vd.vd_id = sd.vd_id
             * where    date_format(sd.sell_date, "%Y-%m") = date_format(now(), "%Y-%m")
             * and        vd.vd_place like "school%"
             * group     by date_format(sd.sell_date, "%Y-%m")
             */
            $getSalesThisMonth = DB::table('sell_data as sd')
            ->select(DB::raw('count(*) as count'))
            ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
            ->where(DB::raw('date_format(sd.sell_date, "%Y-%m")'), 
                    DB::raw('date_format(now(), "%Y-%m")'))
            ->where('vd.vd_place', 'like', $place.'%')
            ->groupBy(DB::raw('date_format(sd.sell_date, "%Y-%m")'))->get();

            /**오늘 판매량과 매출
             * select    count(*) as count
             * from        sell_data as sd
             * join        vendingmachine as vd on vd.vd_id = sd.vd_id
             * where    date_format(sd.sell_date, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")
             * and        vd.vd_place like "school%"
             * group     by date_format(sd.sell_date, "%Y-%m-%d")
             */
            $getSalesThisDay = DB::table('sell_data as sd')
            ->select(DB::raw('count(*) as count'), 
            DB::raw('sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales'))
            ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
            ->where(DB::raw('date_format(sd.sell_date, "%Y-%m-%d")'), 
                    DB::raw('date_format(now(), "%Y-%m-%d")'))
            ->where('vd.vd_place', 'like', $place.'%')
            ->groupBy(DB::raw('date_format(sd.sell_date, "%Y-%m-%d")'))->get();

            if (!isset($getSalesThisDay[0])) {
                $getSalesThisDay = array();
                $getSalesThisDay[0] = (object)array(
                    'count' => 0,
                    'getSales' => 0
                );
            }

            // 배열에 저장
            $saveAll[0] = $getSalesThisMonth;
            $saveAll[1] = $getSalesThisDay;
        }

        return $saveAll;
    }

    public function ListOfDrinkSales($place, $date, $sort, $offset) {
        $saveAll = array();
        $strDate = '';                  // mysql 날짜 형식을 저장할 문자열 변수

        $rank = array();

        // 넘어오는 값에 따라 mysql 날짜 형식을 바꿔준다.
        switch($date){
            case 'year':
                $strDate = '%Y';
                break;
            case 'month':
                $strDate = '%Y-%m';
                break;
            case 'week':
                $strDate = '%Y-%m-%d';
                break;
        }

        // 장소가 all인경우
        if ($place == "all"){
            /** 해당 날짜에 맞게 자판기 판매 순위를 보여준다.
             * select sd.vd_id, vd_name,count(*) as count, sum(sd.coin_1000*1000+sd.coin_500*500      * +sd.coin_100*100) as getSales
             * from sell_data as sd
             * join vendingmachine as vd on vd.vd_id = sd.vd_id
             * where date_format(sell_date, "%Y-%m") = date_format(now(), "%Y-%m")
             * group by sd.vd_id
             * order by count desc
             * limit 5
             */
            /* 이건 그 ... 엄청 복잡한데.. 그 누르는 버튼에 맞게 매출의 차이가 심하거나 적은
             * 그런 자판기를 하나를 선정해서 보여주는거
             * select sub1.vd_id, sub1.sum - sub2.sum as value
             * from (select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500            *       +sd.coin_100*100) as sum
             *       from    sell_data as sd
             *       where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(date_sub *       (date_format(now(), "%Y-%m-%d"), interval 8 day), "%Y-%m-%d"), interval  *       15 day)
             *       group by vd_id) as sub1
             * join (select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500            *       +sd.coin_100*100) as sum 
             *       from    sell_data as sd
             *       where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(now(),   *       "%Y-%m-%d"), interval 8 day)
             *       group by vd_id) as sub2 on sub1.vd_id = sub2.vd_id
             * group by sub1.vd_id
             * order by value desc
             * limit 1
             */
            if ($date == 'week') {
                $getListOfDrinkSales = DB::table('sell_data as sd')
                ->select('sd.vd_id', 'vd.vd_name', DB::raw('count(*) as count'), 
                        DB::raw('sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales'), 'vd_supplementer')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
                ->where(DB::raw('date_format(sell_date, "'.$strDate.'")'), '>', 
                        DB::raw('date_sub(date_format(now(), "'.$strDate.'"), interval 8 day)'))
                ->groupBy('sd.vd_id')
                ->orderBy('count', $sort)
                ->offset($offset)->limit(5)->get();

            } else {
                $getListOfDrinkSales = DB::table('sell_data as sd')
                ->select('sd.vd_id', 'vd.vd_name', DB::raw('count(*) as count'), 
                        DB::raw('sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales'), 'vd_supplementer')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
                ->where(DB::raw('date_format(sell_date, "'.$strDate.'")'), 
                        DB::raw('date_format(now(), "'.$strDate.'")'))
                ->groupBy('sd.vd_id')
                ->orderBy('count', $sort)
                ->offset($offset)->limit(5)->get();
            }
            if ($sort == 'desc') {
                for ($i = 0 ; $i < count($getListOfDrinkSales) ; $i++) {
                    $getListOfDrinkSales[$i]->num = $i+1;
                }
            } else if ($sort == 'asc') {
                $getVDCount = DB::table('vendingmachine')
                ->select(DB::raw('count(*) as count'))->get();

                for ($i = 0 ; $i < count($getListOfDrinkSales) ; $i++) {
                    $getListOfDrinkSales[$i]->num = $getVDCount[0]->count-$i;
                }
            }

            if ($date == "week") {
                $getBestSalesDifference = DB::table(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(date_sub(date_format(now(), "%Y-%m-%d"), interval 8 day), "%Y-%m-%d"), interval 15 day)
                group by vd_id) as sub1'))
                ->select('sub1.vd_id', 'vd.vd_name', 'vd.vd_supplementer', DB::raw('sub2.sum - sub1.sum as value'))
                ->join(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum 
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(now(), "%Y-%m-%d"), interval 8 day)
                group by vd_id) as sub2'), 'sub1.vd_id', '=', 'sub2.vd_id')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sub1.vd_id')
                ->groupBy('sub1.vd_id')
                ->orderBy('value', $sort)
                ->limit(1)->get();
            } else if ($date == "month") {
                $getBestSalesDifference = DB::table(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(date_sub(date_format(now(), "%Y-%m-%d"), interval 30 day), "%Y-%m-%d"), interval 60 day)
                group by vd_id) as sub1'))
                ->select('sub1.vd_id', 'vd.vd_name', 'vd.vd_supplementer', DB::raw('sub2.sum - sub1.sum as value'))
                ->join(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum 
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(now(), "%Y-%m-%d"), interval 30 day)
                group by vd_id) as sub2'), 'sub1.vd_id', '=', 'sub2.vd_id')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sub1.vd_id')
                ->groupBy('sub1.vd_id')
                ->orderBy('value', $sort)
                ->limit(1)->get();
            } else if ($date == "year") {
                $getBestSalesDifference = DB::table(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(date_sub(date_format(now(), "%Y-%m-%d"), interval 365 day), "%Y-%m-%d"), interval 730 day)
                group by vd_id) as sub1'))
                ->select('sub1.vd_id', 'vd.vd_name', 'vd.vd_supplementer', DB::raw('sub2.sum - sub1.sum as value'))
                ->join(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum 
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(now(), "%Y-%m-%d"), interval 365 day)
                group by vd_id) as sub2'), 'sub1.vd_id', '=', 'sub2.vd_id')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sub1.vd_id')
                ->groupBy('sub1.vd_id')
                ->orderBy('value', $sort)
                ->limit(1)->get();
            }

            $all = DB::table('sell_data as sd')
                ->select('sd.vd_id', DB::raw('count(*) as count'))
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
                ->where(DB::raw('date_format(sell_date, "%Y-%m")'), '=', 
                        DB::raw('date_format(now(), "%Y-%m")'))
                ->groupBy('sd.vd_id')
                ->orderBy('count', $sort)->get();

            for ($i = 0 ; $i < count($all) ; $i++) {
                $rank[$i] =  (object)array(
                    'vd_id' => $all[$i]->vd_id,
                    'num'   => $i
                );
            }

            // 값 저장하기
            $saveAll[0] = $getListOfDrinkSales;
            $saveAll[1] = $getBestSalesDifference;
            $saveAll[2] = $rank;

        } else {
            /** 해당 날짜에 맞게 자판기 판매 순위를 보여준다.
             * select sd.vd_id, vd_name,count(*) as count, sum(sd.coin_1000*1000+sd.coin_500*500      * +sd.coin_100*100) as getSales
             * from sell_data as sd
             * join vendingmachine as vd on vd.vd_id = sd.vd_id
             * where date_format(sell_date, "%Y-%m") = date_format(now(), "%Y-%m")
             * and   vd.vd_place like 'school%'
             * group by sd.vd_id
             * order by count desc
             * limit 5
             */
            if ($date == 'week') {
                $getListOfDrinkSales = DB::table('sell_data as sd')
                ->select('sd.vd_id', 'vd.vd_name', DB::raw('count(*) as count'), 
                        DB::raw('sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales'), 'vd_supplementer')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
                ->where(DB::raw('date_format(sell_date, "'.$strDate.'")'), '>', 
                        DB::raw('date_sub(date_format(now(), "'.$strDate.'"), interval 8 day)'))
                ->where('vd.vd_place', 'like', $place.'%')
                ->groupBy('sd.vd_id')
                ->orderBy('count', $sort)
                ->offset($offset)->limit(5)->get(); 
            } else {
                $getListOfDrinkSales = DB::table('sell_data as sd')
                ->select('sd.vd_id', 'vd.vd_name', DB::raw('count(*) as count'), 
                        DB::raw('sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales'), 'vd_supplementer')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
                ->where(DB::raw('date_format(sell_date, "'.$strDate.'")'), 
                        DB::raw('date_format(now(), "'.$strDate.'")'))
                ->where('vd.vd_place', 'like', $place.'%')
                ->groupBy('sd.vd_id')
                ->orderBy('count', $sort)
                ->offset($offset)->limit(5)->get(); 
            }
            if ($sort == 'desc') {
                for ($i = 0 ; $i < count($getListOfDrinkSales) ; $i++) {
                    $getListOfDrinkSales[$i]->num = $i+1;
                }
            } else if ($sort == 'asc') {
                $getVDCount = DB::table('vendingmachine')
                ->select(DB::raw('count(*) as count'))->get();

                for ($i = 0 ; $i < count($getListOfDrinkSales) ; $i++) {
                    $getListOfDrinkSales[$i]->num = $getVDCount[0]->count-$i;
                }
            }

            if ($date == "week") {
                $getBestSalesDifference = DB::table(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(date_sub(date_format(now(), "%Y-%m-%d"), interval 8 day), "%Y-%m-%d"), interval 15 day)
                group by vd_id) as sub1'))
                ->select('sub1.vd_id', 'vd.vd_name', 'vd.vd_supplementer', DB::raw('sub2.sum - sub1.sum as value'))
                ->join(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum 
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(now(), "%Y-%m-%d"), interval 8 day)
                group by vd_id) as sub2'), 'sub1.vd_id', '=', 'sub2.vd_id')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sub1.vd_id')
                ->where('vd.vd_place', 'like', $place.'%')
                ->groupBy('sub1.vd_id')
                ->orderBy('value', $sort)
                ->limit(1)->get();
            } else if ($date == "month") {
                $getBestSalesDifference = DB::table(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(date_sub(date_format(now(), "%Y-%m-%d"), interval 30 day), "%Y-%m-%d"), interval 60 day)
                group by vd_id) as sub1'))
                ->select('sub1.vd_id', 'vd.vd_name', 'vd.vd_supplementer', DB::raw('sub2.sum - sub1.sum as value'))
                ->join(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum 
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(now(), "%Y-%m-%d"), interval 30 day)
                group by vd_id) as sub2'), 'sub1.vd_id', '=', 'sub2.vd_id')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sub1.vd_id')
                ->where('vd.vd_place', 'like', $place.'%')
                ->groupBy('sub1.vd_id')
                ->orderBy('value', $sort)
                ->limit(1)->get();
            } else if ($date == "year") {
                $getBestSalesDifference = DB::table(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(date_sub(date_format(now(), "%Y-%m-%d"), interval 365 day), "%Y-%m-%d"), interval 730 day)
                group by vd_id) as sub1'))
                ->select('sub1.vd_id', 'vd.vd_name', 'vd.vd_supplementer', DB::raw('sub2.sum - sub1.sum as value'))
                ->join(DB::raw('(select vd_id, count(*), sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as sum 
                from    sell_data as sd
                where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(now(), "%Y-%m-%d"), interval 365 day)
                group by vd_id) as sub2'), 'sub1.vd_id', '=', 'sub2.vd_id')
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sub1.vd_id')
                ->where('vd.vd_place', 'like', $place.'%')
                ->groupBy('sub1.vd_id')
                ->orderBy('value', $sort)
                ->limit(1)->get();
            }

            $all = DB::table('sell_data as sd')
                ->select('sd.vd_id', DB::raw('count(*) as count'))
                ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
                ->where(DB::raw('date_format(sell_date, "%Y-%m")'), '=', 
                        DB::raw('date_format(now(), "%Y-%m")'))
                ->where('vd.vd_place', 'like', $place.'%')
                ->groupBy('sd.vd_id')
                ->orderBy('count', $sort)->get();
    
            for ($i = 0 ; $i < count($all) ; $i++) {
                $rank[$i] =  (object)array(
                    'vd_id' => $all[$i]->vd_id,
                    'num'   => $i
                );
            }

            // 값 저장하기
            $saveAll[0] = $getListOfDrinkSales;
            $saveAll[1] = $getBestSalesDifference;
            $saveAll[2] = $rank;
        }

        return $saveAll;
    }

    public function vendingmachineAnalysis($vd_id) {
        // 저장할 배열
        $saveAll = array();

        /**해당 자판기의 음료 판매 순위
         * select    pi.drink_name, pi.drink_id, count(*) as count
         * from      sell_data as sd
         * join    product_info as pi on pi.drink_id = sd.drink_id
         * where     sd.vd_id = $vd_id
         * group by sd.drink_id
         * order by  count desc
         */
        $getSalesRank = DB::table('sell_data as sd')
        ->select('pi.drink_id', 'pi.drink_name', 'pi.drink_img_path', DB::raw('count(*) as count'))
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->where('sd.vd_id', $vd_id)
        ->groupBy('sd.drink_id')
        ->orderBy('count', 'desc')->get();

        /**
         * select drink_id
       * from   vd_stock
       * where  vd_id = $vd_id
         */
        $weNeedItBecauseForUnderSQL1 = DB::table('vd_stock')
        ->select('drink_id')
        ->where('vd_id', $vd_id)->get();

        $array = array();
        for ($i = 0 ;$i < count($weNeedItBecauseForUnderSQL1) ; $i++){
            $array[$i] = $weNeedItBecauseForUnderSQL1[$i]->drink_id;
        }

        /**
         * (select drink_id
         *  from product_info 
         *  where drink_id not in array)
         */
        $weNeedItBecauseForUnderSQL2 = DB::table('product_info')
        ->select('drink_id')
        ->whereNotIn('drink_id', $array)->get();
        
        // 초기화
        $array = array();
        
        // 배열로 저장
        for ($i = 0 ;$i < count($weNeedItBecauseForUnderSQL2) ; $i++){
            $array[$i] = $weNeedItBecauseForUnderSQL2[$i]->drink_id;
        }

        /**해당 자판기에 없는 음료 순위 
         * select pi.drink_name, count(*) as count
         * from sell_data as sd
         * join product_info as pi on pi.drink_id = sd.drink_id
         * where sd.drink_id in (select drink_id
         *                       from product_info 
         *                       where drink_id not in (select drink_id
         *                                              from   vd_stock
         *                                              where  vd_id = $vd_id))
         * group by sd.drink_id
         * order by count desc
         */
        $getNotExistDrinkRank = DB::table('sell_data as sd')
        ->select('pi.drink_id', 'pi.drink_name', 'pi.drink_img_path', DB::raw('count(*) as count'))
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->whereIn('sd.drink_id', $array)
        ->groupBy('sd.drink_id')
        ->orderBy('count', 'desc')->get();

        for ($i = 0 ; $i < count($getNotExistDrinkRank) ; $i++) {
            $getNotExistDrinkRank[$i]->num = $i+1;
        }

        // 해당 자판기 보충 주기
        /*select (to_days(sub2.order_date)-to_days(sub.order_date))/(count(*)/8) as spInterval
        * from jo_column, 
        * (select order_date from job_order order by order_date asc limit 1) as sub,
        * (select order_date from job_order order by order_date desc limit 1) as sub2
        * where vd_id = 2*/
        $getSpInterval = DB::table(DB::raw('jo_column, 
        (select order_date from job_order order by order_date asc limit 1) as sub,
        (select order_date from job_order order by order_date desc limit 1) as sub2'))
        ->select(DB::raw('(to_days(sub2.order_date)-to_days(sub.order_date))/(count(*)/8) as spInterval'))
        ->where('vd_id', $vd_id)->get();

        // 컴플레인
        /*select count(*)
        *from complain
        *where vd_id = 1;*/
        $getComplain = DB::table('complain')
        ->select(DB::raw('count(*) as count'))
        ->where('vd_id', $vd_id)->get();

        /**자판기 장소 구하기
         * SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(vd_place, ',', 1), ',', -1) as place
         * from vendingmachine
         * where vd_id = 2;*/
        $getPlace = DB::table('vendingmachine')
        ->select(DB::raw('SUBSTRING_INDEX(SUBSTRING_INDEX(vd_place, ",", 1), ",", -1) as place'))
        ->where('vd_id', $vd_id)->get();
        
        // 값 저장하기
        $saveAll[0] = $getSalesRank;
        $saveAll[1] = $getNotExistDrinkRank;
        $saveAll[2] = $getSpInterval;
        $saveAll[3] = $getComplain;
        $saveAll[4] = $getPlace;

        return $saveAll;
    }

    public function drinkSalesRank($date){
        $saveAll = array();
        $strDate = '';                  // mysql 날짜 형식을 저장할 문자열 변수

        // 넘어오는 값에 따라 mysql 날짜 형식을 바꿔준다.
        switch($date){
            case 'year':
                $strDate = '%Y';
                break;
            case 'month':
                $strDate = '%Y-%m';
                break;
            case 'week':
                $strDate = '%Y-%m-%d';
                break;
        }

        if ($date == 'week') {
            /**음료 판매 순위
             * select pi.drink_name, count(*) as count
             * from sell_data as sd
             * join product_info as pi on pi.drink_id = sd.drink_id
             * where date_format(sell_date, "%Y") = date_format(now(), "%Y")
             * group by sd.drink_id
             * order by count desc
             */
            $getListOfDrinkSales = DB::table('sell_data as sd')
            ->select('pi.drink_name', DB::raw('count(*) as count'))
            ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
            ->where(DB::raw('date_format(sell_date, "'.$strDate.'")'), '>', 
                    DB::raw('date_sub(date_format(now(), "'.$strDate.'"), interval 8 day)'))
            ->groupBy('sd.drink_id')
            ->orderBy('count', 'desc')->get(); 
        } else {
            $getListOfDrinkSales = DB::table('sell_data as sd')
            ->select('pi.drink_name', DB::raw('count(*) as count'))
            ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
            ->where(DB::raw('date_format(sell_date, "'.$strDate.'")'), 
                    DB::raw('date_format(now(), "'.$strDate.'")'))
            ->groupBy('sd.drink_id')
            ->orderBy('count', 'desc')->get(); 
        }

        // num 매기기
        for ($i = 0 ; $i < count($getListOfDrinkSales) ; $i++) {
            $getListOfDrinkSales[$i]->num = $i+1;
        }

        /************위는 그래프 들어갈 값 밑은 표에 들어갈 값************/
        /**
         * select pi.drink_name, count(*)/7 as count
         * from sell_data as sd
         * join product_info as pi on pi.drink_id = sd.drink_id
         * where date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(now(), "%Y-%m-%d"),        * interval 8 day)
         * group by sd.drink_id
         * order by count desc
         */
        $getAvgSalesValues = DB::table('sell_data as sd')
        ->select('pi.drink_name', 'pi.drink_id', 'sm.stock_id', DB::raw('count(*)/7 as count'))
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->join('stock_management as sm', 'sm.drink_id', '=', 'pi.drink_id')
        ->where(DB::raw('date_format(sell_date, "%Y-%m-%d")'), '>', 
        DB::raw('date_sub(date_format(now(), "%Y-%m-%d"), interval 8 day)'))
        ->groupBy('sd.drink_id')
        ->orderBy('count', 'desc')->get();

        /**창고 재고 파악하기
         * select drink_id, sum(stock)
         * from stock_management
         * group by drink_id
         */
        $getStock = DB::table('stock_management')
        ->select('drink_id', DB::raw('sum(stock) as sum'))
        ->groupBy('drink_id')->get();
        
        $array = array();
        $count = 0;
        
        $ICount = count($getAvgSalesValues);
        if ($ICount > 7) {
            $ICount = 7;
        }
        for($i = 0 ; $i < $ICount ; $i++) {
            for($j = 0 ; $j < count($getStock) ; $j++) {
                if ($getAvgSalesValues[$i]->drink_id == $getStock[$j]->drink_id){
                    $array[$count] = (object)array(
                        'stock_id'   => $getAvgSalesValues[$i]->stock_id,
                        'drink_name' => $getAvgSalesValues[$i]->drink_name,
                        'avgSales'   => $getAvgSalesValues[$i]->count,
                        'prediction' => (int)($getStock[$j]->sum/$getAvgSalesValues[$i]->count)+1,
                        'sum'        => $getStock[$j]->sum
                    );
                    $count++;
                }
            }
        }
        
        usort($array, function($a, $b){
            if($a->prediction == $b->prediction){
                return 0;
            }
            if($a->prediction < $b->prediction){
                return -1;
            }
            if($a->prediction > $b->prediction){
                return 1;
            }
        });


        $saveAll[0] = $getListOfDrinkSales;
        $saveAll[1] = $array;
        
        return $saveAll;

    }

    public function yearMonthWeekData($place) {
        $saveAll = array();
        /**년간 판매량 -> 최근 12개월 판매량과 매출
         * select   date_format(sell_date, "%Y-%m") as date, count(*) as count,
         *          sum(coin_1000*1000+coin_500*500+coin_100*100) as getSales
         * from sell_data
         * group by  date_format(sell_date, "%Y-%m")
         * order by  date desc
         * limit 12
         */
        if ($place == 'all'){
            $getYearData = DB::table('sell_data')
            ->select(DB::raw('date_format(sell_date, "%Y-%m") as date'), DB::raw('count(*) as count'),DB::raw('sum(coin_1000*1000+coin_500*500+coin_100*100) as getSales'))
            ->groupBy(DB::raw('date_format(sell_date, "%Y-%m")'))
            ->orderBy('date', 'desc')
            ->limit(12)->get();

            // 월간 판매량, 매출
            $getMonthData = DB::table('sell_data')
            ->select(DB::raw('date_format(sell_date, "%Y-%m-%d") as date'), DB::raw('count(*) as count'), DB::raw('sum(coin_1000*1000+coin_500*500+coin_100*100) as getSales'))
            ->groupBy(DB::raw('date_format(sell_date, "%Y-%m-%d")'))
            ->orderBy('date', 'desc')
            ->limit(31)->get();

            // 일주일간 판매량, 매출
            $getWeekData = DB::table('sell_data')
            ->select(DB::raw('date_format(sell_date, "%Y-%m-%d") as date'), DB::raw('count(*) as count'), DB::raw('sum(coin_1000*1000+coin_500*500+coin_100*100) as getSales'))
            ->groupBy(DB::raw('date_format(sell_date, "%Y-%m-%d")'))
            ->orderBy('date', 'desc')
            ->limit(7)->get();
        } else {
            /**
             * select    date_format(sell_date, "%Y-%m-%d") as date, count(*) as count,
             *           sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales
             * from        sell_data as sd
             * join      vendingmachine as vd on vd.vd_id = sd.vd_id
             * where     vd.vd_place like "apart%"
             * group by  date_format(sell_date, "%Y-%m-%d")
             * order by  date desc
             * limit 31
             */
            $getYearData = DB::table('sell_data as sd')
            ->select(DB::raw('date_format(sell_date, "%Y-%m") as date'), DB::raw('count(*) as count'),DB::raw('sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales'))
            ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
            ->where('vd.vd_place', 'like', $place.'%')
            ->groupBy(DB::raw('date_format(sell_date, "%Y-%m")'))
            ->orderBy('date', 'desc')
            ->limit(12)->get();

            // 월간 판매량, 매출
            $getMonthData = DB::table('sell_data as sd')
            ->select(DB::raw('date_format(sell_date, "%Y-%m-%d") as date'), DB::raw('count(*) as count'), DB::raw('sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales'))
            ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
            ->where('vd.vd_place', 'like', $place.'%')
            ->groupBy(DB::raw('date_format(sell_date, "%Y-%m-%d")'))
            ->orderBy('date', 'desc')
            ->limit(31)->get();

            // 일주일간 판매량, 매출
            $getWeekData = DB::table('sell_data as sd')
            ->select(DB::raw('date_format(sell_date, "%Y-%m-%d") as date'), DB::raw('count(*) as count'), DB::raw('sum(sd.coin_1000*1000+sd.coin_500*500+sd.coin_100*100) as getSales'))
            ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
            ->where('vd.vd_place', 'like', $place.'%')
            ->groupBy(DB::raw('date_format(sell_date, "%Y-%m-%d")'))
            ->orderBy('date', 'desc')
            ->limit(7)->get();
        }

        // 저장합니다
        $saveAll[0] = $getYearData;
        $saveAll[1] = $getMonthData;
        $saveAll[2] = $getWeekData;

        return $saveAll;
    }

    public function setLineChangeVerTwo($vd_id, $existingDrink, $changeDrink) {
        // 자판기의 보충기사 아이디를 가져온다.
        $getSpId = DB::table('supplementer as sp')->select('sp.sp_id')
        ->join('vendingmachine as vd', 'sp.sp_login_id', '=', 'vd.vd_supplementer')
        ->where('vd_id', $vd_id)->get();

        // 그 아이디를 가진 보충기사의 생성되어있는 작업지시서의 아이디를 가져온다.
        // select * from job_order where sp_id = 1 and jo_check = 0
        $getJobOrder = DB::table('job_order')
        ->where('sp_id', $getSpId[0]->sp_id)->where('jo_check', 0)->get();

        // select * from jo_column where jo_id = 56 and vd_id = 1
        $getJC = DB::table('jo_column')
        ->where('jo_id', $getJobOrder[0]->jo_id)->where('vd_id', $vd_id)->get();
       
        // 그 작업지시서에 해당 자판기가 없으면 작업지시서에 추가한 뒤 해당 라인에 추가한다.
        if (!isset($getJC[0])) {
            // 작업지시라능!
            $updateVd = DB::table('vendingmachine')
            ->where('vd_id', $vd_id)
            ->update(['vd_soldout' => 3]);

            // 정보 가져오기라능!
            $getJoColumnInfo = DB::table('vd_stock as vs')
            ->select('jo.jo_id', 'vs.vd_id', 'vd.vd_name', 'vd.vd_supplementer', 'pi.drink_name', 'pi.drink_img_path', DB::raw('vs.max_stock - vs.stock as sp_val'), 'vs.line')
            ->join('vendingmachine as vd', 'vd.vd_id', '=', 'vs.vd_id')
            ->join('product_info as pi', 'pi.drink_id', '=', 'vs.drink_id')
            ->join('supplementer as sm', 'sm.sp_login_id', '=', 'vd.vd_supplementer')
            ->join('job_order as jo', 'jo.sp_id', '=', 'sm.sp_id')
            ->where('vd.vd_id', $vd_id)->where('jo.jo_id', $getJobOrder[0]->jo_id)->get();

            $getDrinkName = DB::table('product_info')
            ->select('drink_name')->where('drink_id', $changeDrink)->get();

            // 값을 넣기라능!!
            for ($i = 0 ; $i < count($getJoColumnInfo) ; $i++) {
                if ($getJoColumnInfo[$i]->line == $existingDrink) {
                    DB::table('jo_column')->insert([
                        'jc_id'       => NULL,
                        'jo_id'       => $getJoColumnInfo[$i]->jo_id,
                        'vd_id'       => $getJoColumnInfo[$i]->vd_id,
                        'vd_name'     => $getJoColumnInfo[$i]->vd_name,
                        'sp_login_id' => $getJoColumnInfo[$i]->vd_supplementer,
                        'drink_name'  => $getJoColumnInfo[$i]->drink_name,
                        'drink_path'  => $getJoColumnInfo[$i]->drink_img_path,
                        'sp_val'      => $getJoColumnInfo[$i]->sp_val,
                        'drink_line'  => $getJoColumnInfo[$i]->line,
                        'note'        => $existingDrink."->".$getDrinkName,
                        'sp_check'    => 0
                    ]);
                    continue;
                }
                DB::table('jo_column')->insert([
                    'jc_id'       => NULL,
                    'jo_id'       => $getJoColumnInfo[$i]->jo_id,
                    'vd_id'       => $getJoColumnInfo[$i]->vd_id,
                    'vd_name'     => $getJoColumnInfo[$i]->vd_name,
                    'sp_login_id' => $getJoColumnInfo[$i]->vd_supplementer,
                    'drink_name'  => $getJoColumnInfo[$i]->drink_name,
                    'drink_path'  => $getJoColumnInfo[$i]->drink_img_path,
                    'sp_val'      => $getJoColumnInfo[$i]->sp_val,
                    'drink_line'  => $getJoColumnInfo[$i]->line,
                    'note'        => NULL,
                    'sp_check'    => 0
                ]);
            }
        } else {
            // 그 작업지시서에 해당 자판기가 있으면 그 해당 자판기 라인에 추가하고
            $updateVd = DB::table('vendingmachine')
            ->where('vd_id', $vd_id)
            ->update(['vd_soldout' => 3]);

            DB::table('jo_column')->where('jc_id', $getJC[$existingDrink-1]->jc_id)->update([
                'note'      => $existingDrink."->".$getDrinkName[0]->drink_name
            ]);
        }
    }

    public function setLineChangeNote($vd_id, $existingDrink, $changeDrink) {
        $getSpId = DB::table('supplementer as sp')->select('sp.sp_id')
            ->join('vendingmachine as vd', 'sp.sp_login_id', '=', 'vd.vd_supplementer')
            ->where('vd_id', $vd_id)->get();
        /* 
         * select * from job_order 
         * where date_format(order_date, "%Y-%m-%d") = 
         * date_sub(date_format(now(), "%Y-%m-%d"), interval -1 day)
        */ 
        $getJobOrder = DB::table('job_order')
        ->where(DB::raw('date_format(order_date, "%Y-%m-%d")'), DB::raw('date_sub(date_format(now(), "%Y-%m-%d"), interval -1 day)'))
        ->where('sp_id', $getSpId[0]->sp_id)->get();

        if (!isset($getJobOrder[0])){
            $tomorrow = date("Y-m-d", mktime(0,0,0,date("m") , date("d")+1, date("Y"))); 

            DB::table('job_order')->insert([
                'sp_id'      => $getSpId[0]->sp_id,
                'order_date' => $tomorrow." 07:00:00"
            ]);

            $getJobOrder = DB::table('job_order')
            ->where(DB::raw('date_format(order_date, "%Y-%m-%d")'), DB::raw('date_sub(date_format(now(), "%Y-%m-%d"), interval -1 day)'))
            ->where('sp_id', $getSpId[0]->sp_id)->get();
        } 

        $getJoColumn = DB::table('jo_column')
        ->where('jo_id', $getJobOrder[0]->jo_id)->where('vd_id', $vd_id)->get();

        if(!isset($getJoColumn[0])){
            /*
             * select sp.sp_login_id, vd.vd_id, pi.drink_name, vs.line, vs.max_stock-vs.stock as     * supplement, vd.vd_name, pi.drink_img_path
             * from supplementer sp                   
             * join vendingmachine vd                   on sp.sp_login_id = vd.vd_supplementer
             * join vd_stock vs                     on vd.vd_id = vs.vd_id
             * join stock_management sm                on vs.drink_id = sm.drink_id
             * join product_info pi                  on sm.drink_id = pi.drink_id
             * where vd.vd_id = 1;
             */
            $getJoColumnInfo = DB::table('supplementer as sp')
            ->select('sp.sp_login_id', 'vd.vd_id', 'pi.drink_name', 'vs.line', DB::raw('vs.max_stock-vs.stock as sp_val'), 'vd.vd_name', 'pi.drink_img_path')
            ->join('vendingmachine as vd', 'vd.vd_supplementer', '=', 'sp.sp_login_id')
            ->join('vd_stock as vs', 'vs.vd_id', '=', 'vd.vd_id')
            ->join('stock_management as sm', 'sm.drink_id', '=', 'vs.drink_id')
            ->join('product_info as pi', 'pi.drink_id', '=', 'sm.drink_id')
            ->where('vd.vd_id', $vd_id)->get();

            for ($i = 0 ; $i < count($getJoColumnInfo) ; $i++) {
                DB::table('jo_column')->insert([
                    'jc_id'       => NULL,
                    'jo_id'       => $getJobOrder[0]->jo_id,
                    'vd_id'       => $getJoColumnInfo[$i]->vd_id,
                    'vd_name'     => $getJoColumnInfo[$i]->vd_name,
                    'sp_login_id' => $getJoColumnInfo[$i]->sp_login_id,
                    'drink_name'  => $getJoColumnInfo[$i]->drink_name,
                    'drink_path'  => $getJoColumnInfo[$i]->drink_img_path,
                    'sp_val'      => $getJoColumnInfo[$i]->sp_val,
                    'drink_line'  => $getJoColumnInfo[$i]->line,
                    'note'        => NULL,
                    'sp_check'    => 0
                ]);
            }
            $getJoColumn = DB::table('jo_column')
            ->where('jo_id', $getJobOrder[0]->jo_id)->where('vd_id', $vd_id)->get();
        }

        /* 바꿀 친구 찾아오기
         * select jc_id, drink_id
         * from jo_column as jc
         * join product_info as pi on pi.drink_name = jc.drink_name
         * where jo_id = 31 and vd_id = 73 and drink_id = 1
         */
        $getJcId = DB::table('jo_column as jc')
        ->select('jc_id', 'drink_line')
        ->join('product_info as pi', 'pi.drink_name', '=', 'jc.drink_name')
        ->where('jo_id', $getJoColumn[0]->jo_id)
        ->where('vd_id', $vd_id)
        ->where('drink_id', $existingDrink)->get();

        $getDrinkName = DB::table('product_info')
        ->select('drink_name')->where('drink_id', $changeDrink)->get();

        $update = DB::table('jo_column')->where('jc_id', $getJcId[0]->jc_id)->update([
            'note'  => $getJcId[0]->drink_line.' -> '.$getDrinkName[0]->drink_name
        ]);
        $update = DB::table('vendingmachine')->where('vd_id', $vd_id)->update([
            'vd_soldout'    => 3
        ]);
    }
    public function pieGraphData(){
        /* 장소별 음료 판매량 
         * select pi.drink_name, count(*) as count
         * from sell_data as sd
         * join vendingmachine as vd on vd.vd_id = sd.vd_id
         * join product_info as pi on pi.drink_id = sd.drink_id
         * where vd.vd_place like "school%"
         * group by sd.drink_id
         * order by count desc
         */
        $getSchoolData = DB::table('sell_data as sd')
        ->select('pi.drink_name', DB::raw('count(*) as count'))
        ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->where('vd.vd_place', 'like', 'school%')
        ->groupBy('sd.drink_id')
        ->orderBy('count', 'desc')->get();

        $getParkData = DB::table('sell_data as sd')
        ->select('pi.drink_name', DB::raw('count(*) as count'))
        ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->where('vd.vd_place', 'like', 'park%')
        ->groupBy('sd.drink_id')
        ->orderBy('count', 'desc')->get();

        $getCompanyData = DB::table('sell_data as sd')
        ->select('pi.drink_name', DB::raw('count(*) as count'))
        ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->where('vd.vd_place', 'like', 'company%')
        ->groupBy('sd.drink_id')
        ->orderBy('count', 'desc')->get();

        $getHospitalData = DB::table('sell_data as sd')
        ->select('pi.drink_name', DB::raw('count(*) as count'))
        ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->where('vd.vd_place', 'like', 'hospital%')
        ->groupBy('sd.drink_id')
        ->orderBy('count', 'desc')->get();

        $saveArr = array();
        $saveArr[0] = $getSchoolData;
        $saveArr[1] = $getParkData;
        $saveArr[2] = $getCompanyData;
        $saveArr[3] = $getHospitalData;
        
        return $saveArr;
    }
    public function differenceVdAnalysis($vd_id, $date) {
        $saveAll = array();

        // 넘어오는 값에 따라 mysql 날짜 형식을 바꿔준다.
        switch($date){
            case 'year':
                $first = '365';
                $second = '730';
                break;
            case 'month':
                $first = '30';
                $second = '60';
                break;
            case 'week':
                $first = '8';
                $second = '15';
                break;
        }

        /**
         * select pi.drink_name, count(*)
         * from sell_data as sd
         * join product_info as pi on pi.drink_id = sd.drink_id
         * where sd.vd_id = 51
         * and date_format(sell_date, "%Y-%m-%d") > date_sub(date_format(date_sub(date_format  * (now(), "%Y-%m-%d"), interval 30 day), "%Y-%m-%d"), interval 60 day)
         * group by sd.vd_id, sd.drink_id
         */
        $getSalesRankLongTimeAgo = DB::table('sell_data as sd')
        ->select('pi.drink_name', DB::raw('count(*) as count'))
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->where('sd.vd_id', DB::raw($vd_id))
        ->where(DB::raw('date_format(sell_date, "%Y-%m-%d")'), ">", DB::raw('date_sub(date_format(date_sub(date_format(now(), "%Y-%m-%d"), interval '.$first.' day), "%Y-%m-%d"), interval '.$second.' day)'))
        ->groupBy(DB::raw('sd.vd_id, sd.drink_id'))->get();

        $getSalesRankNow = DB::table('sell_data as sd')
        ->select('pi.drink_name', DB::raw('count(*) as count'))
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->where('sd.vd_id', DB::raw($vd_id))
        ->where(DB::raw('date_format(sell_date, "%Y-%m-%d")'), '>', DB::raw('date_sub(date_format(now(), "%Y-%m-%d"), interval '.$first.' day)'))
        ->groupBy(DB::raw('sd.vd_id, sd.drink_id'))
        ->get();

        $saveAll[0] = $getSalesRankLongTimeAgo;
        $saveAll[1] = $getSalesRankNow;

        return $saveAll;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class realtimeController extends Controller
{
    // 자판기 리스트 가져오기
    public function vdList($local, $subLocal) {
        // 자판기 정보 전부 가져오기
        $getVdList  = DB::table('vendingmachine')->get();

        $result = array();
        $vd_latitude = array();           
        $vd_longitude = array();       
        $vd_address = array();

        // 모든 자판기 주소별로 나누기
        for ($x = 0 ; $x < count($getVdList) ; $x++) {
            // 주소 저장
            $vd_address[$x] = $getVdList[$x]->vd_address;
            if ($vd_address[$x] != null) {
                $vd_address[$x] = explode(", ", $vd_address[$x]);
            } else {
                $vd_address[$x] = 'not defined address';
            }
            // 주소가 넘어온 데이터와 같은 것을 저장한다.
            if ($vd_address[$x][0] == $subLocal && $vd_address[$x][1] == $local) {
                $result[$x]['vd_id']            = $getVdList[$x]->vd_id;
                $result[$x]['vd_latitude']      = $getVdList[$x]->vd_latitude;
                $result[$x]['vd_longitude']     = $getVdList[$x]->vd_longitude;
                $result[$x]['vd_name']          = $getVdList[$x]->vd_name;
                $result[$x]['vd_supplementer']  = $getVdList[$x]->vd_supplementer;
                $result[$x]['vd_address']       = $getVdList[$x]->vd_address;
                $result[$x]['vd_soldout']       = $getVdList[$x]->vd_soldout;
            } else if ($vd_address[$x][1] == $local && $subLocal == "all") {
                $result[$x]['vd_id']            = $getVdList[$x]->vd_id;
                $result[$x]['vd_latitude']      = $getVdList[$x]->vd_latitude;
                $result[$x]['vd_longitude']     = $getVdList[$x]->vd_longitude;
                $result[$x]['vd_name']          = $getVdList[$x]->vd_name;
                $result[$x]['vd_supplementer']  = $getVdList[$x]->vd_supplementer;
                $result[$x]['vd_address']       = $getVdList[$x]->vd_address;
                $result[$x]['vd_soldout']       = $getVdList[$x]->vd_soldout;
            } else if ($local == "all" && $subLocal == "all"){
                $result[$x]['vd_id']            = $getVdList[$x]->vd_id;
                $result[$x]['vd_latitude']      = $getVdList[$x]->vd_latitude;
                $result[$x]['vd_longitude']     = $getVdList[$x]->vd_longitude;
                $result[$x]['vd_name']          = $getVdList[$x]->vd_name;
                $result[$x]['vd_supplementer']  = $getVdList[$x]->vd_supplementer;
                $result[$x]['vd_address']       = $getVdList[$x]->vd_address;
                $result[$x]['vd_soldout']       = $getVdList[$x]->vd_soldout;
            }
        }
        return $result;
    }

    // 판매데이터 가져오기
    public function getSellData() {
        // 판매데이터 가져오기.
        $getSellData = DB::table('sell_data')
            ->select('sell_data.sell_date', 'product_info.drink_name',
                      DB::raw('count(\'product_info.drink_name\') as count'))
            ->join('product_info', 'sell_data.drink_id', '=', 'product_info.drink_id')
            ->groupBy('sell_data.sell_date')->groupBy('product_info.drink_name')
            ->having('sell_date', '>',  DB::raw('DATE_SUB(\''.date('Y-m-d').'\', INTERVAL 1 DAY)'))
            ->get();
            
        return $getSellData;
    }

    // 자판기의 음료 재고 가져오기
    public function getVdStock($vd_id) {
        // 음료 재고 가져오기
        $getVdStock = DB::table(DB::raw('vd_stock vs'))
        ->select(DB::raw('vs.vd_id, vs.line, vs.stock, vs.max_stock, pi.drink_name, pi.drink_back_path, sm.expiration_date, pi.sell_price'))
        ->join(DB::raw('stock_management sm'), 'vs.drink_id', '=', 'sm.drink_id')
        ->join(DB::raw('product_info pi'), 'sm.drink_id', '=', 'pi.drink_id')
        ->where('vs.vd_id', $vd_id)->get();
        
        return $getVdStock;
    }

    // 보충기사별 자판기 가져오기
    public function vdListSP($spName) {
        $getVdList = DB::table('vendingmachine')
        ->where('vd_supplementer', $spName)->get();

        return $getVdList;
    }

    // 동전 재고
    public function coinStock($vd_id){
        $getCoinStock = DB::table('vendingmachine')
        ->select(DB::raw('coin_100 as en100, coin_50 as en50, coin_10 as en10, 100*coin_100+50*coin_50+10*coin_10 as sum'))
        ->where('vd_id', $vd_id)->get();

        return $getCoinStock;
    }

    // 자판기 이름으로 검색
    public function searchVdName($vd_name) {
        $getVdList = DB::table('vendingmachine')
        ->where('vd_name', $vd_name)->get();

        return $getVdList;
    }

    // 판매 내역 리스트
    public function getSellDataList() {
        /* 판매 내역
         * select vd.vd_name, pi.drink_name, sd.sell_date, sd.coin_1000*1000+sd.coin_500*500            +sd.coin_100*100 as sell_price
         * from sell_data as sd
         * join vendingmachine as vd on vd.vd_id = sd.vd_id
         * join product_info as pi on pi.drink_id = sd.drink_id
         * where date_format(sd.sell_date, "%Y-%m-%d") = date_sub(date_format(now(), "%Y-%m-%d"),       interval 1 day)
         * order by sd.sell_date desc
         */
        $getSellData = DB::table('sell_data as sd')
        ->select('vd.vd_name', 'pi.drink_name', 'sd.sell_date', DB::raw('sd.sell_date, sd.coin_100*100+sd.coin_50*50+sd.coin_10*10 as sell_price'))
        ->join('vendingmachine as vd', 'vd.vd_id', '=', 'sd.vd_id')
        ->join('product_info as pi', 'pi.drink_id', '=', 'sd.drink_id')
        ->where(DB::raw('date_format(sd.sell_date, "%Y-%m-%d")'), DB::raw('date_sub(date_format(now(), "%Y-%m-%d"), interval 3 day)'))
        ->orderBy('sd.sell_date', 'desc')->get();
            
        return $getSellData;
    }

   public function clickAButton($vd_soldout) {
        /* OO자판기 보기 버튼을 누르면
         * select *
         * from vendingmachine
         * where vd_soldout = x;
         */
        if ($vd_soldout == 0) {
            // 전체 버튼을 누르면
            $getVd = DB::table('vendingmachine')->get();
        } else {
            $getVd = DB::table('vendingmachine')
            ->where('vd_soldout', $vd_soldout)->get();
        }
        
        return $getVd;
    }

    public function getNumOfVd() {
        /* 자판기 주소에 맞는 개수와 매진임박 자판기 개수
         * select SUBSTRING_INDEX(vd_address, ', Republic of Korea', 1), count(vd_id), vd_soldout
         * from vendingmachine
         * group by vd_address, vd_soldout
         */
        $array = array();
        $getNumOfVd = DB::table('vendingmachine')
        ->select(DB::raw('SUBSTRING_INDEX(vd_address, ", Republic of Korea", 1) as address'), DB::raw('count(vd_id) as count'), 'vd_soldout')
        ->groupBy('vd_address', 'vd_soldout')->get();
        
        $count = 0;
        // vd_soldout = 1 이 없는 경우 만들어서 배열에 넣어준다 
        for($x = 0 ; $x < count($getNumOfVd) ; $x++) {
            // 마지막 값이 하나이면 마지막에 하나의 값을 만들어서 넣어준다.
            if (!isset($getNumOfVd[$x+1]) && $getNumOfVd[$x]->address != $getNumOfVd[$x-1]->address) {
                $array[$count] = $getNumOfVd[$x];
                $count++;
                $array[$count] = (object) array(
                    'address'    => $getNumOfVd[$x]->address,
                    'count'      => 0,
                    'vd_soldout' => 1
                );
            } else if ($getNumOfVd[$x]->address == $getNumOfVd[$x+1]->address) { // 값이 두개 일 때
                $array[$count] = $getNumOfVd[$x];
                $count++;
                $x++;
                $array[$count] = $getNumOfVd[$x];
                $count++;
            } else { //  값이 하나 일 때
                $array[$count] = $getNumOfVd[$x];
                $count++;
                $array[$count] = (object) array(
                    'address'    => $getNumOfVd[$x]->address,
                    'count'      => 0,
                    'vd_soldout' => 1
                );
            $count++;
            }
        }

        for ($x = 0 ; $x < count($array) ; $x++) {
            $array[$x]->count = $array[$x]->count+$array[$x+1]->count;
            $x++;
        }
        return $array;
    }
    
    //작업 지시 내리기 
    public function addJobOrderVerTwo(Request $inputNote) {
        
        $vd_id= $inputNote->get('ven_id'); 
        $note= $inputNote->get('ven_note'); 
        
          \Log::info($vd_id);
          
          \Log::info($note);
          

        // 작업지시를 내리는 것..

        // 작업지시를 내리면 현재 생성안된 작업지시서를 찾아서 가져온다.
        // 그러려면 일단 해당 자판기의 보충기사 아이디를 가져온 뒤 찾는다.
        $getSpId = DB::table('supplementer')
        ->select('sp_id')->join('vendingmachine', 'vd_supplementer', '=', 'sp_login_id')
        ->where('vd_id', $vd_id)->get();

        // select * from job_order where sp_id = 1 and jo_check = 0
        $getJobOrder = DB::table('job_order')
        ->where('sp_id', $getSpId[0]->sp_id)->where('jo_check', 0)->get();

        // 만약 없다면 작업지시서에 해당 자판기의 칼럼들을 추가한 뒤 1번라인에 노트를 추가한다.
        // select * from jo_column where jo_id = 56 and vd_id = 1
        $getJC = DB::table('jo_column')
        ->where('jo_id', $getJobOrder[0]->jo_id)->where('vd_id', $vd_id)->get();

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

            // 값을 넣기라능!!
            for ($x = 0 ; $x < count($getJoColumnInfo) ; $x++) {
                if ($x == 0) {
                    DB::table('jo_column')->insert([
                        'jc_id'       => NULL,
                        'jo_id'       => $getJoColumnInfo[$x]->jo_id,
                        'vd_id'       => $getJoColumnInfo[$x]->vd_id,
                        'vd_name'     => $getJoColumnInfo[$x]->vd_name,
                        'sp_login_id' => $getJoColumnInfo[$x]->vd_supplementer,
                        'drink_name'  => $getJoColumnInfo[$x]->drink_name,
                        'drink_path'  => $getJoColumnInfo[$x]->drink_img_path,
                        'sp_val'      => $getJoColumnInfo[$x]->sp_val,
                        'drink_line'  => $getJoColumnInfo[$x]->line,
                        'note'        => $note,
                        'sp_check'    => 0
                    ]);
                    continue;
                }
                DB::table('jo_column')->insert([
                    'jc_id'       => NULL,
                    'jo_id'       => $getJoColumnInfo[$x]->jo_id,
                    'vd_id'       => $getJoColumnInfo[$x]->vd_id,
                    'vd_name'     => $getJoColumnInfo[$x]->vd_name,
                    'sp_login_id' => $getJoColumnInfo[$x]->vd_supplementer,
                    'drink_name'  => $getJoColumnInfo[$x]->drink_name,
                    'drink_path'  => $getJoColumnInfo[$x]->drink_img_path,
                    'sp_val'      => $getJoColumnInfo[$x]->sp_val,
                    'drink_line'  => $getJoColumnInfo[$x]->line,
                    'note'        => NULL,
                    'sp_check'    => 0
                ]);
            }
        } else {
            // 만약 해당 작업지시서에 해당 자판기가 작업지시가 되어있으면 1번라인에 노트를 추가한다.
            $updateVd = DB::table('vendingmachine')
            ->where('vd_id', $vd_id)
            ->update(['vd_soldout' => 3]);

            DB::table('jo_column')->where('jc_id', $getJC[0]->jc_id)->update([
                'note'      => $note
            ]);
        }

        return "good";
    }


    public function sendDataFromVdVersionTwo($vd_id, $line) {
        /*
         * select sp.sp_id
         * from vendingmachine as vd
         * join supplementer as sp on sp.sp_login_id = vd.vd_supplementer
         * where vd.vd_id = $vd_id
         */
        $getSpId = DB::table('vendingmachine as vd')->select('sp.sp_id')
        ->join('supplementer as sp', 'sp.sp_login_id', '=', 'vd.vd_supplementer')
        ->where('vd.vd_id', $vd_id)->get();

        // 재고 -1
        $update = DB::table('vd_stock')
        ->where('vd_id', $vd_id)
        ->where('line', $line)
        ->decrement('stock', 1);

        // 넣을 작업지시서 찾기
        $findJobOrder = DB::table('job_order')->where('sp_id', $getSpId[0]->sp_id)
        ->where('jo_check', 0)->get();

        // 해당 라인의 재고를 가져온다.
        $getData = DB::table('vd_stock')
        ->select('stock')
        ->where('vd_id', $vd_id)
        ->where('line', $line)->get();

        // 재고가 0이면
        if ($getData[0]->stock == 0) {
            // 매진 자판기로 만듬
            $updateVd = DB::table('vendingmachine')
            ->where('vd_id', $vd_id)
            ->update(['vd_soldout' => 2]);
        }

        // 해당 자판기의 매진임박여부를 확인 하기위해..
        $getVd = DB::table('vendingmachine')
        ->select('vd_soldout')
        ->where('vd_id', $vd_id)->get();

        if ($getData[0]->stock < 4 && $getVd[0]->vd_soldout != 1 && $getVd[0]->vd_soldout != 2     && $getVd[0]->vd_soldout != 3){
            // 매진임박 자판기로 만듬
            $updateVd = DB::table('vendingmachine')
            ->where('vd_id', $vd_id)
            ->update(['vd_soldout' => 1]);

            $getJoColumnInfo = DB::table('vd_stock as vs')
            ->select('jo.jo_id', 'vs.vd_id', 'vd.vd_name', 'vd.vd_supplementer', 'pi.drink_name', 'pi.drink_img_path', DB::raw('10 - vs.stock as sp_val'), 'vs.line')
            ->join('vendingmachine as vd', 'vd.vd_id', '=', 'vs.vd_id')
            ->join('product_info as pi', 'pi.drink_id', '=', 'vs.drink_id')
            ->join('supplementer as sm', 'sm.sp_login_id', '=', 'vd.vd_supplementer')
            ->join('job_order as jo', 'jo.sp_id', '=', 'sm.sp_id')
            ->where('vd.vd_id', $vd_id)->where('jo.jo_id', $findJobOrder[0]->jo_id)->get();

            for ($x = 0 ; $x < count($getJoColumnInfo) ; $x++) {
                DB::table('jo_column')->insert([
                    'jc_id'       => NULL,
                    'jo_id'       => $getJoColumnInfo[$x]->jo_id,
                    'vd_id'       => $getJoColumnInfo[$x]->vd_id,
                    'vd_name'     => $getJoColumnInfo[$x]->vd_name,
                    'sp_login_id' => $getJoColumnInfo[$x]->vd_supplementer,
                    'drink_name'  => $getJoColumnInfo[$x]->drink_name,
                    'drink_path'  => $getJoColumnInfo[$x]->drink_img_path,
                    'sp_val'      => $getJoColumnInfo[$x]->sp_val,
                    'drink_line'  => $getJoColumnInfo[$x]->line,
                    'note'        => NULL,
                    'sp_check'    => 0
                ]);
            }
        } else {
            // update 하기~~
            $update = DB::table('jo_column')->where('drink_line', $line)->where('vd_id', $vd_id)->where('jo_id', $findJobOrder[0]->jo_id)->increment('sp_val', 1);
        }

        $getDrinkId = DB::table('vd_stock')->select('drink_id')
            ->where('vd_id', $vd_id)->where('line', $line)->get();

        DB::table('sell_data')->insert([
            'vd_id'     => $vd_id,
            'line'      => $line,
            'drink_id'  => $getDrinkId[0]->drink_id,
            'coin_50'  => 1,
            'coin_10'  => 3
        ]);
    }

    // 판매하기
    public function sell(){
        for ($x = 0 ; $x < 3000 ; $x++) {
            $vd_id = rand(1, 122);
            $line = rand(1, 8);
            
            realtimeController::sendDataFromVdVersionTwo($vd_id, $line); 
        }
    }

    // nfc대면 보충 완료
    public function supplementOK($vd_id) {
        
        // // //vd stock update 쿼리 생성 및 실행
        $result = DB::table('vd_stock')
            ->where('vd_id', $vd_id)
            ->update(['stock' => 10]);
    
        //vendingmachine update 쿼리 생성 및 실행
        $result = DB::table('vendingmachine')
            ->where('vd_id', $vd_id)->update(['vd_soldout' => 0]);
    
        //Jo_column Update 쿼리 생성 및 실행
        $result = DB::table('jo_column')
            ->where('vd_id', $vd_id)->update(['sp_check' => 1]);
    
        if($result) return 'good';
        else return 'fail';
    }

    public function newData() {
        // for ($x = 0 ; $x < 3000 ; $x++) {
        //     $randYear = rand(2017, 2018);
        //     $randMonth = 0;
        //     $randDay = 0;
        //     if ($randYear == 2018) {
        //         $randMonth = rand(1, 6);
        //         if ($randMonth == 6)
        //             $randDay = rand(1, 25);
        //         else
        //             $randDay = rand(1, 30);
        //     } else {
        //         $randMonth = rand(1, 12);
        //         $randDay = rand(1, 30);
        //     }

        //     $date = $randYear."-".$randMonth."-".$randDay." 00:00:00";
        //     $vd_id = rand(1, 122);
        //     $line = rand(1, 8);

        //     $getDrinkId = DB::table('vd_stock')
        //     ->select('drink_id')
        //     ->where('vd_id', $vd_id)
        //     ->where('line', $line)->get();

        //     DB::table('sell_data')->insert([
        //         'vd_id'     => $vd_id,
        //         'line'      => $line,
        //         'drink_id'  => $getDrinkId[0]->drink_id,
        //         'sell_date' => $date,
        //         'coin_1000' => 0,
        //         'coin_500'  => 1,
        //         'coin_100'  => 3
        //     ]);
        // }
        for ($x = 0 ; $x < 3000 ; $x++) {
            $randYear = 2018;
            $randMonth = 0;
            $randDay = 0;

            $randMonth = rand(5, 6);

            if ($randMonth == 6)
                $randDay = rand(1, 25);
            else if ($randMonth == 5)
                $randDay = rand(1, 31);

            $date = $randYear."-".$randMonth."-".$randDay." 00:00:00";
            $vd_id = rand(1, 122);
            $line = rand(1, 8);

            $getDrinkId = DB::table('vd_stock')
            ->select('drink_id')
            ->where('vd_id', $vd_id)
            ->where('line', $line)->get();

            DB::table('sell_data')->insert([
                'vd_id'     => $vd_id,
                'line'      => $line,
                'drink_id'  => $getDrinkId[0]->drink_id,
                'sell_date' => $date,
                'coin_100' => 0,
                'coin_50'  => 1,
                'coin_10'  => 3
            ]);
        }

    }

}
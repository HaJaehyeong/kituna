<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use File;

class managementController extends Controller
{
    //
    public function getSP() {
        $getSP = DB::table('supplementer')->get();

        return $getSP;
    }

    //<------------------- 보충기사 담당자판기 ----------------------->
    public function getVdInfo($sp_name){
        /* 자판기 정보 가져오기 
         * select vd.vd_id, vd.vd_name, vs.line, pi.drink_name, vs.stock
         * from vd_stock as vs
         * join vendingmachine as vd on vd.vd_id = vs.vd_id
         * join product_info as pi on pi.drink_id = vs.drink_id
         * where vd_supplementer = "hajae"
         */
        $getVdInfo = DB::table('vd_stock as vs')
        ->select('vd.vd_id', 'vd.vd_name', 'vs.line', 'pi.drink_name', 'vs.stock')
        ->join('vendingmachine as vd', 'vd.vd_id', '=', 'vs.vd_id')
        ->join('product_info as pi', 'pi.drink_id', '=', 'vs.drink_id')
        ->where('vd_supplementer', $sp_name)
        ->get();

        return $getVdInfo;
    }
    //<------------------- 보충기사 담당자판기 ----------------------->


    //<------------------- 작업지시서 정보 ----------------------->
    public function getJobOrder($spName, $order_date) {

        $getJobOrder = DB::table(DB::raw('jo_column jc'))
        ->select(DB::raw('jc.jc_id, jo.jo_id, jc.vd_id, jc.vd_name, jc.sp_login_id, jc.drink_name, jc.drink_path, jc.sp_val, jc.drink_line, jc.note, jc.sp_check'))
        ->join(DB::raw('job_order jo'), 'jo.jo_id', '=', 'jc.jo_id')
        ->join(DB::raw('supplementer sp'), 'sp.sp_id', '=', 'jo.sp_id')
        ->where('sp.sp_login_id', $spName)
        ->where('jo.order_date', 'like', $order_date.'%')->get();

        return $getJobOrder;
    }
    //<------------------- 작업지시서 생성 ----------------------->


    //coin Stock
    public function coinStock($vd_id){
        $getCoinStock = DB::table('coin_stock as cs')
        ->select(DB::raw('cs.1000 as won1000, cs.500 as won500, cs.100 as won100, 1000*cs.1000+500*cs.500+100*cs.100 as sum'))
        ->where('vd_id', $vd_id)->get();

        return $getCoinStock;
    }
    
    // 자판기 등록
    public function registrationVD(Request $input) {
        $vd_name= $input->get('ven_name'); 
        $vd_address= $input->get('ven_Location'); 
        $vd_supplementer= $input->get('ven_Manager'); 
        $drink_arr= json_decode($input->get('drink_line'), true); 
        $vd_latitude= $input->get('clickLat'); 
        $vd_longitude= $input->get('clickLng'); 
  
          \Log::info($drink_arr["Line1"]);
          \Log::info($drink_arr["Line2"]);
          \Log::info($drink_arr["Line3"]);
          \Log::info($drink_arr["Line4"]);
          \Log::info($drink_arr["Line5"]);
          \Log::info($drink_arr["Line6"]);
          \Log::info($drink_arr["Line7"]);
          
        
        /**
         * insert into vendingmachine(vd_name, vd_latitude, vd_longitude, vd_address, vd_place,                                  vd_supplementer, vd_soldout, money_in_vd, out_money) 
         *                     valuse('', , , '', '', '', 0, 0, 0);
         */
           $result = DB::table('vendingmachine')->insert([
             'vd_name'           => $vd_name,
             'vd_latitude'       => $vd_latitude,
             'vd_longitude'      => $vd_longitude,
             'vd_address'        => $vd_address,
             'vd_place'          => 0,
             'vd_supplementer'   => $vd_supplementer,
             'vd_soldout'        => 0,
             'money_in_vd'       => 0,
             'out_money'         => 0
         ]); 
 

         // 방금 등록한 자판기의 id가져오기
          $getLastVdId = DB::table('vendingmachine')->select('vd_id')
           ->orderBy('vd_id', 'desc')->limit(1)->get();
           
           //마지막 등록한 음료의 count값
        $getLastCountVal = DB::table('vd_stock')->select('count')
        ->orderBy('count', 'desc')->limit(1)->get();

          \Log::info($getLastCountVal[0]->count);
          

          for ($i = 1 ; $i < 9 ; $i++) {
            
              $result = DB::table('vd_stock')->insert([
                  'vd_id'     => $getLastVdId[0]->vd_id,
                  'drink_id'  => $drink_arr["Line$i"]+1,
                  
                  'stock'     => 0,
                  'max_stock' => 35,
                  'line'      => $i,
                  'count'     => $getLastCountVal[0]->count + $i
            
             ]); 
          } 
          
        // 내일 날짜
        $tomorrow = date("Y-m-d", mktime(0,0,0,date("m") , date("d")+1, date("Y"))); 
//기사번호가져오기
        $getSpId = DB::table('supplementer as sp')
        ->select('sp.sp_id')
        ->join('vendingmachine as vd', 'sp.sp_login_id', '=', 'vd.vd_supplementer')
        ->where('vd.vd_id', $getLastVdId[0]->vd_id)->get();

        
        // 작업지시서가 있는지 먼저 확인해야 한다.
        /* select * 
        from job_order 
        where substring(order_date, 1, 10) = substring(date_add(now(), interval +1 day), 1, 10)
        and sp_id = ?;
        */
        // 생성된 작업지시서나 생성되어있던 작업지시서 가져오기
        $getJO = DB::table('job_order')
            ->where(DB::raw('substring(order_date, 1, 10)'), 
            DB::raw('substring(date_add(now(), interval +1 day), 1, 10)'))
            ->where('sp_id', $getSpId[0]->sp_id)->get();

        /* 작업지시서에 추가하기
         * insert into job_order(jo_id, sp_id, order_date) valuse(null, sp_id, tomorrow);
         */
        if (!isset($getJO[0])) {
            $addJobOrder = DB::table('job_order')->insert([
                'jo_id'         => null,
                'sp_id'         => $getSpId[0]->sp_id,
                'order_date'    => $tomorrow." 07:00:00"
            ]);
        } 

        $getJO = DB::table('job_order')
            ->where(DB::raw('substring(order_date, 1, 10)'), 
            DB::raw('substring(date_add(now(), interval +1 day), 1, 10)'))
            ->where('sp_id', $getSpId[0]->sp_id)->get();

        $getJC = DB::table('jo_column')->select('jc_id')
        ->where('jo_id', $getJO[0]->jo_id)->where('vd_id', $getLastVdId[0]->vd_id)->get();

        if (!isset($getJC[0])) {
            $getJoColumnInfo =  DB::table('job_order')
            ->select(DB::raw('supplementer.sp_login_id as sp_login_id, vendingmachine.vd_id as vd_id, product_info.drink_name as drink_name, job_order.jo_id as jo_id, vd_stock.max_stock-vd_stock.stock as sp_val, vendingmachine.vd_name as vd_name,
            vd_stock.line as line, product_info.drink_img_path as drink_path'))
            ->join('supplementer', 'job_order.sp_id', '=', 'supplementer.sp_id')
            ->join('vendingmachine', 'supplementer.sp_login_id', '=','vendingmachine.vd_supplementer')
            ->join('vd_stock', 'vendingmachine.vd_id', '=', 'vd_stock.vd_id')
            ->join('stock_management', 'vd_stock.drink_id', '=', 'stock_management.drink_id')
            ->join('product_info', 'stock_management.drink_id', '=', 'product_info.drink_id')
            ->where('vendingmachine.vd_id', $getLastVdId[0]->vd_id)->where('job_order.jo_id', $getJO[0]->jo_id)
            ->get();
    
            // jo_column 작업지시서에 각 라인마다 35개씩 max값 추가 된다.
            for ($i = 0 ; $i < count($getJoColumnInfo) ; $i++) {
                $result = DB::table('jo_column')->insert([
                    'jc_id'       => NULL,
                    'jo_id'       => $getJoColumnInfo[$i]->jo_id,
                    'vd_id'       => $getJoColumnInfo[$i]->vd_id,
                    'vd_name'     => $getJoColumnInfo[$i]->vd_name,
                    'sp_login_id' => $getJoColumnInfo[$i]->sp_login_id,
                    'drink_name'  => $getJoColumnInfo[$i]->drink_name,
                    'drink_path'  => $getJoColumnInfo[$i]->drink_path,
                    'sp_val'      => 35,
                    'drink_line'  => $getJoColumnInfo[$i]->line,
                    'note'        => NULL,
                    'sp_check'    => 0
                ]);
            }
            // 자판기 상태를 보충필요자판기로 바꿈
                    $result = DB::table('vendingmachine')
                    ->where('vd_id',$vd_id)->update([
                        'vd_soldout'        => 1
                    ]);
        }




         
          // insert 성공하면 good반환 실패하면 fail반환
          if ($result) {
              return 'good';
          } else {
              return 'fail';
          }
     } 

    // // 하나의 자판기 정보가져오기
    // public function getVD($vd_id) {
    //     $result = DB::table('vendingmachine')->where('vd_id', $vd_id)->get();

    //     return $result;
    // }

     // 자판기 수정
     public function updateVD(Request $update) {
     
         $vd_name= $update->get('ven_name'); 
         $vd_id= $update->get('ven_no'); 
         $vd_address= $update->get('ven_Location'); 
         $vd_supplementer= $update->get('ven_Manager'); 
         $drink_arr= json_decode($update->get('drink_line'), true); 
            
        
     // 업데이트문
         $result = DB::table('vendingmachine')->
                     where('vd_id', $vd_id)->update([
                         'vd_name'           => $vd_name,
                         'vd_address'        => $vd_address,
                         'vd_supplementer'   => $vd_supplementer
                     ]);


        //수정 전 음료 아이디 drink_id_before
        $drink_id_before = array();

        // 수정 전 음료 갯수 vd_stock_before, 
        $vd_stock_before = array();

        $get_stock_result = DB::table('vd_stock')->where('vd_id', $vd_id)->get();
        foreach ($get_stock_result as $temp) {
            array_push($vd_stock_before, $temp-> stock);
            array_push($drink_id_before, $temp-> drink_id);
        }

        // 해당 자판기의 보충기사 번호 가져오기  // $getSpId[0]->sp_id
        $getSpId = DB::table('supplementer as sp')
            ->select('sp.sp_id')
            ->join('vendingmachine as vd', 'sp.sp_login_id', '=', 'vd.vd_supplementer')
            ->where('vd.vd_id', $vd_id)->get();
    
        // 내일 날짜
        $tomorrow = date("Y-m-d", mktime(0,0,0,date("m") , date("d")+1, date("Y"))); 

        // 작업지시서가 있는지 확인
        $getJO = DB::table('job_order')
        ->where(DB::raw('substring(order_date, 1, 10)'), 
                DB::raw('substring(date_add(now(), interval +1 day), 1, 10)'))
        ->where('sp_id', $getSpId[0]->sp_id)->get();

        // 작업지시서가 없으면 생성한다.
        if (!isset($getJO[0])) {
            $addJobOrder = DB::table('job_order')->insert([
                'jo_id'         => null,
                'sp_id'         => $getSpId[0]->sp_id,
                'order_date'    => $tomorrow." 07:00:00"
            ]);
        } 

        // 생성된 작업지시서나 생성되어있던 작업지시서 가져오기
        $getJO = DB::table('job_order')
        ->where(DB::raw('substring(order_date, 1, 10)'), 
                DB::raw('substring(date_add(now(), interval +1 day), 1, 10)'))
        ->where('sp_id', $getSpId[0]->sp_id)->get();

        // 해당 자판기가 작업지시서에 등록 되어 있는지 확인한다.
        $getJC = DB::table('jo_column')->select('jc_id')
         ->where('jo_id', $getJO[0]->jo_id)->where('vd_id', $vd_id)->get();
       
        // 만약 작업지시서에 등록이 안된 자판기라면 작업지시서에 등록한다.
        if (!isset($getJC[0])) {
            
            $getJoColumnInfo = DB::table('vd_stock as vs')
            ->select('jo.jo_id', 'vs.vd_id', 'vd.vd_name', 'vd.vd_supplementer', 'pi.drink_name', 'pi.drink_img_path', DB::raw('vs.max_stock - vs.stock as sp_val'), 'vs.line')
            ->join('vendingmachine as vd', 'vd.vd_id', '=', 'vs.vd_id')
            ->join('product_info as pi', 'pi.drink_id', '=', 'vs.drink_id')
            ->join('supplementer as sm', 'sm.sp_login_id', '=', 'vd.vd_supplementer')
            ->join('job_order as jo', 'jo.sp_id', '=', 'sm.sp_id')
            ->where('vd.vd_id', $vd_id)->where('jo.jo_id', $getJO[0]->jo_id)->get();
    
            // 작업지시서 추가
            for ($i = 1 ; $i < count($drink_arr)+1 ; $i++) {
                
                // 작업지시서에 추가 될 문자열
                $addNote = "";
                
                
                // 현재 라인의 음료와 바꿀 음료가 다르면 
                if( $drink_id_before[$i-1] != $drink_arr["Line$i"]+1 ){

                    $get_drink_name = DB::table('product_info')->where('drink_id', $drink_arr["Line$i"]+1)->get();
                    $addNote .= (string)($i+0)."->".$get_drink_name[0]->drink_name;
                    
                    $result = DB::table('jo_column')->insert([
                        'jc_id'       => NULL,
                        'jo_id'       => $getJoColumnInfo[$i-1]->jo_id,
                        'vd_id'       => $getJoColumnInfo[$i-1]->vd_id,
                        'vd_name'     => $getJoColumnInfo[$i-1]->vd_name,
                        'sp_login_id' => $getJoColumnInfo[$i-1]->vd_supplementer,
                        'drink_name'  => $getJoColumnInfo[$i-1]->drink_name,
                        'drink_path'  => $getJoColumnInfo[$i-1]->drink_img_path,
                        'sp_val'      => $getJoColumnInfo[$i-1]->sp_val,
                        'drink_line'  => $getJoColumnInfo[$i-1]->line,
                        'note'        => $addNote,
                        'sp_check'    => 0
                    ]);

                    // 자판기 상태를 작업지시자판기로 바꿈
                    $result = DB::table('vendingmachine')
                    ->where('vd_id', $vd_id)->update([
                        'vd_soldout'        => 3
                    ]);
                }else{
                    $result = DB::table('jo_column')->insert([
                        'jc_id'       => NULL,
                        'jo_id'       => $getJoColumnInfo[$i-1]->jo_id,
                        'vd_id'       => $getJoColumnInfo[$i-1]->vd_id,
                        'vd_name'     => $getJoColumnInfo[$i-1]->vd_name,
                        'sp_login_id' => $getJoColumnInfo[$i-1]->vd_supplementer,
                        'drink_name'  => $getJoColumnInfo[$i-1]->drink_name,
                        'drink_path'  => $getJoColumnInfo[$i-1]->drink_img_path,
                        'sp_val'      => $getJoColumnInfo[$i-1]->sp_val,
                        'drink_line'  => $getJoColumnInfo[$i-1]->line,
                        'note'        => null,
                        'sp_check'    => 0
                    ]);
                }
            }
        }else{
            // 이미 해당 자판기에 대한 작업지시서가 있으면 업데이트를 한다.
            
            // 업데이트
            for ($i = 1 ; $i < count($drink_arr)+1; $i++) {
                
                // 작업지시서에 추가 될 문자열
                $addNote = "";
                
                // 현재 라인의 음료와 바꿀 음료가 다르면 
                if( $drink_id_before[$i-1] != $drink_arr["Line$i"]+1){
                   
                    $get_drink_name = DB::table('product_info')->where('drink_id', $drink_arr["Line$i"]+1)->get();
                    $addNote .= (string)($i+0)."->".$get_drink_name[0]->drink_name;
                    
                    // 업데이트
                    $result = DB::table('jo_column')
                    ->where('vd_id', $vd_id)
                    ->where('jo_id', $getJO[0]->jo_id)
                    ->where('drink_line', $i)->update([
                        'note'        => $addNote
                    ]);

                    // 자판기 상태를 작업지시자판기로 바꿈
                    $result = DB::table('vendingmachine')
                    ->where('vd_id',$vd_id)->update([
                        'vd_soldout'        => 3
                    ]);
                }
            }
        }
        
        return "good";
        
    }

    // 자판기 삭제
    public function deleteVD(Request $delete) {

        $vd_id= $delete->get('ven_id'); 

        $result = DB::table('vendingmachine')->where('vd_id', $vd_id)->delete();

        
        // delete 성공하면 good반환 실패하면 fail반환
        if ($result) {
            // 성공하면 autoincrement -1 
            $max = DB::table('vendingmachine')->max('vd_id')+1; 
            DB::statement("ALTER TABLE vendingmachine AUTO_INCREMENT =  $max");
            return 'good';
        } else {
            return 'fail';
        }
    }
    
    //음료 리스트 가져오기    
    public function getProductInfo() {
        $getProductInfo = DB::table('product_info')->get();

        return $getProductInfo;
    }

    // 자판기 수정 클릭 시 내부 라인의 정보 가져오기
   public function getVdStock($vd_id) {

        $result = DB::table('vd_stock as vs')
            ->select('vs.drink_id', 'vs.line', 'vs.stock', 'vs.max_stock', 'pi.drink_img_path')
            ->join('product_info as pi', 'vs.drink_id', '=', 'pi.drink_id')
            ->where('vd_id', $vd_id)->get();
        
        return $result;
    }

    //<-------------------- 작업지시 ------------------------>
    public function addJobOrderVerTwo(Request $inputNote) {
        
        $vd_id= $inputNote->get('ven_id'); 
        $note= $inputNote->get('ven_note'); 
        
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
            for ($i = 0 ; $i < count($getJoColumnInfo) ; $i++) {
                if ($i == 0) {
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
                        'note'        => $note,
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
    //<-------------------- 작업지시 ------------------------>

    public function getJoInfo($jo_id) {
        
        // 하루 작업지시서의 비고란
        $jo_column_note_string = array();
    
        // 작업지시서 한 페이지의 정보를 가져옴
        $jo_column_note = DB::table('jo_column')
        ->where('jo_id', $jo_id)->get();
        
        $count = 0;
        $addNote = "";
        foreach ($jo_column_note as $temp) {
            
            $count++;
    
            // 라인변경 지시가 있으면 String에 추가
            if($temp-> note != null){
                $addNote .= $temp-> note.",";    
            }
    
            // 자판기 한대의 작업지시를 한줄로 만들기
            if($count % 8 == 0){
    
                // 라인 변경 지시 String추가
                $addNote = substr($addNote, 0, -1);
                $addNote .= "(pls line change)";
    
                if($addNote == "(pls line change)"){
                    $addNote = null;
                }
    
                // 자판기 특정 지시 String추가
                $get_vd_info = DB::table('vendingmachine')
                ->where('vd_id', $temp-> vd_id)->get();
    
                if($get_vd_info[0]->note != null){
                    $addNote .= " *".$get_vd_info[0]->note;
                }
    
                // 작업지시서 한줄을 만드는 배열에 추가
                array_push($jo_column_note_string, $addNote);
                $addNote = "";
            }
        }
    
        return $jo_column_note_string;
    }


    //----------------------- 보충기사 등록 -------------------------------
    public function addSP(Request $request) {

        $fileName = $request->get("sp_login_id");

        if(!file_exists(public_path('\images\supplementer'))){
            File::makeDirectory(public_path('\images\supplementer'));
        }
        // 해당 폴더가 없으면 생성
        
        if(file_exists(public_path('\images\supplementer\\'.$fileName.'.png'))){
            // 같은 이름의 제품 이미지 파일이 있는지 확인

            return "fail";
        }
        else {
            $imageFile = $request->file("imageFile");                                    // 파일 
            $imageFile->storeAs('\images\supplementer', $fileName.'.png');               // 파일 저장
            
            $sp_login_id = $request->get("sp_login_id");
            $sp_password = $request->get("sp_password");
            $sp_name = $request->get("sp_name");
            $sp_mail = $request->get("sp_mail");
            $sp_phone = $request->get("sp_phone");
            $sp_address = $request->get("sp_address");
            $sp_img_path = $request->get("sp_img_path");
            $sp_img_path = "/images/supplementer/".$fileName.".png";

            $result = DB::table('supplementer')->insert([
                'sp_id'             => NULL,
                'sp_login_id'       => $sp_login_id,
                'sp_password'       => $sp_password,
                'sp_name'           => $sp_name,
                'sp_mail'           => $sp_mail,
                'sp_phone'          => $sp_phone,
                'sp_address'        => $sp_address,
                'sp_img_path'       => $sp_img_path
            ]);
            
            if($result){
                return "good";
            }else{
                return "fail";
            }
        }
    }
    //----------------------- 보충기사 등록 -------------------------------


    //----------------------- 보충기사 수정 -------------------------------
    public function updateSP(Request $request) {
        $is_file = $request->get('is_file');
        $originalFileName = $request->get('original_sp_login_id');              // 원래 파일 이름                
        $fileName = $request->get('sp_login_id');                               // 바뀔 파일 이름

        if ($is_file == "ok") {
            File::delete(public_path('\images\supplementer\\'.$originalFileName.'.png'));  // 파일 삭제

            if(file_exists(public_path('\images\supplementer\\'.$fileName.'.png'))){
                // 같은 이름의 제품 이미지 파일이 있는지 확인
    
                return "fail";
            }
            else {
                $spImage = $request->file('imageFile');                                        // 파일
                $spImage->storeAs('\images\supplementer', $fileName.'.png');                   // 파일 저장
            
                $sp_id = (int)$request->get('sp_id');
                $sp_login_id = (string)$request->get('sp_login_id');
                $sp_password = (string)$request->get('sp_password');
                $sp_name = (string)$request->get('sp_name');
                $sp_mail = (string)$request->get('sp_mail');
                $sp_phone = (string)$request->get('sp_phone');
                $sp_address = (string)$request->get('sp_address');
                $sp_img_path = '/images/supplementer/'.$fileName.'.png';

                $getSpInfo = DB::table('supplementer')->where('sp_id', $sp_id)->get();

                if ($getSpInfo[0]->sp_login_id == $sp_login_id && 
                $getSpInfo[0]->sp_password == $sp_password && 
                $getSpInfo[0]->sp_name == $sp_name && 
                $getSpInfo[0]->sp_mail == $sp_mail && 
                $getSpInfo[0]->sp_phone == $sp_phone && 
                $getSpInfo[0]->sp_address == $sp_address) {
                    return "good";
                }
                else {
                    $result = DB::table('supplementer')
                    ->where('sp_id', $sp_id)->update([
                        'sp_id'             => $sp_id,
                        'sp_login_id'       => $sp_login_id,
                        'sp_password'       => $sp_password,
                        'sp_name'           => $sp_name,
                        'sp_mail'           => $sp_mail,
                        'sp_phone'          => $sp_phone,
                        'sp_address'        => $sp_address,
                        'sp_img_path'       => $sp_img_path
                    ]);

                    if($result){
                        return "good";
                    }else{
                        return "fail";
                    }
                }
            }
        }
        else {
            $sp_id = $request->get('sp_id');
            $sp_login_id = $request->get('sp_login_id');
            $sp_password = $request->get('sp_password');
            $sp_name = $request->get('sp_name');
            $sp_mail = $request->get('sp_mail');
            $sp_phone = $request->get('sp_phone');
            $sp_address = $request->get('sp_address');
            $sp_img_path = '/images/supplementer/'.$fileNmae.'.png';

            $result = DB::table('supplementer')
            ->where('sp_id', $sp_id)->update([
                'sp_id'             => $sp_id,
                'sp_login_id'       => $sp_login_id,
                'sp_password'       => $sp_password,
                'sp_name'           => $sp_name,
                'sp_mail'           => $sp_mail,
                'sp_phone'          => $sp_phone,
                'sp_address'        => $sp_address,
                'sp_img_path'       => $sp_img_path
            ]);

            if($result){
                return "good";
            }else{
                return "fail";
            }
        }
    }
    //----------------------- 보충기사 수정 -------------------------------



    //----------------------- 보충기사 삭제 -------------------------------
    public function deleteSP($sp_id) {
        // 해당 보충기사의 sp_login_id를 가져온다.
        $getSpLoginId = DB::table('supplementer')->select('sp_login_id')
        ->where('sp_id', $sp_id)->get();

        $fileName = $getSpLoginId[0]->sp_login_id;

        // 해당 보충기사의 이름으로 등록된 자판기가 있는지 확인한다.
         $getVDInfo = DB::table('vendingmachine')
         ->where('vd_supplementer', $getSpLoginId[0]->sp_login_id)->get();

        // 등록된 자판기가 없으면 해당 보충기사를 삭제한다.
        if(!isset($getVDInfo[0])){
            $result = DB::table('supplementer')->where('sp_id', $sp_id)->delete();
            
            if($result) {
                File::delete(public_path('\images\supplementer\\'.$fileName.'.png'));          // 파일 삭제
                return "good";
            }
        }else{
            return "fail";
        }
    }
    //----------------------- 보충기사 삭제 -------------------------------

    
    //----------------------- 작업지시서 생성 -------------------------------
    public function createJobOrder($sp_id) {
        
        $boolean = "true";

        // 버튼을 누르면 jo_check를 1로 만든다 여기서 1은 작업지시서 생성된거
        DB::table('job_order')
        ->where('jo_check', 0)->where('sp_id', $sp_id)->update([
            'jo_check'      => 1
        ]);
        
        // 내일 날짜
        $tomorrow = date("Y-m-d", mktime(0,0,0,date("m") , date("d")+1, date("Y"))); 

        // 음 내일날짜로 하나가 만들어져 있으면 고민해본다. 
        // 날짜가 아니고 보충하지 않은 작업지시서를 가지고 있으면으로 변경
        $getTomorrowJobOrder = DB::table('job_order')->select('order_date')
        //->where(DB::raw('date_format(order_date, "%Y-%m-%d")'), $tomorrow)
        ->where('jo_check', 0)
        ->where('sp_id', $sp_id)->get();

        // 보충하지 않은 작업지시서를 가지고 있으면 작업지시서 생성을 안함
        if (isset($getTomorrowJobOrder[0])) {
            $boolean = "false";
        } else {
            // 존재하지 않으면 내일 보충할 다음날 작업지시서를 생성한다
            DB::table('job_order')->insert([
                'sp_id'      => $sp_id,
                'order_date' => $tomorrow." 00:00:00",
                'jo_check'   => 0
            ]);
        }

        // 이후부터는 계속 다음작업지시서에 추가가 된다.
        // 그리고 그 작업지시서가 생성되면 트루, 있으면 풜스 반환!
        return $boolean;
    }   
    //----------------------- 작업지시서 생성 -------------------------------


    //----------------------- 작업지시서 생성 유무 확인-------------------------------
    public function checkJobOrder($sp_id, $date) {
        // 오늘 작업지시서가 생성 되어 있으면 내일 작업지시서가 있으니까
        // 내일 작업지시서가 있는지 확인한다.
        // select * from job_order where date_format(order_date, "%Y-%m-%d") = date_sub(date_format(now(), "%Y-%m-%d"), interval -1 day)
        $check = DB::table('job_order')
        ->where(DB::raw('date_format(order_date, "%Y-%m-%d")'), 
        DB::raw('date_sub(date_format("'.$date.'", "%Y-%m-%d"), interval -1 day)'))
        ->where('sp_id', $sp_id)->get();
        
        if (isset($check[0])) {
            return "true";
        } else {
            return "false";
        }
    }
    //----------------------- 작업지시서 생성 유무 확인-------------------------------
}
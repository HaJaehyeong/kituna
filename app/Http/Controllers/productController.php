<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\FileUpload;
use App\User;
use Storage;
use File;
use Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class productController extends Controller
{
    //------------------------ 회사 정보 -------------------------------
    public function getCompanyData() {
        $result = DB::table('company_info as ci')
        ->select(DB::raw(' ci.cp_name, COUNT(pi.cp_id) as drink_val_of_company, ci.cp_leader, ci.cp_phone, ci.cp_fax'))
        ->leftjoin('product_info as pi', 'pi.cp_id', '=', 'ci.cp_id')
        ->groupBy('ci.cp_id')->get();
  
        return $result;
    }
    //------------------------ 회사 정보 -------------------------------



    //------------------------ 제품 정보 -------------------------------
    public function getProductData() {
        $result = DB::table(DB::raw('stock_management sm'))
        ->select(DB::raw('sm.stock_id, pi.drink_name, ci.cp_name, sm.expiration_date, sm.stock, pi.max_stock, pi.drink_price, pi.max_stock-sm.stock as sp_val'))
        ->join(DB::raw('product_info pi'), 'sm.drink_id', '=', 'pi.drink_id')
        ->join(DB::raw('company_info ci'), 'pi.cp_id', '=', 'ci.cp_id')
        ->orderByRaw('ci.cp_name asc, sm.expiration_date asc')->get();

        return $result;
    }
    //------------------------ 제품 정보 -------------------------------



    //------------------------ 제품 등록 -------------------------------
    public function imgStore(Request $request) { 
        if ($request->file('image')) {
            // 파일이 들어 있는지 확인

            if(!is_dir('\productImg')) {
                Storage::makeDirectory('\productImg');
            }
            // 해당 폴더가 없으면 생성
            
            $file = $request->file('image');                                // 파일 
            $fileName = $request->get('name');                              // 파일 이름

            $file->storeAs('\productImg', $fileName.'.png');                       // Storage경로로 파일 저장

            if(!file_exists(public_path('\images\drink'))){
                File::makeDirectory(public_path('\images\drink'));
            }
            // 해당 폴더가 없으면 생성

            if(file_exists(public_path('\images\drink\\'.$fileName.'.png'))){
                // 같은 이름의 파일이 있는지 확인
                echo $fileName;
            }
            else {
                $file->move(public_path('\images\drink'), $fileName.'.png');     // Storage에서 Public으로 파일 이동

                //----------------------- DB 저장 ---------------------------
                $drink_name = $request->get("drink_name");
                $cp_name = $request->get("cp_name");
                $drink_price = $request->get("drink_price");
                $max_stock = $request->get("max_stock");
                $expiration_date = $request->get("expiration_date");
                $stock = $request->get("stock");

                echo $drink_name." ".$cp_name." ".$drink_price." ".$max_stock." ".$expiration_date;

                $getCpId = DB::table('company_info')->select('cp_id')->where('cp_name', $cp_name)->get();

                DB::table('product_info')->insert([
                    'drink_id'      => null,
                    'drink_name'    => $drink_name,
                    'cp_id'         => $getCpId[0]->cp_id,
                    'drink_img_path'=> "/images/drink/".$drink_name.".png",
                    'drink_price'   => $drink_price,
                    "sell_price"    => 800,
                    'max_stock'     => $max_stock
                ]);

                $getDrinkId = DB::table('product_info')->select('drink_id')
                        ->where('drink_name', $drink_name)->get();

                DB::table('stock_management')->insert([
                    'drink_id'          => $getDrinkId[0]->drink_id,
                    'stock_id'          => null,
                    'buy_date'          => DB::raw('CURRENT_TIMESTAMP'),
                    'expiration_date'   => $expiration_date." 00:00:00",
                    'stock'             => $stock
                ]);
                //----------------------- DB 저장 ---------------------------
            }
        }
    }
    //------------------------ 제품 등록 -------------------------------
    
    
    
    //------------------------ 제품 수정 -------------------------------
    public function updateProduct(Request $request) {
        $fileName = $request->get('name');                              // 파일 이름

        if ($request->get("fileUse") == "true") {
            if(!is_dir('\productImg')) {
                Storage::makeDirectory('\productImg');
            }
            // 해당 폴더가 없으면 생성

            $file = $request->file('image');                                // 파일
            $file->storeAs('\productImg', $fileName.'.png');                       // Storage경로로 파일 저장

            if(!file_exists(public_path('\images\drink'))){
                File::makeDirectory(public_path('\images\drink'));
            }
            // 해당 폴더가 없으면 생성
        }

        $original_name = $request->get("original_name");
        
        if($original_name == $fileName) {
            //----------------------- DB 저장 ---------------------------
            $stock_id = $request->get("stock_id");
            $original_name = $request->get("original_name");
            $drink_name = $request->get("drink_name");
            $cp_name = $request->get("cp_name");
            $drink_price = $request->get("drink_price");
            $stock = $request->get("stock");
            $max_stock = $request->get("max_stock");
            $expiration_date = $request->get("expiration_date");

            $getCpId = DB::table('company_info')->select('cp_id')->where('cp_name', $cp_name)->get();
    
            DB::table('product_info')->where('drink_name', $original_name)->update([
                'drink_name'    => $drink_name,
                'cp_id'         => $getCpId[0]->cp_id,
                'drink_img_path'=> "/images/drink/".$drink_name.".png",
                'drink_price'   => $drink_price,
                "sell_price"    => 800,
                'max_stock'     => $max_stock
            ]);
    
            $getDrinkId = DB::table('product_info')->select('drink_id')
            ->where('drink_name', $drink_name)->get();
    
            DB::table('stock_management')->where('stock_id', $stock_id)->update([
                'drink_id'          => $getDrinkId[0]->drink_id,
                'expiration_date'   => $expiration_date." 00:00:00",
                'stock'             => $stock
            ]);
            //----------------------- DB 저장 ---------------------------
        }
        else {
            if(file_exists(public_path('\images\drink\\'.$fileName.'.png'))){
                // 같은 이름의 파일이 있는지 확인

                echo $fileName;   
            }
            else {
                if ($request->get("fileUse") == "true") {
                    File::delete(public_path('\images\drink\\'.$original_name.'.png'));                // 파일 삭제
                    $file->move(public_path('\images\drink'), $fileName.'.png');     // Storage에서 Public으로 파일 이동
                }

                //----------------------- DB 저장 ---------------------------
                $stock_id = $request->get("stock_id");
                $original_name = $request->get("original_name");
                $drink_name = $request->get("drink_name");
                $cp_name = $request->get("cp_name");
                $drink_price = $request->get("drink_price");
                $stock = $request->get("stock");
                $max_stock = $request->get("max_stock");
                $expiration_date = $request->get("expiration_date");

                $getCpId = DB::table('company_info')->select('cp_id')->where('cp_name', $cp_name)->get();
        
                DB::table('product_info')->where('drink_name', $original_name)->update([
                    'drink_name'    => $drink_name,
                    'cp_id'         => $getCpId[0]->cp_id,
                    'drink_img_path'=> "/images/drink/".$drink_name.".png",
                    'drink_price'   => $drink_price,
                    "sell_price"    => 800,
                    'max_stock'     => $max_stock
                ]);
        
                $getDrinkId = DB::table('product_info')->select('drink_id')
                ->where('drink_name', $drink_name)->get();
        
                DB::table('stock_management')->where('stock_id', $stock_id)->update([
                    'drink_id'          => $getDrinkId[0]->drink_id,
                    'expiration_date'   => $expiration_date." 00:00:00",
                    'stock'             => $stock
                ]);
                //----------------------- DB 저장 ---------------------------
            }
        }
    }
    //------------------------ 제품 수정 -------------------------------



    //------------------------ 제품 삭제 -------------------------------
    public function deleteProduct(Request $request) {
        $stock_id = $request->get('stock_id');
        $drink_name = $request->get('drink_name');
        $fileName = $drink_name.'.png';

        if(file_exists(public_path('\images\drink\\'.$fileName))){
            // 같은 이름의 파일이 있는지 확인
            
            // 제품삭제 -> stock_id 받아 재고가 전부 0이면 제품을 삭제
            // 주문내역도 없어야함
            $get_data = DB::table('stock_management')->where('stock_id', $stock_id)->first();
            $get_drink_id = $get_data -> drink_id;

            $getStock = DB::table('stock_management')
            ->where('drink_id', $get_drink_id)->get();
            
            // 만약 여러 주문서의 내역 중 stoct(재고)을 순환 
            // 재고가 있다면 $stock_check 는 fail이 된다.
            // $stock_check가 good 삭제를 실시한다.
            $stock_check = "good";

            foreach ($getStock as $temp) {
                $stock_flag = $temp -> stock;
                if($stock_flag > 0){
                    $stock_check = "fail";
                }
            }

            if($stock_check == "good"){
                // 해당 제품을 삭제한다.
                DB::table('stock_management')->where('drink_id', $get_drink_id)->delete();
                DB::table('product_info')->where('drink_id', $get_drink_id)->delete();

                File::delete(public_path('\images\drink\\'.$fileName));                // 파일 삭제
            }

            return $stock_check;
        }
    }
    //------------------------ 제품 삭제 -------------------------------



    //------------------------ 음료 주문 저장 -------------------------------
    public function insertOrderSheetColumn(Request $request) {
        /*
        insert into order_sheet(cp_id, os_name, os_date, style) values(ㅁ, "ㅇ"."_2", now(), 2); 
        */
        /*
            select cp_id, count(cp_id)
            from order_sheet
            group by cp_id
        */
        $orderInfo = $request->orderInfo;
        
        $count = 0;
        // 주문서가 몇개인가 확인
        $getOrderSheet = DB::table('order_sheet')
        ->select(DB::raw('cp_id, count(cp_id) as count'))
        ->where('cp_id', $orderInfo[0])
        ->groupBy('cp_id')->get();

        // 회사 이름 가져오기
        $getCompanyName = DB::table('company_info')->select('cp_name')
        ->where('cp_id', $orderInfo[0])->get();

        // 오더시트 몇개인지 확인후 뒷부분에 오는 숫자 정의
        if(!isset($getOrderSheet[0])){
            $count = 1;
        } else {
            $count = $getOrderSheet[0]->count+1;
        }

        // 주문서 삽입
        DB::table('order_sheet')->insert([
            'cp_id'     => $orderInfo[0],
            'os_name'   => $getCompanyName[0]->cp_name."_order_sheet_".$count,
            'os_date'   => date("Y-m-d H:i:s"),
            'style'     => 2,
            'os_path'   => "documentFile/".$getCompanyName[0]->cp_name."_order_sheet_".$count.".pdf"
        ]);
        
        $getInsertedOrderSheetId = DB::table('order_sheet')->select('os_id')
        ->orderBy('os_id', 'desc')->limit(1)->get();        // 마지막에 넣은 os_id를 가져옴

        // 주문서의 제품부분 삽입
        for ($j = 1 ; $j < count($orderInfo); $j++) {
            // 가져온 os_id를 참조하는 행 삽입
            $result = DB::table('os_column')->insert([
                'oc_id'         => NULL,
                'os_id'         => $getInsertedOrderSheetId[0]->os_id,
                'drink_name'    => $orderInfo[$j]['drink_name'],
                'drink_price'   => $orderInfo[$j]['drink_price'],
                'order_val'     => $orderInfo[$j]['order_val']
            ]);
        }
    
        
        if ($result) {
            return 'good';
        } else {
            return 'fail';
        }
    }
    //------------------------ 음료 주문 저장 -------------------------------



    //------------------------ 주문 내역 한개 클릭 시 회사 및 제품 정보 -------------------------------
    // 주문내역 하나 클릭 시 해당 회사정보와 주문 제품정보 가져오기
    // return값[0] = 회사정보
    // return값[1] = 제품정보
    public function getOrderInfo(Request $request) {

        $cp_id = $request->get("cp_id");
        $os_id = $request->get("os_id");

        $getOrderInfo = array();
        $getOrderInfo[0] = DB::table('company_info')->where('cp_id', $cp_id)->get();

        $getOrderInfo[1] = DB::table('os_column')
        ->where('os_id', $os_id)->get();

        return $getOrderInfo;
    }
    //------------------------ 주문 내역 한개 클릭 시 회사 및 제품 정보 -------------------------------



    //------------------------ 주문회사 정보 -------------------------------
    public function getCompanyInfo() {

        $getCompanyInfo = DB::table('company_info')->get();

        return $getCompanyInfo;
    }
    //------------------------ 주문회사 정보 -------------------------------



    //------------------------ 전체 주문 내역 -------------------------------
    public function getOrderSheet() {
        /* 
            select os.os_id, ci.cp_name, os.os_name, os.os_date, os.style
            from order_sheet os
            join company_info ci on os.cp_id = ci.cp_id;
        */
        $getOrderSheet = DB::table('order_sheet as os')
        ->select(DB::raw('os.os_id, ci.cp_id, ci.cp_name, os.os_name, os.os_date, os.style, os.os_path, COUNT(oc.os_id) as all_kind_of_drink'))
        ->join('company_info as ci', 'os.cp_id', '=', 'ci.cp_id')
        ->join('os_column as oc', 'oc.os_id', '=', 'os.os_id')
        ->groupBy('os.os_id')->get();

        return $getOrderSheet;
    }
    //------------------------ 전체 주문 내역 -------------------------------



    //------------------------ 주문 확인 -------------------------------
    // 수령확인 누르면 style이 2 -> 1 되고 stock_management에 재고가 쌓인다.
    // order_sheet의 주문서 번호인 $os_id를 get방식으로 받는다.
    public function getedStockInCompany($os_id) {
        
        // drink_name_temp = 음료이름으로 product_info에서 drink_id를 구하고 
        // order_sheet에서 os_date를 구해 buy_date로 너으야함
        // expirarion_date 는 buy_date에서 2년 추가
        // 현재 stock_management의 stock을 가져와 order_val_temp추가
        // 추가된 값이 max_stock을 넘어버리면 실패 

        // style 2에서 1로 바뀜
        DB::table('order_sheet')->where('os_id', $os_id)
        ->update([
            'style'    => 1,
        ]);

        //해당 주문서의 음료 내역을 가져와서
        $getOrderInfo = DB::table('os_column')
        ->where('os_id', $os_id)->get();

        //음료이름과 갯수 가져오고 이제 그것을
        $drink_name_temp = "";
        $order_val_temp = "";
        foreach ($getOrderInfo as $temp) {
            $drink_name_temp = $temp -> drink_name;
            $order_val_temp = $temp -> order_val;
            
            // drink_id구함
            $getDrinkId = DB::table('product_info')->where('drink_name', $drink_name_temp)->first();
            $drink_id_temp = $getDrinkId-> drink_id;

            // buy_date구함
            $getBuyDate = DB::table('order_sheet')->where('os_id', $os_id)->first();
            $buy_date_temp = $getBuyDate-> os_date;

            // expiration_date구함
            $expiration_date_temp = strtotime("+3 years");
            // 어떡해 딱 맞춰서 3년만 +될까? 현재 3년 +알파
            $expiration_date_temp = date("Y-m-d H:i:s", $expiration_date_temp);

            //insert해야함
            $result = DB::table('stock_management')->insert([
                'drink_id'          => $drink_id_temp,
                'stock_id'          => NULL,
                'buy_date'          => $buy_date_temp,
                'expiration_date'   => $expiration_date_temp,
                'stock'             => $order_val_temp
            ]);

            if ($result) {
                return 'good';
            } else {
                return 'fail';
            }
        }
    }
    //------------------------ 주문 확인 -------------------------------



    //------------------------ 재고 삭제 -------------------------------
    public function deleteStockManagement($stock_id) {
        
        $getStock = DB::table('stock_management')
        ->where('stock_id', $stock_id)->where('stock', '0')->get();

        // drink_id로 구하기
        $get_data = DB::table('stock_management')->where('stock_id', $stock_id)->first();
        $get_drink_id = $get_data -> drink_id;

        // drink_id로 몇개의 칼럼이 있나 찾기.
        $getStockCount = DB::table('stock_management')
        ->where('drink_id', $get_drink_id)->where('stock', '0')->count();

        $deleteCheck = "fail";

        if(isset($getStock[0])){
            // 주문목록중 0인 것이 1개 이상 있을 때만 삭제한다.
            // 1개면 삭제하지 않는다. deleteCheck 여전히 false
            if($getStockCount > 1){
                DB::table('stock_management')->where('stock_id', $stock_id)->delete();
                $deleteCheck = "good";
            }
        }

        return $deleteCheck;
    }
    //------------------------ 재고 삭제 -------------------------------



    //------------------------ 회사정보 등록 -------------------------------
    public function registerCompanyInfo(Request $request) {

        $cp_name = $request->get("cp_name");
        $cp_leader = $request->get("cp_leader");
        $cp_phone = $request->get("cp_phone");
        $cp_mail = $request->get("cp_mail");
        $cp_fax = $request->get("cp_fax");
        
        $result = DB::table('company_info')->insert([
            'cp_id'         => NULL,
            'cp_name'       => $cp_name,
            'cp_leader'     => $cp_leader,
            'cp_phone'      => $cp_phone,
            'cp_mail'       => $cp_mail,
            'cp_fax'        => $cp_fax,
            'cp_os_path'    => "documentFile/".$cp_name.".pdf"
        ]);

        if ($result) {
            return 'good';
        } else {
             return 'fail';
        }
    }
    //------------------------ 회사정보 등록 -------------------------------



    //------------------------ 회사정보 수정 -------------------------------
    public function updateCompanyInfo(Request $request) {
        $cp_id = $request->get("cp_id");
        $cp_name = $request->get("cp_name");
        $cp_leader = $request->get("cp_leader");
        $cp_phone = $request->get("cp_phone");
        $cp_mail = $request->get("cp_mail");
        $cp_fax = $request->get("cp_fax");

        
        $result = DB::table('company_info')->where('cp_id', $cp_id)->update([
            'cp_name'       => $cp_name,
            'cp_leader'     => $cp_leader,
            'cp_phone'      => $cp_phone,
            'cp_mail'       => $cp_mail,
            'cp_fax'        => $cp_fax
        ]);

        if ($result) {
            return 'good';
        } else {
             return 'fail';
        }
    }
    //------------------------ 회사정보 수정 -------------------------------



    //------------------------ 회사 삭제 -------------------------------
    public function deleteCompanyInfo($cp_id) {
        
        // 회사 음료가 몇개 등록되어 있는지 확인
        $result = DB::table('company_info as ci')
        ->where('ci.cp_id', $cp_id)
        ->select(DB::raw('ci.cp_id, ci.cp_name, COUNT(pi.cp_id) as drink_val_of_company'))
        ->leftjoin('product_info as pi', 'pi.cp_id', '=', 'ci.cp_id')
        ->groupBy('ci.cp_id')->first();
  
        // 회사에 등록되어 음료 갯수 - string반환
        $drink_val_of_company = $result -> drink_val_of_company;
        
        // 회사에 등록된 음료가 0이면 삭제 수행
        if($drink_val_of_company == "0"){
            $excute_delete = DB::table('company_info')->where('cp_id', $cp_id)->delete();
            return 'good';
        }else{
            return 'fail';
        }
    }
    //------------------------ 회사 삭제 -------------------------------


    
    //------------------------ pdf 메일 보내기 -------------------------------
    public function sendPDF(Request $request) {
        $fileData = $request->file('pdfFile');
        $cp_id = $request->get('cp_id');

        $getLast_Os_path = DB::table('order_sheet')->select('os_path')
        ->orderBy('os_id', 'desc')
        ->where('cp_id', $cp_id)->limit(1)->get();

        $get_send_mailAdress = DB::table('company_info')->select('cp_mail')
        ->where('cp_id', $cp_id)->get();

        $myMailAdress = DB::table('manager')->select('mail')
        ->where('manager_id', 'manager')->get();


        $fileName = $getLast_Os_path[0]->os_path;
        $fileName = explode("/", $fileName)[1];

        if(!is_dir('\productOrderSheet')) {
            Storage::makeDirectory('\productOrderSheet');
        }
        // 해당 폴더가 없으면 생성

        $fileData->storeAs('\productOrderSheet', $fileName);                       // Storage경로로 파일 저장

        if(!file_exists(public_path('\documentFile'))){
            File::makeDirectory(public_path('\documentFile'));
        }
        // 해당 폴더가 없으면 생성

        $fileData->move(public_path('\documentFile'), $fileName);     // Storage에서 Public으로 파일 이동

        $email = new PHPMailer(true);
        $email->IsSMTP();

        $email->Host = "smtp.gmail.com";
        $email->SMTPAuth = true;
        $email->Port = 465;
        $email->SMTPSecure = "ssl";
        $email->Username = $myMailAdress[0]->mail;
        $email->Password = "tpwkclsrn79!";
        $email->Body = "HaJaeCompany orderSheet";
        $email->SetFrom($myMailAdress[0]->mail);
        $email->AddAddress($get_send_mailAdress[0]->cp_mail);
        $email->Subject = "HaJaeCompany orderSheet";
        $email->AddAttachment("documentFile/".$fileName);

        if ($email->Send()) {
            return "good";
        }
        else {
            return "fail";
        }

        
    }
    //------------------------ pdf 메일 보내기 -------------------------------



    //------------------------ kituna 회사 정보 ------------------------------
    public function getMyCompanyInfo() {
        $getMyCompanyInfo = DB::table('manager')->get();
        
        return $getMyCompanyInfo;
    }
    //------------------------ kituna 회사 정보 -------------------------------
}
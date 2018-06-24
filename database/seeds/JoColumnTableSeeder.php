<?php

use Illuminate\Database\Seeder;

class JoColumnTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $getJoId    = DB::table('job_order')->select('jo_id', 'sp_id')->get();
        // $saveSpId   = array();
        // for ($i = 0 ; $i < count($getJoId) ; $i++) {
        //     $saveSpId[$i] = $getJoId[$i]->sp_id;
        // }
        
        // $getSpName  = DB::table('supplementer')->select('sp_login_id')->whereIn('sp_id', $saveSpId)->get();
        // $saveSpName = array();
        // for ($i = 0 ; $i < count($getSpName) ; $i++) {
        //     $saveSpName[$i] = $getSpName[$i]->sp_login_id;
        // }
        // $saveSpName = ['hajae', 'dahui', 'kihyeok'];

        // // saveVdId = [jo_id 0, 1, 2, 3 ......][vd_id 보충기사에 맞는 자판기 id들 ]
        // $saveVdId   = array();
        
        // for ($i = 0 ;$i < count($getJoId) ;$i++) {
        //     $saveVdId[$saveSpName[$i]] = DB::table('vendingmachine')
        //                         ->select('vd_id')
        //                         ->where('vd_soldout', 1)
        //                         ->where('vd_supplementer', $saveSpName[$i])->get();
        // }

        // $getStockId = array();
        // $saveID = array();
        // for ($i = 0 ;$i < count($saveVdId) ; $i++) {
        //     for ($j = 0 ; $j < count($saveVdId[$saveSpName[$i]]) ; $j++) {
        //         $saveID[$saveSpName[$i]][$j] = $saveVdId[$saveSpName[$i]][$j]->vd_id;
        //     }
        // }

        // for ($i = 0 ; $i < count($saveSpName) ; $i++) {
        //     $getStockId[$saveSpName[$i]] = DB::table('vd_stock')
        //     ->whereIn('vd_id', $saveID[$saveSpName[$i]])->get();
        // }

        $getJoColumnInfo = 
            DB::table('job_order')
            ->select(DB::raw('supplementer.sp_login_id as sp_login_id, vendingmachine.vd_id as vd_id, product_info.drink_name as drink_name, job_order.jo_id as jo_id, vd_stock.max_stock-vd_stock.stock as sp_val, vendingmachine.vd_name as vd_name,
            vd_stock.line as line, product_info.drink_img_path as drink_path'))
            ->join('supplementer', 'job_order.sp_id', '=', 'supplementer.sp_id')
            ->join('vendingmachine', 'supplementer.sp_login_id', '=','vendingmachine.vd_supplementer')
            ->join('vd_stock', 'vendingmachine.vd_id', '=', 'vd_stock.vd_id')
            ->join('stock_management', 'vd_stock.drink_id', '=', 'stock_management.drink_id')
            ->join('product_info', 'stock_management.drink_id', '=', 'product_info.drink_id')
            ->where('vendingmachine.vd_soldout', 1)->get();

        for ($i = 0 ; $i < count($getJoColumnInfo) ; $i++) {
            DB::table('jo_column')->insert([
                'jc_id'       => NULL,
                'jo_id'       => $getJoColumnInfo[$i]->jo_id,
                'vd_id'       => $getJoColumnInfo[$i]->vd_id,
                'vd_name'     => $getJoColumnInfo[$i]->vd_name,
                'sp_login_id' => $getJoColumnInfo[$i]->sp_login_id,
                'drink_name'  => $getJoColumnInfo[$i]->drink_name,
                'drink_path'  => $getJoColumnInfo[$i]->drink_path,
                'sp_val'      => $getJoColumnInfo[$i]->sp_val,
                'drink_line'  => $getJoColumnInfo[$i]->line,
                'note'        => NULL,
                'sp_check'    => 0
            ]);
        }
        
    }
}

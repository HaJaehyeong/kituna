<?php

use Illuminate\Database\Seeder;

class SupplementerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('supplementer')->insert([
            'sp_id'         => NULL,
            'sp_login_id'   => 'hajae',
            'sp_password'   => 'hajae',
            'sp_name'       => 'Ha Jaehyeong',
            'sp_phone'      => '01080771157',
            'sp_mail'       => 'hajae305@gmail.com',
            'sp_address'    => '55, Gukchaebosang-ro 131-gil, Jung-gu, Daegu, Republic of Korea',
            'sp_img_path'   => '/images/supplementer/hajae.png'
        ]);

        DB::table('supplementer')->insert([
            'sp_id'         => NULL,
            'sp_login_id'   => 'dahui',
            'sp_password'   => 'dahui',
            'sp_name'       => 'Gwak Dahui',
            'sp_phone'      => '01030321219',
            'sp_mail'       => 'kwakdh19@gmail.com',
            'sp_address'    => '34, Saedongne-ro, Dalseo-gu, Daegu, Republic of Korea',
            'sp_img_path'   => '/images/supplementer/dahui.png'
        ]);

        DB::table('supplementer')->insert([
            'sp_id'         => NULL,
            'sp_login_id'   => 'kihyeok',
            'sp_password'   => 'kihyeok',
            'sp_name'       => 'Sung Kihyeok',
            'sp_phone'      => '01023112266',
            'sp_mail'       => 'seongkh94@naver.com',
            'sp_address'    => '189, Janggi-ro, Dalseo-gu, Daegu, Republic of Korea',
            'sp_img_path'   => '/images/supplementer/kihyeok.png'
        ]);
    }
}

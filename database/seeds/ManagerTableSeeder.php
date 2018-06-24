<?php

use Illuminate\Database\Seeder;

class ManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('manager')->insert([
            'manager_id'            => 'manager',
            'manager_password'      => 'Wkqla12!@',
            'management_company'    => 'HaJaeCompany',
            'leader'                => 'HaJae',
            'phone'                 => '01080771157',
            'mail'                  => 'hajae305@gmail.com',
            'address'               => '55, Gukchaebosang-ro 131-gil, Jung-gu, Daegu, Republic of Korea',
            'fax'                   => '1234567890',
            'sign_path'             => 'Manager/sign'
        ]);
    }
}

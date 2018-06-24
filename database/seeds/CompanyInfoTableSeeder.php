<?php

use Illuminate\Database\Seeder;

class CompanyInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_info')->insert([
            'cp_id'         => NULL,
            'cp_name'       => 'CokeCompany',
            'cp_leader'     => 'Sehyuk Lee',
            'cp_phone'      => '01066571224',
            'cp_mail'       => 'sehyuk26@Coke.com',
            'cp_fax'        => '00011112222',
            'cp_os_path'    => 'documentFile/CokeCompany.pdf'
        ]);

        DB::table('company_info')->insert([
            'cp_id'         => NULL,
            'cp_name'       => 'CiderCompany',
            'cp_leader'     => 'Seongmin Lee',
            'cp_phone'      => '01087443289',
            'cp_mail'       => 'seongmin25@Cider.com',
            'cp_fax'        => '00022221111',
            'cp_os_path'    => 'documentFile/CiderCompany.pdf'
        ]);

        DB::table('company_info')->insert([
            'cp_id'         => NULL,
            'cp_name'       => 'FantaCompany',
            'cp_leader'     => 'Sungkyu Jin',
            'cp_phone'      => '01052485447',
            'cp_mail'       => 'sungkyu24@fanta.com',
            'cp_fax'        => '00033334444',
            'cp_os_path'    => 'documentFile/FantaCompany.pdf'
        ]);

        DB::table('company_info')->insert([
            'cp_id'         => NULL,
            'cp_name'       => 'SportsDrink',
            'cp_leader'     => 'Mr.Sweat',
            'cp_phone'      => '01084515318',
            'cp_mail'       => 'sports54@sports.com',
            'cp_fax'        => '00044443333',
            'cp_os_path'    => 'documentFile/SportsDrink.pdf'
        ]);
    }
}

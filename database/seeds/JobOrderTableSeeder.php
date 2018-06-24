<?php

use Illuminate\Database\Seeder;

class JobOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1 ; $i <= 3 ; $i++) {
            DB::table('job_order')->insert([
                'jo_id'         => NULL,
                'sp_id'         => $i
            ]);
        }
        
    }
}

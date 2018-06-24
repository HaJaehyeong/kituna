<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SupplementerTableSeeder::class);
        $this->call(CompanyInfoTableSeeder::class);
        $this->call(ProductInfoTableSeeder::class);
        $this->call(StockManagementTableSeeder::class);
        $this->call(VendingmachineTableSeeder::class);
        $this->call(VdStockTableSeeder::class);
        $this->call(SellDataTableSeeder::class);
        $this->call(JobOrderTableSeeder::class);
        $this->call(JoColumnTableSeeder::class);
        $this->call(CoinStockTableSeeder::class);
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_stock', function (Blueprint $table) {
            $table->increments('coin_var');
            $table->integer('vd_id')->unsigned();
            $table->integer('1000')->nullable()->default(NULL);
            $table->integer('500')->nullable()->default(NULL);
            $table->integer('100')->nullable()->default(NULL);
            $table->integer('50')->nullable()->default(NULL);
            $table->integer('10')->nullable()->default(NULL);
            $table->integer('5')->nullable()->default(NULL);
            $table->integer('1')->nullable()->default(NULL);

            $table->foreign('vd_id')
                  ->references('vd_id')->on('vendingmachine');
                  
            $table->engine  = 'InnoDB';
            $table->charset = 'utf8';
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_stock');
    }
}

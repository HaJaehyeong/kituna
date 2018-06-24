<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_stock', function (Blueprint $table) {
            $table->increments('cs_id');
            $table->string('sp_login_id', 16);
            $table->integer('stock_id')->unsigned();
            $table->integer('stock');

            $table->foreign('sp_login_id')
                  ->references('sp_login_id')->on('supplementer');

            $table->foreign('stock_id')
                  ->references('stock_id')->on('stock_management');
                  
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
        Schema::dropIfExists('car_stock');
    }
}

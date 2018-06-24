<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVdStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vd_stock', function (Blueprint $table) {
            $table->integer('vd_id')->unsigned();
            $table->integer('drink_id')->unsigned();
            $table->integer('stock');
            $table->integer('max_stock');
            $table->integer('line');
            $table->increments('count');

            $table->foreign('drink_id')
                  ->references('drink_id')->on('product_info');
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
        Schema::dropIfExists('vd_stock');
    }
}

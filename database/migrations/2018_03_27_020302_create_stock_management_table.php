<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_management', function (Blueprint $table) {
            $table->integer('drink_id')->unsigned();
            $table->increments('stock_id');
            $table->dateTime('buy_date');
            $table->dateTime('expiration_date');
            $table->integer('stock');

            $table->foreign('drink_id')
                  ->references('drink_id')->on('product_info');

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
        Schema::dropIfExists('stock_management');
    }
}

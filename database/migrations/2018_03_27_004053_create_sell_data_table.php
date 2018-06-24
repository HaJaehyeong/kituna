<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_data', function (Blueprint $table) {
            $table->integer('vd_id')->unsigned();
            $table->integer('line');
            $table->integer('drink_id')->unsigned();
            $table->dateTime('sell_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('in_coin', 45);

            $table->foreign('vd_id')
                  ->references('vd_id')->on('vendingmachine');
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
        Schema::dropIfExists('sell_data');
    }
}

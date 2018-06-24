<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendingmachineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendingmachine', function (Blueprint $table) {
            $table->increments('vd_id');
            $table->string('vd_name', 30)->unique();
            $table->double('vd_latitude', 9, 6);
            $table->double('vd_longitude', 9, 6);
            $table->string('vd_address', 50)->nullable()->default(NULL);;
            $table->string('vd_place', 45)->nullable()->default(NULL);
            $table->string('vd_supplementer', 16)->nullable()->default(NULL);
            $table->integer('vd_soldout')->nullable()->default('0');
            $table->integer('money_in_vd')->nullable()->default('0');
            $table->integer('out_money')->nullable()->default('0');

            $table->foreign('vd_supplementer')
                  ->references('sp_login_id')->on('supplementer');

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
        Schema::dropIfExists('vendingmachine');
    }
}

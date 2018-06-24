<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_sheet', function (Blueprint $table) {
            $table->increments('os_id');
            $table->integer('cp_id')->unsigned();
            $table->string('os_name', 45);
            $table->dateTime('os_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('style');
            $table->string('os_path', 45);
            
            $table->foreign('cp_id')
                  ->references('cp_id')->on('company_info');

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
        Schema::dropIfExists('order_sheet');
    }
}

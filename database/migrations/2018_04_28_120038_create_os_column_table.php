<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOsColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('os_column', function (Blueprint $table) {
            $table->increments('oc_id');
            $table->integer('os_id')->unsigned();
            $table->string('drink_name', 45);
            $table->integer('drink_price');
            $table->integer('order_val');

            $table->foreign('os_id')
            ->references('os_id')->on('order_sheet');

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
        Schema::dropIfExists('os_column');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_info', function (Blueprint $table) {
            $table->increments('drink_id');
            $table->string('drink_name', 45);
            $table->integer('cp_id')->unsigned();
            $table->string('drink_img_path', 45)->nullable()->default(NULL);
            $table->integer('drink_price')->unsigned();
            $table->integer('sell_price')->unsigned();
            $table->integer('max_stock')->unsigned();

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
        Schema::dropIfExists('product_info');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_info', function (Blueprint $table) {
            $table->increments('cp_id');
            $table->string('cp_name', 45);
            $table->string('cp_leader', 20);
            $table->string('cp_phone', 20);
            $table->string('cp_mail', 45);
            $table->string('cp_fax', 20);
            $table->string('cp_os_path', 45)->nullable()->default(NULL);
            
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
        Schema::dropIfExists('company_info');
    }
}

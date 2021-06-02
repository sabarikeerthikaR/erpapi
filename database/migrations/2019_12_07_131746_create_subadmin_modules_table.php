<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubadminModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subadmin_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sub_admin_id');
            $table->integer('module_id');
            $table->tinyInteger('access')->comment('1 Accessible 0 Not Accessible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subadmin_modules');
    }
}

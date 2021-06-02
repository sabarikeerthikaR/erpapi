<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kin_first_name');
            $table->string('kin_last_name');
            $table->string('kin_phone');
            $table->string('kin_email');
            $table->integer('age')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->tinyInteger('marital_status')->comment('1 Married 2 Unmarried')->nullable();
            $table->string('address')->nullable();
            $table->string('fb_handle')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->string('insta_handle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
//
        });
    }
}

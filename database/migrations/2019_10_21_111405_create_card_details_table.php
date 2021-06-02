<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('authorization_code');
            $table->string('first_six_digit');
            $table->string('last_four_digit');
            $table->string('exp_month');
            $table->string('exp_year');
            $table->string('channel');
            $table->string('card_type');
            $table->string('bank');
            $table->string('country_code');
            $table->string('brand');
            $table->string('reusable');
            $table->string('signature');


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
        Schema::dropIfExists('card_details');
    }
}

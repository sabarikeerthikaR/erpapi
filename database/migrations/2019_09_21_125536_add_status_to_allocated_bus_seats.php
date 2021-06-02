<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToAllocatedBusSeats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('allocated_bus_seats', function (Blueprint $table) {
            $table->tinyInteger('status')->comment('1 Booked 2 On Board')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allocated_bus_seats', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

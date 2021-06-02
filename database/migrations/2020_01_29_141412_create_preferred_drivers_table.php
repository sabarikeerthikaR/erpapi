<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreferredDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preferred_drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('driver_id');
            $table->integer('ride_id');
            $table->string('preference_latitude')->nullable();
            $table->string('preference_longitude')->nullable();
            $table->decimal('distance', 11, 2)->nullable();
            $table->tinyInteger('status')->comment('1 Posted 2 Accepted 3 Not Accepted')->default(1);
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
        Schema::dropIfExists('preferred_drivers');
    }
}

i<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('order_id');
            $table->string('currency');
            $table->string('transaction_id');
            $table->decimal('amount', 11, 2);
            $table->string('transaction_date');
            $table->tinyInteger('payment_option')->comment('1 Credit card, 2 Debit card, 3 wallet 4 Net Banking 5 Cash');
            $table->tinyInteger('status')->comment('1 Success 0 Failed 2 to be paid');
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
        Schema::dropIfExists('shopping_transactions');
    }
}

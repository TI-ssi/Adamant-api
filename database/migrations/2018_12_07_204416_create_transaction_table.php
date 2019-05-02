<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('type');
            $table->string('status')->default('pending');
            $table->string('currency');
            $table->float('amount', 40, 18);
            $table->string('blockHash');
            $table->string('blockNumber');
            $table->string('from');
            $table->string('gas');
            $table->string('gasPrice');
            $table->string('hash');
            $table->string('input');
            $table->string('nonce');                           
            $table->string('to');
            $table->string('transactionIndex');
            $table->string('v');
            $table->string('r');
            $table->string('s');
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
        Schema::dropIfExists('transactions');
    }
}

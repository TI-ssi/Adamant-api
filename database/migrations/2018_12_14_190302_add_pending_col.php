<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPendingCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balances', function (Blueprint $table) {
            $table->float('pending', 40, 18);
        });
        
        Schema::table('system_balances', function (Blueprint $table) {
            $table->float('pending', 40, 18);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('balances', function (Blueprint $table) {
            $table->dropColumn('pending');
        });
        
        Schema::table('system_balances', function (Blueprint $table) {
            $table->dropColumn('pending');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendToCallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('callbacks', function (Blueprint $table) {
            //
            $table->boolean('send')->default(0)->after('extra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('callbacks', function (Blueprint $table) {
            //
            $table->dropColumn('send');
        });
    }
}

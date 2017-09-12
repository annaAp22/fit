<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuypriceToMsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ms_products', function (Blueprint $table) {
            //
            $table->float('ms_buyPrice')->after('ms_salePrice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ms_products', function (Blueprint $table) {
            //
            $table->dropColumn('ms_buyPrice');
        });
    }
}

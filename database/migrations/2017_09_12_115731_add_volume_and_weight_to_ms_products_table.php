<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVolumeAndWeightToMsProductsTable extends Migration
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
            $table->decimal('ms_volume', 7, 4)->after('size');
            $table->decimal('ms_weight', 5, 2)->after('size');
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
            $table->dropColumn('ms_volume');
            $table->dropColumn('ms_weight');
        });
    }
}

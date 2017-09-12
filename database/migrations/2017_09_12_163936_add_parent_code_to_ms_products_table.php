<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentCodeToMsProductsTable extends Migration
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
            $table->string('parent_code')->after('ms_externalCode');
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
            $table->dropColumn('parent_code');
        });
    }
}

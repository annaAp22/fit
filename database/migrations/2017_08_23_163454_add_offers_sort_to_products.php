<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOffersSortToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->integer('hit_sort')->default(0)->after('hit');
            $table->integer('act_sort')->default(0)->after('act');
            $table->integer('new_sort')->default(0)->after('new');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn('hit_sort');
            $table->dropColumn('act_sort');
            $table->dropColumn('new_sort');
        });
    }
}

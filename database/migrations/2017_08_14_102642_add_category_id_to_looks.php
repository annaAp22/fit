<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryIdToLooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('looks', function(Blueprint $table)
        {
            $table->integer('category_id')->unsigned()->nullable()->after('id');
            $table->foreign('category_id')->references('id')->on('look_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('looks', function(Blueprint $table)
        {
            $table->dropColumn('category_id');
        });
    }
}

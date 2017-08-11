<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLookProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('look_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('look_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('position')->nullable();
            $table->foreign('look_id')->references('id')->on('looks')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('look_product');
    }
}

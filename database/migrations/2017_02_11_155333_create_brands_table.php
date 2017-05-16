<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sysname');

            $table->string('name');
            $table->text('text');

            $table->string('img')->nullable();

            $table->string('title');
            $table->text('description');
            $table->text('keywords');

            $table->boolean('status');

            $table->timestamps();
            $table->softDeletes();

            $table->index('sysname');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('brands');
    }
}

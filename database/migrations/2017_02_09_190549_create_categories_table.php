<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->string('sysname');

            $table->string('name');
            $table->text('text')->nullable();

            $table->string('icon')->nullable();
            $table->string('img')->nullable();
            $table->string('img_main')->nullable();

            $table->string('title');
            $table->text('description');
            $table->text('keywords');

            $table->integer('sort')->default(0);

            $table->boolean('new')->default(0);
            $table->boolean('act')->default(0);
            $table->boolean('hit')->default(0);

            $table->boolean('status');

            $table->timestamps();
            $table->softDeletes();

            $table->index('sysname');
            $table->index('parent_id');
            $table->index('status');
            $table->index('name');
            $table->index('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sysname');

            $table->string('name');
            $table->text('text');

            $table->string('title');
            $table->text('description');
            $table->text('keywords');

            $table->integer('views')->default(0);
            $table->boolean('status');

            $table->timestamps();
            $table->softDeletes();

            $table->index('sysname');
            $table->index('status');
            $table->index('views');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
    }
}

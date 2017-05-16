<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sysname');
            
            $table->date('date');

            $table->string('name');
            $table->text('descr');
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
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
    }
}

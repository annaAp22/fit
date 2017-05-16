<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table) {
            $table->increments('id');
            $table->string('sysname');

            $table->string('name');

            $table->string('img')->nullable();
            $table->text('body');

            $table->string('title');
            $table->text('keywords');
            $table->text('description');

            $table->boolean('status')->default(0);

            $table->timestamps();

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
		Schema::drop('news');
	}

}

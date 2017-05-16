<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('type', ['main', 'left', 'content'])->default('main');
            $table->string('img')->nullable();
            $table->string('url');

            $table->boolean('blank')->default(0);
            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->index('blank');
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
        Schema::drop('banners');
    }
}

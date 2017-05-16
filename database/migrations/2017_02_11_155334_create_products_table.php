<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned()->nullable()->default(null);
            $table->string('sysname');

            $table->string('name');
            $table->string('descr');
            $table->text('text');

            $table->string('sku');
            $table->integer('price');
            $table->integer('discount');

            $table->string('img')->nullable();
            $table->string('video_url')->nullable();

            $table->string('title');
            $table->text('description');
            $table->text('keywords');

            $table->boolean('new')->default(0);
            $table->boolean('act')->default(0);
            $table->boolean('hit')->default(0);

            $table->boolean('stock')->default(1);
            $table->boolean('status');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('brand_id')->references('id')->on('brands');

            $table->index('sysname');
            $table->index('name');
            $table->index('sku');
            $table->index('status');
            $table->index('act');
            $table->index('hit');
            $table->index('new');
            $table->index('stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}

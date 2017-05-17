<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('text');
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->decimal('rating', 2, 1)->default(0.0);

            $table->boolean('status');

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

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
        Schema::drop('product_comments');
    }
}

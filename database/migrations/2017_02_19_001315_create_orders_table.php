<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_id')->unsigned()->nullable();
            $table->integer('payment_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable()->default(null);

            $table->dateTime('datetime');
            $table->string('name');
            $table->string('email')->defalut('');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->text('payment_add')->nullable();
            $table->integer('amount')->nullable()->default(0);
            $table->enum('status', ['wait', 'work', 'send', 'cancel', 'complete'])->default('wait');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('delivery_id')->references('id')->on('deliveries');
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->foreign('customer_id')->references('id')->on('users');

            $table->index(['delivery_id', 'payment_id']);
            $table->index('status');
            $table->index('name');
            $table->index('phone');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}

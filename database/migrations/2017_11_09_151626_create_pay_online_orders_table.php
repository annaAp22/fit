<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayOnlineOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_online_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            //сумма заказа с учетом всего
            $table->float('amount');
            //способ оплаты
            $table->string('provider')->nullable();
            //дата платежа зафиксированная в системе
            $table->dateTime('date');
            //успех заказа
            $table->boolean('success')->default(0);
            $table->unique('order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_online_orders');
    }
}

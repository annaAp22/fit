<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ms_uuid');
            $table->string('ms_name')->nullable();
            $table->string('ms_tag');
            $table->string('ms_phone')->nullable();
            $table->string('ms_email')->nullable();
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
        Schema::dropIfExists('ms_agents');
    }
}

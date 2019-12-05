<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_colors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('color');
            $table->integer('count');
            $table->integer('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('bill_id');
            $table->foreign('bill_id')->references('id')->on('bills');
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
        Schema::dropIfExists('order_colors');
    }
}

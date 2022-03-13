<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('type')->unsigned();
            $table->integer('from_coin_id')->unsigned();
            $table->foreign('from_coin_id')->references('id')->on('coins')->onDelete('cascade');
            $table->decimal('from_amount',38,18)->nullable()->unsigned();
            $table->integer('to_coin_id')->unsigned();
            $table->foreign('to_coin_id')->references('id')->on('coins')->onDelete('cascade');
            $table->decimal('to_amount',38,18)->nullable()->unsigned();
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
        Schema::dropIfExists('orders');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('transaction_type_id')->unsigned();
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onDelete('cascade');
            $table->integer('coin_id_from')->unsigned();
            $table->foreign('coin_id_from')->references('id')->on('coins')->onDelete('cascade');
            $table->decimal('price_from',38,18);
            $table->decimal('amount_from',38,18);
            $table->integer('coin_id_to')->unsigned();
            $table->foreign('coin_id_to')->references('id')->on('coins')->onDelete('cascade');
            $table->decimal('price_to',38,18);
            $table->decimal('amount_to',38,18);
            $table->decimal('commission',10)->nullable();
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
        Schema::dropIfExists('transactions');
    }
}

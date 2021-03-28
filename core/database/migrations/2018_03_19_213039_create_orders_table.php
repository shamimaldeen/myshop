<?php

use Illuminate\Support\Facades\Schema;
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
            $table->string('custom');
            $table->integer('customer_id');
            $table->string('product_price');
            $table->string('discount')->nullable();
            $table->string('product_total');
            $table->tinyInteger('payment_type');
            $table->string('total_amount');
            $table->string('pay_amount');
            $table->string('due_amount');
            $table->integer('instalment_id')->default(0);
            $table->tinyInteger('status')->default(0);
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

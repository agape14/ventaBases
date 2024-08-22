<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->integer('user_id');
            $table->integer('state_division_id');
            $table->integer('city_id');
            $table->integer('township_id');
            $table->string('address');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('note')->nullable();
            $table->string('payment_method');
            $table->string('sub_total');
            $table->string('coupon_id')->nullable();
            $table->string('coupon_discount')->nullable();
            $table->string('grand_total');
            $table->string('invoice_number');
            $table->string('order_date');
            $table->string('order_month');
            $table->string('order_year');
            $table->string('confirmed_date')->nullable();
            $table->string('processing_date')->nullable();
            $table->string('picked_date')->nullable();
            $table->string('shipped_date')->nullable();
            $table->string('delivered_date')->nullable();
            $table->string('cancel_date')->nullable();
            $table->string('return_date')->nullable();
            $table->string('return_reason')->nullable();
            $table->string('status');
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
};

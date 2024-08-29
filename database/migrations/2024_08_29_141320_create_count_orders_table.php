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
        Schema::create('count_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_number')->unique();
            $table->timestamps();
        });
        // Insertar el primer valor para el contador con 50000
        DB::table('count_orders')->insert([
            'order_number' => 50000
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('count_orders');
    }
};

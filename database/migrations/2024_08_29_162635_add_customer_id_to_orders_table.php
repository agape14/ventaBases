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
        Schema::table('orders', function (Blueprint $table) {
            $table->char('tipo_persona', 1)->after('note')->comment('N para Natural, J para Jurídica');
            $table->char('tipo_comprobante', 1)->after('tipo_persona')->comment('B para Boleta, F para Factura');
            $table->unsignedBigInteger('customer_id')->after('order_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tipo_persona');
            $table->dropColumn('tipo_comprobante');
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};

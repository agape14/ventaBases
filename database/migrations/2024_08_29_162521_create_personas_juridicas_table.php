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
        Schema::create('personas_juridicas', function (Blueprint $table) {
            $table->bigIncrements('persona_juridica_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('ruc', 11);
            $table->string('razon_social');
            $table->unsignedBigInteger('representante_legal_id')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->foreign('representante_legal_id')->references('persona_natural_id')->on('personas_naturales')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas_juridicas');
    }
};

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
        Schema::table('personas_juridicas', function (Blueprint $table) {
            $table->string('representante_legal_distrito',6)->nullable()->after('representante_legal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personas_juridicas', function (Blueprint $table) {
            $table->dropColumn('representante_legal_distrito');
        });
    }
};

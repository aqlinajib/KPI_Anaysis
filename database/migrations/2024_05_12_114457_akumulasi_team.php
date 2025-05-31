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
        Schema::create('akumulasi_team', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('target'); // varchar equivalent in Laravel
            $table->integer('capaian_kerja'); // int equivalent in Laravel
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akumulasi_team');
    }
};

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
        Schema::create('sops', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('persetujuan_sekretaris');
            $table->string('persetujuan_mr');
            $table->string('status_pengesahan_direktur');
            $table->string('klasifikasi_dokumen');
            $table->string('nomor_dokumen');
            $table->string('file');
            // Tambahkan kolom lain sesuai kebutuhan

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
        Schema::dropIfExists('sops');
    }
};

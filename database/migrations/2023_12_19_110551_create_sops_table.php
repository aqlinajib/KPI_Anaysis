<?php

// database/migrations/YYYY_MM_DD_create_sops_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSopsTable extends Migration
{
    public function up()
    {
        Schema::create('sops', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('klasifikasi_dokumen');
            $table->string('nomor_dokumen');
            $table->string('file');
            // Tambahkan kolom lain sesuai kebutuhan

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sops');
    }
}


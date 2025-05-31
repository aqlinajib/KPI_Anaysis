<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKerjaHarian extends Model
{
    protected $table = 'hasil_kerja_harian'; // Ganti dengan nama tabel yang sesuai di database Anda

    // Tentukan jika ada kolom yang tidak boleh diisi secara massal
    protected $guarded = [];

    public function akumulasiTeam()
    {
        return $this->belongsTo(AkumulasiTeam::class, 'id_akumulasi', 'capaian_kerja', 'banyak_pekerjaan'); // Ensure the foreign key is correctly specified
    }
}

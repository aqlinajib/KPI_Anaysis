<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkumulasiTeam extends Model
{
    protected $table = 'akumulasi_team'; // Ganti dengan nama tabel yang sesuai di database Anda
    protected $primaryKey = 'id_akumulasi'; // Set primary key to 'id_akumulasi'

    protected $guarded = [];
    protected $appends = ['banyak_pekerjaan'];

    public function getBanyakPekerjaanAttribute($value)
    {
        return $this->attributes['banyak_pekerjaan'] ?? [];
    }

    public function hasilKerjaHarian()
    {
        return $this->hasOne(HasilKerjaHarian::class, 'id_akumulasi', 'id_akumulasi', 'banyak_pekerjaan', 'capaian_kerja');
    }
}

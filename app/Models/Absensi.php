<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $table = 'kehadiran';
    protected $fillable = ['nama', 'divisi',  'tgl', 'id_user', 'alasan', 'jam_masuk'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user'); // Ensure the foreign key is correctly specified
    }
}

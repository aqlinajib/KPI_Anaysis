<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class token extends Model
{
    protected $table = 'tokens'; // Nama tabel di database
    protected $fillable = ['token', 'quantity','used_at'];

    // Jika Anda ingin menggunakan timestamp (created_at, updated_at), maka set properti ini
    public $timestamps = true;
    public function event()
    {
        return $this->belongsTo(event::class, 'id_event');
    }
}

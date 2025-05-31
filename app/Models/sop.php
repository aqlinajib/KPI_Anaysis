<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sop extends Model
{
    use HasFactory;
    protected $table = 'dokumen_sop';
    protected $fillable = ['nama', 'file',  'status', 'divisi'];
}

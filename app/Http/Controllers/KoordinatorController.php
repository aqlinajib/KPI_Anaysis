<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilKerjaHarian;

class KoordinatorController extends Controller
{
    public function index()
    {
        $hasil_kerja_harian = HasilKerjaHarian::all(); // Mengambil semua data hasil kerja harian
        return view('koordinator.detailkerja', compact('hasil_kerja_harian')); // Mengirim data ke view
    }
}

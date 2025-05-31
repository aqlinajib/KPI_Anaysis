<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AkumulasiTeam;
use Illuminate\Support\Facades\View;

class ShareAkumulasiId
{
    public function handle($request, Closure $next)
    {
        // Mengambil hanya id_akumulasi
        $id_akumulasi = AkumulasiTeam::select('id_akumulasi')->pluck('id_akumulasi');

        // Membagikan ke semua views
        View::share('id_akumulasi', $id_akumulasi);

        return $next($request);
    }
}

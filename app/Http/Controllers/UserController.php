<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kandidat;
use App\Models\Event;

class UserController extends Controller
{
    public function index(Request $request)
{
    $idEvent = $request->query('id_event');
    
    // Pengecekan apakah id_event sudah di-set
    if (!$idEvent) {
        // Handle the case when id_event is not set, you might want to redirect or show an error message
        return redirect()->back()->withErrors(['id_event' => 'ID Event tidak valid']);
    }

    // Pengecekan apakah event dengan idEvent tersebut ada
    $event = Event::find($idEvent);
    
    // Pengecekan apakah event ditemukan
    if (!$event) {
        // Handle the case when the event is not found, you might want to redirect or show an error message
        return redirect()->back()->withErrors(['id_event' => 'Event tidak ditemukan']);
    }

    $data = Kandidat::all();

    return view('user.index', [
        'data' => $data,
        'idEvent' => $idEvent,
        'kuotaVote' => $event->kuota_vote,
    ]);
}

public function submitVote(Request $request)
{
    $selectedCandidates = $request->input('selected_candidates');
    $idEvent = $request->input('id_event');

    // Periksa apakah event dengan ID yang diberikan ditemukan
    $event = Event::find($idEvent);

    // Pengecekan apakah event ditemukan
    if (!$event) {
        return redirect()->back()->withErrors(['id_event' => 'Event tidak ditemukan']);
    }

    // Periksa apakah jumlah pemilihan tidak melebihi kuota_vote
    if (count($selectedCandidates) > $event->kuota_vote) {
        return redirect()->back()->withErrors(['vote_limit' => 'Jumlah pemilihan melebihi kuota_vote yang diizinkan']);
    }

    try {
        foreach ($selectedCandidates as $candidateId) {
            $kandidat = Kandidat::findOrFail($candidateId);
            $kandidat->increment('jumlah_suara');
        }

        // Jika berhasil, redirect ke halaman selanjutnya
        return redirect()->route('user.thank');
    } catch (\Exception $e) {
        // Jika terjadi kesalahan, redirect kembali dengan pesan kesalahan
        return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses pemilihan']);
    }
}



    public function thank()
    {
        return view('user.thank');
    }
    
}

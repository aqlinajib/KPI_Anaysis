<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\sop;
use App\Models\AkumulasiTeam;
use App\Models\HasilKerjaHarian;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AkumulasiTeamExport;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function user()
    {
        $sop = sop::all();
        return view('user.index', compact('sop'));
    }

    public function admin()
    { // Pastikan Anda memiliki model AkumulasiTeam yang sesuai
        $user = Auth::User();

        // Assuming the correct column name is 'nama' instead of 'name'
        // Assuming the correct column name is 'nama' instead of 'name'
        $jumlahKerjaHarian = HasilKerjaHarian::where('nama', $user->name)
            ->whereDate('created_at', Carbon::today())
            ->count();
        // Menghitung total SOP yang ada di database
        $totalSop1 = SOP::where('status', 'Pengajuan Dokumen')->count();

        $totalSop = SOP::where('status', 'DITERBITKAN')->count();


        // Assuming the correct column name for Absensi is also 'nama'
        $jumlahAbsen = Absensi::where('nama', $user->name)->count();

        return view('admin.index', compact('jumlahKerjaHarian', 'totalSop1', 'totalSop', 'jumlahAbsen'));
    }

    public function sop()
    {
        $sop = sop::all();
        return view('admin.sop', compact('sop'));
    }

    public function createabsen()
    {
        $allNames = Absensi::select('nama')->distinct()->pluck('nama');
        $absensis = Absensi::all();
        return view(
            'admin.absen',
            compact('absensis', 'allNames')
        );
    }

    public function absenstore(Request $request)
    {
        $existingAttendance = Absensi::where('id_user', Auth::user()->id)
            ->whereDate('tgl', now()->format('Y-m-d'))
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen hari ini.');
        }

        $absensi = new Absensi();
        $absensi->nama = $request->nama;
        $absensi->divisi = $request->divisi;
        $absensi->tgl = $request->tgl;
        $absensi->id_user = $request->id_user;
        $absensi->jam_masuk = now()->setTimezone('Asia/Jakarta')->format('H:i:s');
        $absensi->alasan = $request->alasan ?? 'Tidak ada alasan';
        $absensi->save();

        return redirect()->back();
    }

    public function createsop()
    {
        return view('admin.tambah');
    }

    public function usersopstore(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'status' => 'required',
            'file' => 'required|mimes:pdf|max:2048',
            'divisi' => 'required',
        ]);

        $file = $request->file('file');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        $tujuan_upload = 'public/uploads';
        $file->storeAs($tujuan_upload, $nama_file);

        $sop = new SOP([
            'nama' => $request->input('nama'),
            'file' => $nama_file,
            'divisi' => $request->input('divisi'),
        ]);

        $sop->status = $request->input('status');
        $sop->save();

        return redirect('sop/admin')->with('success', 'Data SOP berhasil disimpan.');
    }

    public function adminsopedit($id)
    {
        $sop = SOP::find($id);
        return view('admin.edit', compact('sop'));
    }


    public function sopshow($id)
    {
        $sop = sop::findOrFail($id);
        $pathToFile = storage_path('app/public/uploads/' . $sop->file);

        if (!file_exists($pathToFile)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($pathToFile) . '"'
        ];

        return response()->file($pathToFile, $headers);
    }



    //KOORDINATOR
    public function sopdestroy($id)
    {
        $sop = SOP::findOrFail($id);
        $sop->delete();

        return redirect('sop/admin')->with('success', 'Data event berhasil disimpan.');
    }

    public function superadm()
    {
        $user = Auth::User();
        // Menghitung total user kecuali koordinator berdasarkan nama
        $totalUsers = User::where('name', '!=', 'koordinator')->count();

        // Menghitung jumlah user yang telah melakukan absensi hari ini, kecuali koordinator berdasarkan nama
        $jumlahAbsenHariIni = Absensi::whereHas('user', function ($query) {
            $query->where('name', '!=', 'koordinator');
        })->whereDate('tgl', Carbon::today())->count();

        // Menghitung jumlah user yang tidak melakukan absensi hari ini
        $jumlahAbsen = $totalUsers - $jumlahAbsenHariIni;

        // Menghitung total SOP yang ada di database
        $totalSop = SOP::where('status', 'Pengajuan Dokumen')->count();

        // Menghitung total kerja harian
        $jumlahKerjaHarian = HasilKerjaHarian::whereDate('created_at', Carbon::today())->count();
        $totalTargetSelesai = HasilKerjaHarian::join('akumulasi_team', 'hasil_kerja_harian.id_akumulasi', '=', 'akumulasi_team.id_akumulasi')
        ->whereColumn('hasil_kerja_harian.banyak_pekerjaan', '>=', 'akumulasi_team.capaian_kerja')
        ->distinct('hasil_kerja_harian.id_akumulasi')
        ->count('hasil_kerja_harian.id_akumulasi');

        $telatHariIni = Absensi::whereDate('created_at', Carbon::today())
            ->whereTime('jam_masuk', '>', '08:00:00')
            ->count();
        $divisions = AkumulasiTeam::select('divisi')->distinct()->get();

        $divisions = AkumulasiTeam::select('divisi')->distinct()->get();
        $performanceData = [];
        $divisionSummaries = [];

        foreach ($divisions as $division) {
            $divisionName = $division->divisi;

            // Get users in the division
            $users = User::where('divisi', $divisionName)->get();

            $totalDailyRating = 0;
            $totalMonthlyRating = 0;
            $userCount = $users->count();
            $divisionTotalMonths = 0; // Initialize totalMonths here for each division

            foreach ($users as $user) {
                $userId = $user->id;

                // Skip users with no daily work results
                $dailyWorkResultsaja = HasilKerjaHarian::where('id_user', $userId)->get();
                $daysWorkedaja = $dailyWorkResultsaja->count();
                if ($daysWorkedaja == 0) {
                    continue;
                }

                // Calculate attendance
                $attendanceCount = Absensi::where('id_user', $userId)->count();

                // Calculate daily work results
                $dailyWorkResults = HasilKerjaHarian::where('id_user', $userId)->get();
                $totalWork = $dailyWorkResults->sum('banyak_pekerjaan');
                $daysWorked = $dailyWorkResults->count();
                $averageDailyWork = $daysWorked > 0 ? $totalWork / $daysWorked : 0;

                // Calculate monthly targets and ratings per month
                $monthlyTargets = AkumulasiTeam::where('divisi', $divisionName)->get();
                $totalMonthlyTarget = $monthlyTargets->sum('capaian_kerja');
                $monthlyRatings = [];
                $monthsProcessed = []; // Array to track processed months

                foreach ($monthlyTargets as $target) {
                    $startDate = new DateTime($target->bulan_mulai);
                    $endDate = new DateTime($target->bulan_selesai);
                    $interval = $startDate->diff($endDate);
                    $months = $interval->m + 1 + ($interval->y * 12); // +1 to include the start month
                    $divisionTotalMonths += $months; // Accumulate total months

                    for ($month = 0; $month < $months; $month++) {
                        $currentMonthStart = (clone $startDate)->modify("+$month month")->modify('first day of this month');
                        $currentMonthEnd = (clone $startDate)->modify("+$month month")->modify('last day of this month');

                        // Skip if month already processed
                        if (in_array($currentMonthStart->format('F Y'), $monthsProcessed)) {
                            continue;
                        }
                        $monthsProcessed[] = $currentMonthStart->format('F Y');

                        // Calculate work done in the current month
                        $monthlyWork = HasilKerjaHarian::where('id_user', $userId)
                            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                            ->sum('banyak_pekerjaan');

                        $monthlyTarget = $target->capaian_kerja / $months;
                        $adjustedMonthlyTarget = $monthlyTarget / $userCount;

                        // Calculate monthly performance rating
                        $monthlyRating = $this->evaluatePerformance($monthlyWork, $adjustedMonthlyTarget);
                        $monthlyRatings[] = [
                            'month' => $currentMonthStart->format('F Y'),
                            'rating' => $monthlyRating,
                        ];

                        // Add to total monthly rating for the division
                        $totalMonthlyRating += $this->convertRatingToScore($monthlyRating);
                    }
                }

                // Calculate percentage of capaian_kerja achieved
                $adjustedTotalWork = $totalWork / $userCount;
                $adjustedTotalMonthlyTarget = $totalMonthlyTarget / $userCount;
                $capaianKerjaAchieved = $adjustedTotalWork > 0 ? ($adjustedTotalWork / $adjustedTotalMonthlyTarget) * 100 : 0;

                // Evaluate daily performance
                if ($divisionTotalMonths > 0) { // Ensure averageMonthlyTarget is defined
                    $averageMonthlyTarget = $totalMonthlyTarget / $divisionTotalMonths;
                    $dailyRating = $this->evaluatePerformance($averageDailyWork, $averageMonthlyTarget / 22 / $userCount);
                    $totalDailyRating += $this->convertRatingToScore($dailyRating);
                }

                $performanceData[$divisionName][] = [
                    'user' => $user->name,
                    'attendance' => $attendanceCount,
                    'average_daily_work' => $averageDailyWork,
                    'daily_rating' => $dailyRating ?? 'N/A', // Handle undefined daily rating
                    'monthly_rating' => $monthlyRating ?? 'N/A', // Handle undefined monthly rating
                    'monthly_ratings' => $monthlyRatings,
                    'capaian_kerja_achieved' => $capaianKerjaAchieved
                ];
            }

            if ($divisionTotalMonths > 0) {
                // Calculate average ratings for the division
                $averageDailyRating = $userCount > 0 ? $totalDailyRating / $userCount : 0;
                $averageMonthlyRating = $userCount > 0 ? $totalMonthlyRating / ($userCount * $divisionTotalMonths) : 0;

                $divisionSummaries[$divisionName] = [
                    'average_daily_rating' => $this->convertScoreToRating($averageDailyRating),
                    'average_monthly_rating' => $this->convertScoreToRating($averageMonthlyRating)
                ];
            }
        }
        return view('koordinator.index', compact('jumlahKerjaHarian', 'totalSop', 'jumlahAbsen', 'totalTargetSelesai', 'telatHariIni', 'performanceData', 'divisionSummaries'));
    }

    private function evaluatePerformance($actual, $target)
    {
        $percentage = $target > 0 ? ($actual / $target) * 100 : 0;

        if ($percentage >= 90) {
            return 'Sangat Baik';
        } elseif ($percentage >= 75) {
            return 'Baik';
        } elseif ($percentage >= 50) {
            return 'Cukup';
        } elseif ($percentage >= 25) {
            return 'Buruk';
        } else {
            return 'Sangat Buruk';
        }
    }

    private function convertRatingToScore($rating)
    {
        switch ($rating) {
            case 'Sangat Baik':
                return 5;
            case 'Baik':
                return 4;
            case 'Cukup':
                return 3;
            case 'Buruk':
                return 2;
            case 'Sangat Buruk':
                return 1;
            default:
                return 0;
        }
    }

    private function convertScoreToRating($score)
    {
        if ($score >= 4.5) {
            return 'Sangat Baik';
        } elseif ($score >= 3.5) {
            return 'Baik';
        } elseif ($score >= 2.5) {
            return 'Cukup';
        } elseif ($score >= 1.5) {
            return 'Buruk';
        } else {
            return 'Sangat Buruk';
        }
    }
    public function edit($id)
    {
        $sop = SOP::find($id);

        return view('admin.edit', compact('sop'));
    }



    public function kerjaHarian(Request $request)
    {
        $query = AkumulasiTeam::with('hasilKerjaHarian');

        if ($request->has('start_date') && $request->start_date) {
            $query->where('bulan_mulai', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('bulan_selesai', '<=', $request->end_date);
        }

        $akumulasi_team = $query->get();
        $kerja_harian = HasilKerjaHarian::all();

        return view('admin.kerjaharian', compact('akumulasi_team', 'kerja_harian'));
    }
    public function absenDetail(Request $request)
    {
        $query = Absensi::query();

        if ($request->filled('nama')) {
            $query->where('nama', $request->nama);
        }

        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl', $request->tanggal);
        }

        if ($request->filter == 'late') {
            // Assuming 'jam_masuk' is the column that stores the check-in time
            // and '08:00:00' is the threshold for being late
            $query->whereTime('jam_masuk', '>', '08:00:00');
        } elseif ($request->filter == 'absent') {
            // Get all users
            $allUsers = User::select('name')->get();

            // Get users who have checked in on the specified date
            $checkedInUsers = Absensi::whereDate('tgl', $request->tanggal)
                ->pluck('nama');

            // Get users who have not checked in
            $absentUsers = $allUsers->whereNotIn('name', $checkedInUsers);

            // Return the absent users
            $absensis = $absentUsers->map(function ($user) use ($request) {
                return (object) [
                    'nama' => $user->name,
                    'tgl' => $request->tanggal,
                    'divisi' => 'N/A', // Assuming division is not available for absent users
                ];
            });


            $names = User::select('name as nama')->distinct()->get();
            $divisions = Absensi::select('divisi')->distinct()->get();
            $users = User::all(); // Add this line to get all users

            return view('admin.absen_detail', compact('absensis', 'names', 'divisions', 'users'));
        }

        $absensis = $query->get();

        $names = User::select('name as nama')->distinct()->get();
        $divisions = Absensi::select('divisi')->distinct()->get();
        $users = User::all(); // Add this line to get all users

        return view('admin.absen_detail', compact('absensis', 'names', 'divisions', 'users'));
    }

    //KPI
    public function kpi()
    {
        $kerja_harian = HasilKerjaHarian::all();
        $akumulasi_team = AkumulasiTeam::with('hasilKerjaHarian')->get();
        $overallMinMonth = $akumulasi_team->min(function ($team) {
            return date('n', strtotime($team->bulan_mulai));
        });

        $overallMaxMonth = $akumulasi_team->max(function ($team) {
            return date('n', strtotime($team->bulan_selesai));
        });


        return view('koordinator.kpi', compact('akumulasi_team', 'kerja_harian', 'overallMinMonth', 'overallMaxMonth'));
    }


    //AKUMULASI TEAM
    public function createAkumulasiTeam()
    {
        return view('admin.createakumulasiteam');
    }

    public function storeAkumulasiTeam(Request $request)
    {
        $request->validate([
            'target' => 'required',
            'capaian_kerja' => 'required|numeric',
            'divisi' => 'required',
            'bobot' => 'required|numeric',
            'bulan_mulai' => 'required|date',
            'bulan_selesai' => 'required|date',
        ]);

        $akumulasiTeam = new AkumulasiTeam([
            'target' => $request->target,
            'capaian_kerja' => $request->capaian_kerja,
            'divisi' => $request->divisi,
            'bobot' => $request->bobot,
            'bulan_mulai' => $request->bulan_mulai,
            'bulan_selesai' => $request->bulan_selesai,
        ]);
        $akumulasiTeam->save();

        return redirect()->route('kpi')->with('success', 'Data berhasil ditambahkan.');
    }

    public function editAkumulasiTeam($id)
    {
        $akumulasiTeam = AkumulasiTeam::findOrFail($id);
        return view('admin.editakumulasiteam', compact('akumulasiTeam'));
    }

    public function updateAkumulasiTeam(Request $request, $id)
    {
        $request->validate([
            'target' => 'required',
            'capaian_kerja' => 'required|numeric',
            'divisi' => 'required',
            'bobot' => 'required|numeric',
            'bulan_mulai' => 'required|date',
            'bulan_selesai' => 'required|date',
        ]);

        $akumulasiTeam = AkumulasiTeam::findOrFail($id);
        $akumulasiTeam->update([
            'target' => $request->target,
            'capaian_kerja' => $request->capaian_kerja,
            'divisi' => $request->divisi,
            'bobot' => $request->bobot,
            'bulan_mulai' => $request->bulan_mulai,
            'bulan_selesai' => $request->bulan_selesai,
        ]);

        return redirect()->route('kpi')->with('success', 'Data berhasil diupdate.');
    }

    public function destroyAkumulasi($id_akumulasi)
    {
        try {
            DB::beginTransaction();
            $akumulasi = AkumulasiTeam::find($id_akumulasi);

            if ($akumulasi) {
                HasilKerjaHarian::where('id_akumulasi', $id_akumulasi)->delete();
                $akumulasi->delete();
                DB::commit();

                return redirect()->route('kpi')->with('success', 'Akumulasi Team berhasil dihapus.');
            } else {
                DB::rollBack();
                return redirect()->route('kpi')->with('error', 'Akumulasi Team not found');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kpi')->with('error', $e->getMessage());
        }
    }
    public function exportAkumulasiTeam(Request $request)
    {
        $akumulasiTeam = AkumulasiTeam::all();
        $kerjaHarian = HasilKerjaHarian::all();
        $overallMinMonth = 12;
        $overallMaxMonth = 1;
        foreach ($akumulasiTeam as $team) {
            $currentStartMonth = date('n', strtotime($team->bulan_mulai));
            $currentEndMonth = date('n', strtotime($team->bulan_selesai));
            $overallMinMonth = min($overallMinMonth, $currentStartMonth);
            $overallMaxMonth = max($overallMaxMonth, $currentEndMonth);
        }

        return Excel::download(new AkumulasiTeamExport($akumulasiTeam, $kerjaHarian, $overallMinMonth, $overallMaxMonth), 'akumulasi_team.xlsx');
    }




    //HASIL KERJA HARIAN
    public function kerjapegawai(Request $request, $id_akumulasi)
    {
        $query = HasilKerjaHarian::where('id_akumulasi', $id_akumulasi);

        if ($request->has('start_date') && $request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date
        ) {
            $query->where('created_at', '<=', $request->end_date);
        }

        $hasil_kerja_harian = $query->get();

        // Fetch the specific AkumulasiTeam entry by id_akumulasi
        $akumulasi_team = AkumulasiTeam::where('id_akumulasi', $id_akumulasi)->first();

        if (!$akumulasi_team) {
            return redirect()->back()->with('error', 'Akumulasi Team not found.');
        }

        return view('admin.kerja_pegawai', compact('akumulasi_team', 'hasil_kerja_harian'));
    }
    public function createHasilKerjaHarian($id_akumulasi)
    {
        $akumulasi = AkumulasiTeam::where('id_akumulasi', $id_akumulasi)->first();
        $akumulasiTeam = AkumulasiTeam::select('bulan_mulai', 'bulan_selesai')->first(); // Sesuaikan query sesuai kebutuhan

        if (!$akumulasi) {
            return redirect()->back()->with('error', 'Data akumulasi tidak ditemukan');
        }

        // Hitung jumlah pengguna di divisi yang tgl_expired belum sama dengan hari ini
        $activeUsersCount = User::where('divisi', $akumulasi->divisi)
            ->whereDate('tgl_expired', '>', now())
            ->count();

        // Hitung total hari kerja antara bulan_mulai dan bulan_selesai
        $startDate = Carbon::parse($akumulasi->bulan_mulai);
        $endDate = Carbon::parse($akumulasi->bulan_selesai);
        $totalWorkingDays = $startDate->diffInWeekdays($endDate);

        // Hitung pekerjaan per hari per user
        $maxPekerjaanPerDay = round($akumulasi->capaian_kerja / ($totalWorkingDays * $activeUsersCount));

        // Hitung pekerjaan yang sudah dilakukan hari ini
        $pekerjaanHariIni = HasilKerjaHarian::where('id_user', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->sum('banyak_pekerjaan');

        // Kurangi maxPekerjaanPerDay dengan pekerjaan yang sudah dilakukan hari ini
        $sisaPekerjaanPerDay = $maxPekerjaanPerDay - $pekerjaanHariIni;
        return view('admin.createhasilkerjaharian', [
            'id_akumulasi' => $id_akumulasi,
            'bulan_mulai' => $akumulasiTeam->bulan_mulai,
            'bulan_selesai' => $akumulasiTeam->bulan_selesai,
            'maxPekerjaanPerDay' => max($sisaPekerjaanPerDay, 0) // Tidak bisa kurang dari 0
        ]);
    }
    public function storeHasilKerjaHarian(Request $request)
    {
        $id_akumulasi = $request->input('id_akumulasi');
        $user = Auth::user(); // Menggunakan autentikasi untuk mendapatkan pengguna yang sedang login

        // Ambil data akumulasi berdasarkan ID dari request
        $akumulasi = AkumulasiTeam::find($request->id_akumulasi);

        if (!$akumulasi) {
            return redirect()->back()->with('error', 'Data akumulasi tidak ditemukan');
        }


        $request->validate([
            'nama' => 'required',
            'id_user' => 'required',
            'divisi' => 'required',
            'hasil_kerja_harian' => 'required',
            'banyak_pekerjaan' => 'required|integer',
            'id_akumulasi' => 'required',
            'bukti' => 'required' // Opsional, tambahkan validasi file jika perlu
        ]);

        $hasilKerjaHarian = new HasilKerjaHarian([
            'nama' => $request->nama,
            'id_user' => $request->id_user,
            'divisi' => $request->divisi,
            'tgl' => now()->format('Y-m-d'), // Menggunakan Carbon untuk mendapatkan tanggal saat ini
            'hasil_kerja_harian' => $request->hasil_kerja_harian,
            'banyak_pekerjaan' => $request->banyak_pekerjaan,
            'bukti' => $request->bukti,
            'id_akumulasi' => $id_akumulasi
        ]);
        $hasilKerjaHarian->save();

        return redirect()->route('kerjaPegawai', ['id_akumulasi' => $id_akumulasi])->with('success', 'Data berhasil ditambahkan.');
    }

    public function editHasilKerjaHarian($id)
    {
        $hasilKerjaHarian = HasilKerjaHarian::findOrFail($id);
        return view('admin.edithasilkerjaharian', compact('hasilKerjaHarian'));
    }

    public function updateHasilKerjaHarian(Request $request, $id)
    {
        $id_akumulasi = $request->input('id_akumulasi');
        $request->validate([
            'nama' => 'required',
            'id_user' => 'required',
            'hasil_kerja_harian' => 'required',
            'banyak_pekerjaan' => 'required|integer',
            'bukti' => 'nullable|string', // Assuming 'bukti' can be optional
            'id_akumulasi' => 'required' // Validate based on your requirements
        ]);

        $hasilKerjaHarian = HasilKerjaHarian::findOrFail($id);
        $hasilKerjaHarian->update([
            'nama' => $request->nama,
            'id_user' => $request->id_user,
            'hasil_kerja_harian' => $request->hasil_kerja_harian,
            'banyak_pekerjaan' => $request->banyak_pekerjaan,
            'bukti' => $request->bukti,
            'id_akumulasi' => $request->id_akumulasi
        ]);

        return redirect()->route('kerjaPegawai', ['id_akumulasi' => $id_akumulasi])->with('success', 'Data berhasil diupdat.');
    }

    public function destroyHasilKerjaHarian($id)
    {
        $hasilKerjaHarian = HasilKerjaHarian::find($id);
        if ($hasilKerjaHarian) {
            $hasilKerjaHarian->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 404);
        }
    }
}

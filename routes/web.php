<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\rekapkehadiranController;
use App\Http\Controllers\SOPController;
use App\Models\AkumulasiTeam;
use App\Exports\AkumulasiTeamExport;
use Maatwebsite\Excel\Facades\Excel;

// Definisi rute untuk otentikasi

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('/login', [SesiController::class, 'index'])->name('login');
    Route::post('/login', [SesiController::class, 'login']);
});


// Grup rute yang memerlukan otentikasi
Route::middleware(['auth'])->group(function () {

    Route::get('/index', [IndexController::class, 'index']);

    Route::get('/index/admin', [IndexController::class, 'admin'])->middleware('userAkases:admin,user')->name('admin');
    Route::get('/index/admin/edit/{id}', [IndexController::class, 'adminsopedit'])->name('mr.sop.edit');
    Route::get('/sop/admin', [IndexController::class, 'sop'])->name('sop');
    Route::get('/admin/tambahabsen', [IndexController::class, 'createabsen'])->name('admin.absen');
    Route::get('/admin/rekapkehadiran', [rekapkehadiranController::class, 'rekapkehadiran'])->name('admin.rekapkehadiran');
    Route::post('/absen/admin/absen/store', [IndexController::class, 'absenstore'])->name('absensi.store');
    Route::get('/tambahsop', [IndexController::class, 'createsop'])->name('admin.tambahsop');
    Route::post('/index/admin/sop/store', [IndexController::class, 'usersopstore'])->name('sop.store');

    //AKUMULASI TEAM
    Route::get('/admin/kerjaharian', [IndexController::class, 'kerjaHarian'])->name('admin.kerjaHarian');
    Route::get('/createakumulasiteam', [IndexController::class, 'createAkumulasiTeam'])->name('admin.createAkumulasiTeam');
    Route::post('/storeakumulasiteam', [IndexController::class, 'storeAkumulasiTeam'])->name('admin.storeAkumulasiTeam');
    Route::get('/editakumulasiteam/{id}', [IndexController::class, 'editAkumulasiTeam'])->name('admin.editAkumulasiTeam');
    Route::put('/updateakumulasiteam/{id}', [IndexController::class, 'updateAkumulasiTeam'])->name('admin.updateAkumulasiTeam');
    Route::delete('/{id_akumulasi}/delete', [IndexController::class, 'destroyAkumulasi'])->name('admin.destroyAkumulasiTeam');


    //HASIL KERJA HARIAN
    Route::get('/createhasilkerjaharian/{id_akumulasi}', [IndexController::class, 'createHasilKerjaHarian'])->name('createHasilKerjaHarian');
    Route::post('/admin/storehasilkerjaharian', [IndexController::class, 'storeHasilKerjaHarian'])->name('admin.storeHasilKerjaHarian');
    Route::get('/edithasilkerjaharian/{id}', [IndexController::class, 'editHasilKerjaHarian'])->name('editHasilKerjaHarian');
    Route::put('/admin/updatehasilkerjaharian/{id}', [IndexController::class, 'updateHasilKerjaHarian'])->name('updateHasilKerjaHarian');
    Route::delete('/admin/destroyhasilkerjaharian/{id}', [IndexController::class, 'destroyHasilKerjaHarian'])->name('destroyHasilKerjaHarian');
    Route::get('/kerja_pegawai/{id_akumulasi}', [IndexController::class, 'kerjapegawai'])->name('kerjaPegawai');
    Route::get('/admin/kerjapegawai/{id_akumulasi}', [IndexController::class, 'kerjapegawai'])->name('admin.kerjapegawai');
    Route::get('/koordinator/detailkerja', [KoordinatorController::class, 'index'])->name('detailkerja');
    // Meng-handle update persetujuan MR dari form edit
    Route::put('/index/admin/update/{id}', [IndexController::class, 'adminsopupdate'])->name('mr.sop.update');
    Route::get('/sop/{id}', [IndexController::class, 'sopshow'])->name('sop.show');
    Route::delete('/sop/{id}', [IndexController::class, 'sopdestroy'])->name('sop.delete');


    // Rute user
    Route::get('/index/user', [IndexController::class, 'user'])->middleware('userAkases:user')->name('user');


    //koordinator
    Route::get('/index/koordinator', [IndexController::class, 'superadm'])->middleware('userAkases:admin,koordinator')->name('koordinator');
    Route::delete('/delete/{id_event}', [IndexController::class, 'destroy'])->name('events.destroy');
    Route::get('/edit/{id}', [IndexController::class, 'edit'])->name('updatesopdulu');
    Route::get('/koordinator/kpi', [IndexController::class, 'kpi'])->name('kpi');
    Route::put('/sop/{id}', [SOPController::class, 'update'])->name('sopupdate');
    Route::get('/admin/absen-detail', [IndexController::class, 'absendetail'])->name('admin.absenDetail');
    Route::get('/export/akumulasi_team', [IndexController::class, 'exportAkumulasiTeam'])->name('export.akumulasi_team');

    Route::get('/logout', [SesiController::class, 'logout'])->name('logout');
    Route::get('/create', [IndexController::class, 'create'])->name('adminall.tambah');
    Route::post('/store', [IndexController::class, 'store'])->name('adminall.store');


    // Rute pemilihan yang memerlukan otentikasi dengan peran user
    // Route to show a specific user
    Route::get('/admin/usershow', [RegisterController::class, 'showuser'])->name('user.show');

    // Route to show the edit form for a specific user
    Route::get('/user/{id}', [RegisterController::class, 'edituser'])->name('user.edit');

    // Route to update a specific user
    Route::put('/updateuser/{id}', [RegisterController::class, 'updateuser'])->name('user.update');

    // Route to delete a specific user
    Route::delete('/user/{id}', [RegisterController::class, 'destroyuser'])->name('user.destroy');


    //tambah superadmin
    //token untuk buat login user token
    //Route::post('/voting-tokens/use', 'VotingTokenController@useToken')->name('voting_tokens.use');
});

// Rute fallback untuk tampilan landing
Route::fallback(function () {
    return view('login');
});

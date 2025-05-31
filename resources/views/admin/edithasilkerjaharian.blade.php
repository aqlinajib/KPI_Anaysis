{{-- resources/views/admin/edithasilkerjaharian.blade.php --}}
@extends('index')

@section('content')
<div class="container">
    <h1>Edit Hasil Kerja Harian</h1>
    <form method="POST" action="{{ route('updateHasilKerjaHarian', $hasilKerjaHarian->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
           
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $hasilKerjaHarian->nama }}"hidden required readonly>
        </div>
        <div class="form-group">
           
            <input type="text" class="form-control" id="divisi" name="divisi" value="{{ $hasilKerjaHarian->divisi }}"hidden required readonly>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" value="{{ $hasilKerjaHarian->id_user }}" id="id_user" name="id_user" required hidden>
        </div>
       
         <div class="form-group">
            <label for="nama">Hasil kerja Harian:</label>
            <input type="text" class="form-control" id="hasil_kerja_harian" name="hasil_kerja_harian" value="{{ $hasilKerjaHarian->hasil_kerja_harian }}" required >
        </div>

         <div class="form-group">
            <label for="banyak_pekerjaan">Banyak Pekerjaan:</label>
            <input type="number" class="form-control" value="{{ $hasilKerjaHarian->banyak_pekerjaan }}" id="banyak_pekerjaan" name="banyak_pekerjaan" required>
        </div>
       
        <div class="form-group">
            <label for="bukti">Bukti:</label>
            <input type="text" class="form-control" id="bukti" value="{{ $hasilKerjaHarian->bukti }}" name="bukti">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" value="{{ $hasilKerjaHarian->id_akumulasi }}" id="id_akumulasi" name="id_akumulasi" required hidden>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>@endsection


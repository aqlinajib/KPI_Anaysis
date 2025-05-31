@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
<div class="container">
    <h1>Create Hasil Kerja Harian</h1>
    <form method="POST" action="{{ route('admin.storeHasilKerjaHarian') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control" id="nama" value="{{ Auth::user()->name }}" name="nama" hidden required readonly>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="divisi" value="{{ Auth::user()->divisi }}" name="divisi" hidden required readonly>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" value="{{ Auth::user()->id }}" id="id_user" name="id_user" required hidden>
        </div>
        <div class="form-group">
            <label for="hasil_kerja_harian">Hasil Kerja Harian:</label>
            <textarea class="form-control" id="hasil_kerja_harian" name="hasil_kerja_harian" required></textarea>
        </div>
     
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
          
                <div class="form-group">
                    <label for="banyak_pekerjaan">Banyak Pekerjaan: (minimal pekerjaan perhari: {{ $maxPekerjaanPerDay }})</label>
                    <input type="number" class="form-control" id="banyak_pekerjaan" name="banyak_pekerjaan" required>
                    @if ($errors->has('banyak_pekerjaan'))
                        <span class="text-danger">{{ $errors->first('banyak_pekerjaan') }}</span>
                    @endif
                </div>
        <div class="form-group">
            <label for="bukti">Link Bukti:</label>
            <input type="text" class="form-control" id="bukti" name="bukti">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" value="{{ $id_akumulasi }}" id="id_akumulasi" name="id_akumulasi" required hidden>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
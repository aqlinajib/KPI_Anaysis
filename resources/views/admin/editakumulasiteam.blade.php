@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
<div class="container">
    <h1>Edit Akumulasi Team</h1>
    <form method="POST" action="{{ route('admin.updateAkumulasiTeam', $akumulasiTeam->id_akumulasi) }}">
    @csrf
    @method('PUT')
        <div class="form-group">
            <label for="nama">Nama Target:</label>
            <input type="text" class="form-control" id="target" name="target" value="{{ $akumulasiTeam->target }}" required>
        </div>
        <div class="form-group">
            <label for="total">Capaian Kerja:</label>
            <input type="number" class="form-control" id="capaian_kerja" name="capaian_kerja" value="{{ $akumulasiTeam->capaian_kerja }}" required>
        </div>
        <div class="form-group">
    <label for="divisi">Divisi:</label>
    <select class="form-control" id="divisi" name="divisi" required>
        <option value="event partnership" {{ $akumulasiTeam->divisi == 'event partnership' ? 'selected' : '' }}>Event Partnership</option>
        <option value="social media" {{ $akumulasiTeam->divisi == 'graphic design' ? 'selected' : '' }}>Graphic Design</option>
        <option value="content creator" {{ $akumulasiTeam->divisi == 'content creator' ? 'selected' : '' }}>Content Creator</option>
        <option value="sme relation" {{ $akumulasiTeam->divisi == 'sme relation' ? 'selected' : '' }}>SME Relation</option>
        <option value="copy writer" {{ $akumulasiTeam->divisi == 'copy writer' ? 'selected' : '' }}>Copy Writer</option>
         <option value="admin" {{ $akumulasiTeam->divisi == 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
</div>
         <div class="form-group">
            <label for="bobot">Bobot %:</label>
            <input type="number" class="form-control" id="bobot" name="bobot" value="{{ $akumulasiTeam->bobot }}" required>
        </div>
        <div class="form-group">
            <label for="bulan_mulai">Bulan Mulai:</label>
            <input type="date" class="form-control" id="bulan_mulai" name="bulan_mulai" value="{{ $akumulasiTeam->bulan_mulai }}" required>
        </div>
        <div class="form-group">
            <label for="bulan_selesai">Bulan Selesai:</label>
            <input type="date" class="form-control" id="bulan_selesai" name="bulan_selesai" value="{{ $akumulasiTeam->bulan_selesai }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection


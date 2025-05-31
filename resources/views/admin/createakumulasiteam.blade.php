@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
<div class="container">
    <h1>Tambah Akumulasi Team</h1>
    <form method="POST" action="{{ route('admin.storeAkumulasiTeam') }}">
        @csrf
        <div class="form-group">
            <label for="nama">Nama Target:</label>
            <input type="text" class="form-control" id="target" name="target" required>
        </div>
        <div class="form-group">
            <label for="total">Capaian Kerja:</label>
            <input type="number" class="form-control" id="capaian_kerja" name="capaian_kerja" required>
        </div>
       <div>
        <div class="form-group">
            <label for="divisi">Divisi:</label>
            <select class="form-control" id="divisi" name="divisi" required>
                <option value="event & partnership">Event Partnership</option>
                <option value="graphic desain">Graphic Desain</option>
                <option value="content creator">Content creator</option>
				<option value="sme relation">Sme relation</option>
				<option value="copy writer">copy writer</option>
                <option value="admin">copy writer</option>
            </select>
        </div>
        <div class="form-group">
            <label for="bobot">Bobot %:</label>
            <input type="number" class="form-control" id="bobot" name="bobot" required>
        </div>
        <div class="form-group">
            <label for="bulan_mulai">Bulan Mulai:</label>
            <input type="date" class="form-control" id="bulan_mulai" name="bulan_mulai" required>
        </div>
        <div class="form-group">
            <label for="bulan_selesai">Bulan Selesai:</label>
            <input type="date" class="form-control" id="bulan_selesai" name="bulan_selesai" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection


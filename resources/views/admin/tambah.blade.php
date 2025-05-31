@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
<div class="container">
    @if(Auth::user()->role == 'user' || Auth::user()->role == 'admin')
        <div class="custom-buttons-container">
            <!-- Back button -->
            <a href="javascript:void(0);" onclick="goBack()" class="btn btn-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back
            </a>
        </div>
        <form action="{{ route('sop.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama">Nama SOP</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="divisi">Divisi</label>
                <input type="text" name="divisi" class="form-control"  required>
            </div>
            <div class="form-group">
                <label for="file">Dokumen PDF:</label><br><br>
                <input type="file" name="file" accept=".pdf" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" class="form-control" required>
                    <option value="" disabled selected>Pilih Status</option>
                    <option value="Pengajuan Dokumen">Pengajuan Dokumen</option>
                    <option value="Pengajuan Revisi">Pengajuan Revisi</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    @endif
</div>
@endsection

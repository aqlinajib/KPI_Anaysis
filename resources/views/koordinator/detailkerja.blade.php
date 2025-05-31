@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
<div class="container">
    <h2>Hasil Kerja Harian</h2>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Akumulasi</th>
                <th>Nama</th>
                <th>Hasil Kerja Harian</th>
                <th>Jumlah Pekerjaan</th>
                <th>Bukti</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasil_kerja_harian as $index => $kerja)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kerja->id_akumulasi }}</td>
                    <td>{{ $kerja->nama }}</td>
                    <td>{{ $kerja->hasil_kerja_harian }}</td>
                    <td>{{ $kerja->banyak_pekerjaan }}</td>
                    <td>{{ $kerja->bukti }}</td>
                    <td>{{ (new DateTime($kerja->tgl))->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
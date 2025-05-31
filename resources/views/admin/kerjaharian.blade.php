@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
<div class="container">
    <h2>Work Report</h2>
    <form method="GET" action="{{ route('admin.kerjaHarian') }}">
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.kerjaHarian') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr style="background-color: #7DC9BF;">
                <th>NO</th>
                <th>Nama Target</th>
                <th>Divisi</th>
                <th>Rentang Waktu</th>
                <th>Target Tercapai</th>
                <th>Total Target</th>
                <th>Kurangnya unit dalam capaian kerja </th> <!-- Added column -->
                <th>Persentase Capaian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $nomorUrut = 1; @endphp
            @foreach ($akumulasi_team as $team)
                @php
                    $persentase = 0; // Reset persentase di awal setiap iterasi
                    $totalKerjaHarian = $team->hasilKerjaHarian->total_kerja_harian ?? '0';
                    $banyakPekerjaan = 0;
                    $currentMonth = date('Y-m'); // Get the current year and month
                    $endMonth = date('Y-m', strtotime($team->bulan_selesai)); // Get the end month in the same format
                @endphp

                @if ($currentMonth <= $endMonth) <!-- Check if the current month is before or the same as the end month -->
                    @if($kerja_harian->isNotEmpty())
                        @foreach ($kerja_harian as $kerja)
                            @if ($kerja->id_akumulasi == $team->id_akumulasi && isset($kerja->banyak_pekerjaan))
                                @php
                                    $banyakPekerjaan += $kerja->banyak_pekerjaan;
                                @endphp
                            @endif
                        @endforeach
                        @php
                            if ($team->capaian_kerja > 0 && $banyakPekerjaan > 0) {
                                $persentase = ($banyakPekerjaan / $team->capaian_kerja) * 100;
                            }
                            $kurangnyaUnit = $team->capaian_kerja - $banyakPekerjaan; // Calculate total kurangnya unit
                        @endphp
                    @endif
                    @if(Auth::user()->role == 'koordinator' || Auth::user()->divisi == $team->divisi) <!-- Modifikasi kondisi untuk koordinator -->
                        <tr>
                            <td>{{ $nomorUrut++ }}</td>
                            <td>{{ $team->target }}</td>
                            <td>{{ $team->divisi }}</td>
                            <td>{{ $team->bulan_mulai }} - {{ $team->bulan_selesai }}</td>
                            <td>{{ $banyakPekerjaan }}</td>
                            <td>{{ $team->capaian_kerja }}</td>
                            <td>{{ $kurangnyaUnit }}</td> <!-- Display total kurangnya unit -->
                            <td>{{ number_format($persentase, 2) }}%</td>
                            <td>
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'user' || Auth::user()->role =='koordinator')
                                    <a href="{{ route('kerjaPegawai', ['id_akumulasi'=>$team->id_akumulasi]) }}" class="btn btn-success">Detail Kerja</a>
                                @endif
                            
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@extends('index') <!-- Merujuk ke template utama -->

@section('content') <!-- Menyediakan isi yield konten -->
@if(Auth::user()->role == 'koordinator')
    <!-- Form Pencarian -->
   <form method="GET" action="{{ route('admin.absenDetail') }}">
    <div class="form-group">
        <label for="nama">Nama:</label>
        <select class="form-control" id="nama" name="nama">
            <option value="">Pilih Nama</option>
            @foreach($names as $name)
                <option value="{{ $name->nama }}" {{ request('nama') == $name->nama ? 'selected' : '' }}>{{ $name->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="divisi">Divisi:</label>
        <select class="form-control" id="divisi" name="divisi">
            <option value="">Pilih Divisi</option>
            @foreach($divisions as $division)
                <option value="{{ $division->divisi }}" {{ request('divisi') == $division->divisi ? 'selected' : '' }}>{{ $division->divisi }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="tanggal">Tanggal:</label>
        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
    </div>
    
    <button type="submit" class="btn btn-primary">Cari</button>
     <a href="{{ route('admin.absenDetail') }}" class="btn btn-secondary">Reset</a>
</form> <!-- Tabel Hasil Pencarian -->
    <table class="table table-bordered" id="absensiTable">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Divisi</th>
                <th>Alasan</th>
                <th>% Kehadiran (dari awal masuk)</th> <!-- Tambahkan kolom persentase kehadiran -->
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if($absensis->isEmpty())
                <tr>
                    <td colspan="4">Tidak ada data yang ditemukan.</td> <!-- Update colspan -->
                </tr>
            @else
                @php
                    $displayedNames = [];
                @endphp
                @foreach($absensis as $absensi)
                <tr>
                    <td>{{ $absensi->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($absensi->tgl)->format('Y-m-d') }}</td>
                    <td>{{ $absensi->divisi }}</td>
                    <td>{{ $absensi->alasan }}</td>
                    <td>
                        @php
                            $startDate = \Carbon\Carbon::parse('first day of this month');
                            $endDate = \Carbon\Carbon::parse('last day of this month');
                            $totalDays = $startDate->diffInWeekdays($endDate) + 1; // Total weekdays in the month
                            $presentDays = $absensis->where('nama', $absensi->nama)->count(); // Count of present days
                            $attendancePercentage = ($presentDays / $totalDays) * 100;
                            $displayedNames[] = $absensi->nama; // Track displayed names
                        @endphp
                        {{ number_format($attendancePercentage, 2) }}%
                    </td>
                    <td>
                        @if($absensi->alasan == 'Tidak ada alasan')
                            <span style="color: green;">Tepat Waktu</span>
                        @else
                            <span style="color: red;">Telat</span>
                        @endif
                    </td>
                </tr>
      
                @endforeach
                <!-- Loop through all users and display their names if not already displayed -->
                @if(request('filter') == 'all' || request('filter') == null)
                    @foreach($users as $user)
                        @if(!in_array($user->name, $displayedNames))
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>N/A</td>
                            <td>{{ $user->divisi }}</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                        </tr>
                        @endif
                    @endforeach
                @endif
            @endif

        </tbody>
    </table>
@endif
@endsection 
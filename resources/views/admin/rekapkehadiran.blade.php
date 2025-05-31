@extends('index')

@section('content')
<div class="container">
    @if(Auth::user()->role == 'koordinator')
<div class="container-info">
            <div class="stat-card">
                <div class="btn-btn warning">
                    @if(Auth::user()->role == 'koordinator')
                        <a href="{{ route('admin.absenDetail') }}" class="btn btn-warning btn-sm">
                            Detail Absen
                        </a>
                    @endif
                </div>
                <br>
            </div>
        <!-- Form untuk memilih rentang tanggal -->
        <form action="" method="GET">
            <div class="form-group">
                <label for="start_date">Dari Tanggal:</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') ?? $startDate }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="end_date">Sampai Tanggal:</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') ?? $endDate }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.rekapkehadiran') }}" class="btn btn-secondary">Reset</a>
        </form>

        <br>

        <div class="attendance-summary">
            <table class="table table-bordered">
                <thead>
                      <tr style="background-color: #7DC9BF;">
                        <th>Divisi</th>
                        <th>Kehadiran (Senin - Jumat)</th>
                        <th>Ketidakhadiran (Senin - Jumat)</th>
                        <th>% Kehadiran</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceData as $divisi => $data)
                        <tr>
                            <td>{{ $divisi }}</td>
                            <td>{{ $data['attendanceCount'] }}</td>
                            <td>{{ $data['absenceCount'] }}</td>
                            <td>{{ number_format($data['attendancePercentage'], 2) }}%</td>
                          
                        </tr>
                    @endforeach
                    @if(empty($attendanceData))
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data kehadiran untuk rentang tanggal yang dipilih.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

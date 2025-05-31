@extends('index')

@section('content')
<div class="container">
    <h2>Key Performance Indicator</h2>
    @if(Auth::user()->role == 'koordinator')
    <a href="{{ route('admin.createAkumulasiTeam') }}" class="btn btn-primary">Tambah Data</a>
    <a href="{{ route('export.akumulasi_team') }}" class="btn btn-primary">Export Data</a>
     <br>
    @endif
    @php
        $totalBobot = 0;
        foreach ($akumulasi_team as $team) {
            $totalBobot += $team->bobot;
        }
        $bobotClass = $totalBobot == 100 ? 'btn btn-primary' : ($totalBobot > 100 ? 'btn btn-danger' : 'btn btn-info');
    @endphp
    <br>
    <span class="{{ $bobotClass }}">Total Bobot: {{ $totalBobot }}%</span> <!-- Add this line to display the total bobot with dynamic class -->

    <br>

    <p style="color: white;">...</p>
    <table class="table table-bordered">
        <thead>
             <tr style="background-color: #7DC9BF;">
                <th rowspan="2">KPI</th>
                <th rowspan="2" class="text-center align-middle">DIVISI</th>
                <th colspan="2" class="text-center align-middle">TARGET</th>
                <th colspan="{{ $overallMaxMonth - $overallMinMonth + 1 }}" class="text-center align-middle">PENCAPAIAN</th>
                <th rowspan="2">Total Banyak Pekerjaan</th>
                <th rowspan="2">% Pencapaian</th>
                <th rowspan="2">Aksi</th>
                
            </tr>
            <tr style="background-color: #7DC9BF;">
                <th>TARGET</th>
                <th>BOBOT</th>
                @php
                $overallMinMonth = 12;
                $overallMaxMonth = 1;
                foreach ($akumulasi_team as $team) {
                    $currentStartMonth = date('n', strtotime($team->bulan_mulai));
                    $currentEndMonth = date('n', strtotime($team->bulan_selesai));
                    $overallMinMonth = min($overallMinMonth, $currentStartMonth);
                    $overallMaxMonth = max($overallMaxMonth, $currentEndMonth);
                }
                for ($m = $overallMinMonth; $m <= $overallMaxMonth; $m++) {
                    echo "<th>" . date('F', mktime(0, 0, 0, $m, 1)) . "</th>";
                }
                @endphp
            </tr>
        </thead>
        <tbody>
            @foreach ($akumulasi_team as $team)
            @php
                $startMonth = date('n', strtotime($team->bulan_mulai));
                $endMonth = date('n', strtotime($team->bulan_selesai));
                $currentMonth = date('n');
                if ($currentMonth > $endMonth) {
                    continue;
                }
                $banyakPekerjaanPerBulan = array_fill($overallMinMonth, $overallMaxMonth - $overallMinMonth + 1, 0);
                $totalTargetPerBulan = 0;
            @endphp
            @if($kerja_harian->isNotEmpty())
                @foreach ($kerja_harian as $kerja)
                    @if ($kerja->id_akumulasi == $team->id_akumulasi)
                        @php
                            $bulan = date('n', strtotime($kerja->tgl));
                            if ($bulan >= $startMonth && $bulan <= $endMonth) {
                                $banyakPekerjaanPerBulan[$bulan] += $kerja->banyak_pekerjaan;
                            }
                        @endphp
                    @endif
                @endforeach
            @endif

            @php
                $filteredValues = array_filter($banyakPekerjaanPerBulan, function($v) { return $v !== 0; });
                $sumValues = array_sum($filteredValues);
                $persentase = $sumValues > 0 ? ($sumValues / $team->capaian_kerja) * 100 : 0;
            @endphp

            <tr>
                <td>{{ $team->target }}</td>
                <td>{{ $team->divisi }}</td>
                <td>{{ $team->capaian_kerja }}</td>
                <td>{{ $team->bobot }}%</td>
                @foreach ($banyakPekerjaanPerBulan as $bulan => $value)
                    @if($bulan >= $startMonth && $bulan <= $endMonth)
                        <td>{{ $value }}</td>
                    @else
                        <td>-</td>
                    @endif
                @endforeach
                <td>{{ $sumValues }}</td>
                <td>{{ number_format($persentase, 2) }}%</td>
                <td>
                    @if(Auth::user()->role == 'koordinator')
                        <a href="{{ route('admin.editAkumulasiTeam', $team->id_akumulasi) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.destroyAkumulasiTeam', $team->id_akumulasi) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
            <td colspan="11">
                <div class="container">
                    <h2>Total Bobot: {{ $totalBobot }}%</h2> <!-- Add this line to display the total bobot -->
                </div>
            </td>
        </tbody>
    </table>
</div>
@endsection

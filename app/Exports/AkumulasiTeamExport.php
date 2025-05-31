<?php

namespace App\Exports;

use App\Models\AkumulasiTeam;
use App\Models\HasilKerjaHarian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AkumulasiTeamExport implements FromCollection, WithHeadings, WithMapping
{
    protected $akumulasiTeam;
    protected $HasilkerjaHarian;
    protected $overallMinMonth;
    protected $overallMaxMonth;

    public function __construct($akumulasiTeam, $kerjaHarian, $overallMinMonth, $overallMaxMonth)
    {
        $this->akumulasiTeam = $akumulasiTeam;
        $this->HasilkerjaHarian = $kerjaHarian;
        $this->overallMinMonth = $overallMinMonth;
        $this->overallMaxMonth = $overallMaxMonth;
    }

    public function collection()
    {
        return $this->akumulasiTeam;
    }

    public function headings(): array
    {
        $months = [];
        for ($m = $this->overallMinMonth; $m <= $this->overallMaxMonth; $m++) {
            $months[] = date('F', mktime(0, 0, 0, $m, 1));
        }
        return array_merge(['KPI', 'Target', 'Bobot'], $months, ['% Pencapaian']);
    }

    public function map($team): array
    {
        $startMonth = date('n', strtotime($team->bulan_mulai));
        $endMonth = date('n', strtotime($team->bulan_selesai));
        $currentMonth = date('n');

        if ($currentMonth > $endMonth) {
            return []; // Skip this iteration and do not display the data
        }

        $banyakPekerjaanPerBulan = array_fill($this->overallMinMonth, $this->overallMaxMonth - $this->overallMinMonth + 1, 0);

        foreach ($this->HasilkerjaHarian as $kerja) {
            if ($kerja->id_akumulasi == $team->id_akumulasi) {
                $bulan = date('n', strtotime($kerja->tgl));
                if ($bulan >= $startMonth && $bulan <= $endMonth) {
                    $banyakPekerjaanPerBulan[$bulan] += $kerja->banyak_pekerjaan;
                }
            }
        }

        $filteredValues = array_filter($banyakPekerjaanPerBulan, function ($v) {
            return $v !== 0;
        });
        $sumValues = array_sum($filteredValues);
        $persentase = $sumValues > 0 ? ($sumValues / $team->capaian_kerja) * 100 : 0;

        return array_merge([
            $team->target,  // KPI mengambil data kolom target
            $team->capaian_kerja,  // Target mengambil data kolom capaian_kerja
            $team->bobot . '%',  // Bobot mengambil data kolom bobot
        ], $banyakPekerjaanPerBulan, [number_format($persentase, 2) . '%']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class rekapkehadiranController extends Controller
{
   public function rekapkehadiran(Request $request)
   {
        // Default start and end dates to the last 7 days
        $endDate = $request->input('end_date') ?? now()->toDateString();
        $startDate = $request->input('start_date') ?? Carbon::parse($endDate)->subDays(6)->toDateString(); // Last 7 days

        // Cache key generation based on request parameters
        $cacheKey = "absen_{$startDate}_{$endDate}_" . Auth::id();

        // Cache the results for a specified duration
        $attendanceData = \Cache::remember($cacheKey, 60, function () use ($startDate, $endDate) {
            return $this->calculateAttendanceData($startDate, $endDate);
        });

        return view('admin.rekapkehadiran', compact('attendanceData', 'startDate', 'endDate'));
    }

    private function calculateAttendanceData($startDate, $endDate)
    {
        $attendanceData = [];

        // Retrieve absensi records within the date range and group by division
        $absensis = Absensi::select('id_user', 'tgl')
            ->with(['user' => function ($query) {
                $query->select('id', 'divisi', 'created_at');
            }])
            ->whereBetween('tgl', [$startDate, $endDate])
            ->get()
            ->groupBy('user.divisi');

        foreach ($absensis as $divisi => $records) {
            // Calculate attendance and absence counts
            $attendanceCount = $records->filter(function ($record) {
                return !in_array(Carbon::parse($record->tgl)->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
            })->count();

            // Calculate total weekdays in the date range
            $totalWeekdays = CarbonPeriod::create($startDate, $endDate)->filter(function ($date) {
                return $date->isWeekday();
            })->count();

            $absenceCount = $totalWeekdays - $attendanceCount;

            // Calculate attendance percentage since account creation
            $accountCreationDate = $records->first()->user->created_at;
            $totalWeekdaysSinceCreation = Carbon::parse($accountCreationDate)->diffInWeekdays(Carbon::now());

            $attendanceCountSinceCreation = $records->filter(function ($record) use ($accountCreationDate) {
                $recordDate = Carbon::parse($record->tgl);
                return $recordDate->isWeekday() && $recordDate >= $accountCreationDate;
            })->count();

            $attendancePercentageSinceCreation = $totalWeekdaysSinceCreation > 0 
                ? ($attendanceCountSinceCreation / $totalWeekdaysSinceCreation) * 100 
                : 0;

            // Collect the processed data
            $attendanceData[$divisi] = [
                'attendanceCount' => $attendanceCount,
                'absenceCount' => $absenceCount,
                'attendancePercentage' => $totalWeekdays > 0 ? ($attendanceCount / $totalWeekdays) * 100 : 0,
                'attendancePercentageSinceCreation' => $attendancePercentageSinceCreation
            ];
        }

        return $attendanceData;
    }
}

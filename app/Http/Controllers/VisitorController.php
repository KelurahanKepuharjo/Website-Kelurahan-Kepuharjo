<?php

namespace App\Http\Controllers;
use App\Models\Visit;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;


class VisitorController extends Controller
{
    public function landingPage(Request $request)
    {
        $visit = new Visit();
        $visit->ip_address = $request->ip();
        $visit->user_agent = $request->header('User-Agent');
        $visit->visited_at = now();

        // Hitung durasi kunjungan sebelum menyimpan ke dalam model Visit
        $lastVisit = Visit::where('ip_address', $request->ip())->latest('visited_at')->first();
        if ($lastVisit) {
            $visit->duration = $lastVisit->duration;
        }

        $visit->save();

        $totalVisits = Visit::count();
        $averageDurationInSeconds = Visit::avg('duration');

        // Mengubah rata-rata durasi dari detik menjadi menit
        $averageDurationInMinutes = floor($averageDurationInSeconds / 60);

        // Format rata-rata durasi menjadi HH:mm (misalnya 01:30)
        $averageDuration = sprintf('%02d:%02d', $averageDurationInMinutes / 60, $averageDurationInMinutes % 60);

        return view('index', compact('totalVisits', 'averageDuration'));
    }
}

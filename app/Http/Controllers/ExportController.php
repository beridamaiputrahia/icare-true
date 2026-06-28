<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Devotion;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    // ── Schedules ──────────────────────────────────────────────────────

    public function schedulePdf(Schedule $schedule)
    {
        $pdf = Pdf::loadView('exports.pdf.schedule', compact('schedule'))
            ->setPaper('a4', 'portrait');
        return $pdf->download("jadwal-{$schedule->id}.pdf");
    }

    public function schedulesListPdf(Request $request)
    {
        $schedules = Schedule::orderBy('tanggal')->get();
        $pdf = Pdf::loadView('exports.pdf.schedules-list', compact('schedules'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('daftar-jadwal.pdf');
    }

    // ── Announcements ──────────────────────────────────────────────────

    public function announcementPdf(Announcement $announcement)
    {
        $pdf = Pdf::loadView('exports.pdf.announcement', compact('announcement'))
            ->setPaper('a4', 'portrait');
        return $pdf->download("pengumuman-{$announcement->id}.pdf");
    }

    // ── Devotions ──────────────────────────────────────────────────────

    public function devotionPdf(Devotion $devotion)
    {
        $this->authorize('view', $devotion);
        $pdf = Pdf::loadView('exports.pdf.devotion', compact('devotion'))
            ->setPaper('a4', 'portrait');
        return $pdf->download("renungan-{$devotion->id}.pdf");
    }

    // ── Statistics ─────────────────────────────────────────────────────

    public function statisticsPdf(Request $request)
    {
        $this->authorize('viewAny', \App\Models\User::class);

        $data = $this->getStatisticsData();
        $pdf  = Pdf::loadView('exports.pdf.statistics', $data)
            ->setPaper('a4', 'landscape');
        return $pdf->download('statistik-' . date('Y-m-d') . '.pdf');
    }

    private function getStatisticsData(): array
    {
        return [
            'totalMembers'   => \App\Models\Member::where('is_active', true)->count(),
            'totalDevotions' => Devotion::where('status', 'approved')->count(),
            'totalPrayers'   => \App\Models\Prayer::count(),
            'topWriters'     => \App\Models\User::withCount(['devotions as devotions_count' => fn ($q) => $q->where('status', 'approved')])
                                    ->having('devotions_count', '>', 0)
                                    ->orderByDesc('devotions_count')->take(10)->get(),
            'generatedAt'    => now()->translatedFormat('d F Y, H:i'),
        ];
    }
}

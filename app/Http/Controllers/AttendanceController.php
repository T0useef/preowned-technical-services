<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class AttendanceController extends Controller
{
    public function preview()
    {
        $logo = null;
        $logoPath = public_path('assets/images/pts-logo.png');

        if (File::exists($logoPath)) {
            $logo = 'data:image/png;base64,' . base64_encode(File::get($logoPath));
        }

        $users = User::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $pdf = Pdf::loadView('attendance.preview-pdf', [
            'logo' => $logo,
            'pages' => $this->buildAttendancePages($users),
        ]);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('dpi', 96);
        $pdf->setOption('isHtml5ParserEnabled', false);
        $pdf->setOption('isFontSubsettingEnabled', true);

        return $pdf->download('attendance-record.pdf');
    }

    private function buildAttendancePages($users): array
    {
        $firstPageSize = 22;
        $nextPageSize = 42;
        $users = collect($users)->values();
        $pages = [];

        $pages[] = [
            'show_header' => true,
            'rows' => $this->padAttendanceRows($users->take($firstPageSize), $firstPageSize, 1),
        ];

        $remaining = $users->slice($firstPageSize)->values();
        $startSr = $firstPageSize + 1;

        foreach ($remaining->chunk($nextPageSize) as $chunk) {
            $pages[] = [
                'show_header' => false,
                'rows' => $this->padAttendanceRows($chunk, $nextPageSize, $startSr),
            ];
            $startSr += $nextPageSize;
        }

        $emptyOnLastPage = collect(end($pages)['rows'])
            ->filter(fn ($row) => $row['name'] === '')
            ->count();

        if ($emptyOnLastPage < 10) {
            $pages[] = [
                'show_header' => false,
                'rows' => $this->padAttendanceRows(collect(), $nextPageSize, $startSr),
            ];
        }

        return $pages;
    }

    private function padAttendanceRows($users, int $size, int $startSr): array
    {
        $users = collect($users)->values();
        $rows = [];

        for ($i = 0; $i < $size; $i++) {
            $user = $users->get($i);
            $rows[] = [
                'sr' => $startSr + $i,
                'name' => $user?->name ?? '',
            ];
        }

        return $rows;
    }
}

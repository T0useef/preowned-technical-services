<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Models\Salary;
use App\Models\User;
use App\Models\WorkingHour;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function generateSalarySlip(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'allowed_off' => 'nullable|integer|min:0|max:31',
            'advance_deduction' => 'nullable|numeric|min:0',
        ]);

        $existingSalary = Salary::where('user_id', $validated['user_id'])
            ->where('month', (int) $validated['month'])
            ->where('year', (int) $validated['year'])
            ->first();

        if ($existingSalary) {
            return response()->json([
                'message' => 'Salary slip already generated for this month.',
                'file_url' => asset($existingSalary->file_path),
                'already_generated' => true,
            ]);
        }

        $user = User::findOrFail($validated['user_id']);
        $selectedMonthStart = Carbon::create($validated['year'], $validated['month'], 1)->startOfMonth();
        $currentMonthStart = now()->startOfMonth();

        if ($selectedMonthStart->gt($currentMonthStart)) {
            throw ValidationException::withMessages([
                'month' => 'You cannot generate salary for upcoming months.',
            ]);
        }

        $userJoinDate = Carbon::parse($user->created_at)->startOfDay();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

        if ($selectedMonthEnd->lt($userJoinDate)) {
            throw ValidationException::withMessages([
                'month' => 'Selected month/year is before this user registration date.',
            ]);
        }

        $entries = AdvancePayment::where('user_id', $validated['user_id'])
            ->whereMonth('created_at', $validated['month'])
            ->whereYear('created_at', $validated['year'])
            ->orderBy('created_at', 'desc')
            ->get();

        $advanceTotals = AdvancePayment::where('user_id', $validated['user_id'])
            ->whereDate('created_at', '<=', $selectedMonthEnd->toDateString())
            ->selectRaw("
                SUM(CASE WHEN status = 'given' THEN amount ELSE 0 END) as total_taken,
                SUM(CASE WHEN status = 'received' THEN amount ELSE 0 END) as total_given_back
            ")
            ->first();

        $daysInMonth = Carbon::create($validated['year'], $validated['month'], 1)->daysInMonth;
        $presentDates = WorkingHour::where('user_id', $validated['user_id'])
            ->whereMonth('date', $validated['month'])
            ->whereYear('date', $validated['year'])
            ->distinct()
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        $presents = count($presentDates);
        $offs = max($daysInMonth - $presents, 0);
        $allowedOff = isset($validated['allowed_off']) ? (int) $validated['allowed_off'] : 4;
        $extraOff = max($offs - $allowedOff, 0);
        $monthlySalary = (float) ($user->salary ?? 0);
        $singleDaySalary = $daysInMonth > 0 ? ($monthlySalary / $daysInMonth) : 0;

        $overtimeHourEntries = WorkingHour::where('user_id', $validated['user_id'])
            ->whereMonth('date', $validated['month'])
            ->whereYear('date', $validated['year'])
            ->pluck('overtime_hours')
            ->map(fn ($hours) => (float) ($hours ?? 0))
            ->filter(fn ($hours) => $hours > 0)
            ->values();

        $overtimeHours = (float) $overtimeHourEntries->sum();
        $adjustedOvertimeHours = (float) $overtimeHourEntries
            ->map(fn ($hours) => $hours > 3 ? ($hours + 2) : $hours)
            ->sum();
        $hourlyRate = $singleDaySalary / 8;
        $overtimeAmount = $hourlyRate * $adjustedOvertimeHours;
        $deduction = $singleDaySalary * $extraOff;
        $finalSalary = max(($monthlySalary - $deduction) + $overtimeAmount, 0);

        $startDate = Carbon::create($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::create($validated['year'], $validated['month'], 1)->endOfMonth();
        $presentDateMap = array_flip($presentDates);
        $dailyDetails = [];

        $workingRowsByDate = DB::table('working_hours')
            ->leftJoin('projects', 'projects.id', '=', 'working_hours.project_id')
            ->select(
                'working_hours.date',
                'working_hours.working_hours',
                'working_hours.overtime_hours',
                'working_hours.description',
                'projects.name as project_name'
            )
            ->where('working_hours.user_id', $validated['user_id'])
            ->whereMonth('working_hours.date', $validated['month'])
            ->whereYear('working_hours.date', $validated['year'])
            ->orderBy('working_hours.date')
            ->get()
            ->groupBy(fn ($row) => Carbon::parse($row->date)->format('Y-m-d'));

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $formatted = $date->format('Y-m-d');
            $rows = $workingRowsByDate->get($formatted, collect());

            if (!isset($presentDateMap[$formatted])) {
                $dailyDetails[] = [
                    'date' => $date->format('d/m/Y'),
                    'status' => 'absent',
                    'projects' => '-',
                    'working_hours' => 0,
                    'overtime_hours' => 0,
                    'adjusted_overtime_hours' => 0,
                    'description' => '-',
                ];
            } else {
                $projectNames = $rows->pluck('project_name')->filter()->unique()->values()->all();
                $descriptions = $rows->pluck('description')->filter()->unique()->values()->all();
                $rawOtHours = (float) $rows->sum(fn ($row) => (float) ($row->overtime_hours ?? 0));
                $dailyDetails[] = [
                    'date' => $date->format('d/m/Y'),
                    'status' => 'present',
                    'projects' => !empty($projectNames) ? implode(', ', $projectNames) : '-',
                    'working_hours' => (float) $rows->sum(fn ($row) => (float) ($row->working_hours ?? 0)),
                    'overtime_hours' => $rawOtHours,
                    'adjusted_overtime_hours' => $rawOtHours > 3 ? ($rawOtHours + 2) : $rawOtHours,
                    'description' => !empty($descriptions) ? implode(' | ', $descriptions) : '-',
                ];
            }
        }

        $totalTaken = (float) ($advanceTotals->total_taken ?? 0);
        $totalGivenBack = (float) ($advanceTotals->total_given_back ?? 0);
        $netAdvance = $totalTaken - $totalGivenBack;
        $advanceDeduction = isset($validated['advance_deduction']) ? (float) $validated['advance_deduction'] : 0.0;
        $maxDeductionByAdvance = max($netAdvance, 0);

        if ($advanceDeduction > $maxDeductionByAdvance) {
            throw ValidationException::withMessages([
                'advance_deduction' => 'Advance deduction cannot be greater than total advance.',
            ]);
        }

        if ($advanceDeduction > $finalSalary) {
            throw ValidationException::withMessages([
                'advance_deduction' => 'Advance deduction cannot be greater than salary of month.',
            ]);
        }

        $payableSalary = max($finalSalary - $advanceDeduction, 0);
        $monthName = Carbon::create($validated['year'], $validated['month'], 1)->format('F');
        $monthDir = public_path('Salaries/' . $monthName);
        File::ensureDirectoryExists($monthDir);

        $safeUserName = Str::slug($user->name, '_');
        $fileName = $safeUserName . '.pdf';
        $absoluteFilePath = $monthDir . DIRECTORY_SEPARATOR . $fileName;
        $relativeFilePath = 'Salaries/' . $monthName . '/' . $fileName;

        $summary = [
            'days_in_month' => $daysInMonth,
            'presents' => $presents,
            'offs' => $offs,
            'extra_off' => $extraOff,
            'single_day_salary' => $singleDaySalary,
            'hourly_rate' => $hourlyRate,
            'overtime_hours' => $overtimeHours,
            'adjusted_overtime_hours' => $adjustedOvertimeHours,
            'overtime_amount' => $overtimeAmount,
            'deduction' => $deduction,
            'monthly_salary' => $monthlySalary,
            'final_salary' => $finalSalary,
            'advance_deduction' => $advanceDeduction,
            'payable_salary' => $payableSalary,
            'total_taken' => $totalTaken,
            'total_given_back' => $totalGivenBack,
            'net_advance' => $netAdvance,
        ];

        $pdf = Pdf::loadView('payments.salary-slip-pdf', [
            'user' => $user,
            'month' => (int) $validated['month'],
            'year' => (int) $validated['year'],
            'allowedOff' => $allowedOff,
            'entries' => $entries,
            'dailyDetails' => $dailyDetails,
            'summary' => $summary,
        ])->setPaper('a4', 'portrait');

        DB::transaction(function () use (
            $pdf,
            $absoluteFilePath,
            $relativeFilePath,
            $user,
            $validated,
            $allowedOff,
            $monthlySalary,
            $finalSalary,
            $advanceDeduction,
            $payableSalary
        ) {
            File::put($absoluteFilePath, $pdf->output());

            Salary::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'month' => (int) $validated['month'],
                    'year' => (int) $validated['year'],
                ],
                [
                    'generated_by_user_id' => Auth::id(),
                    'allowed_off' => $allowedOff,
                    'monthly_salary' => $monthlySalary,
                    'final_salary' => $finalSalary,
                    'advance_deduction' => $advanceDeduction,
                    'payable_salary' => $payableSalary,
                    'file_path' => $relativeFilePath,
                    'generated_at' => now(),
                ]
            );

            WorkingHour::where('user_id', $user->id)
                ->whereMonth('date', $validated['month'])
                ->whereYear('date', $validated['year'])
                ->update(['payment_status' => 'paid']);

            if ($advanceDeduction > 0) {
                AdvancePayment::create([
                    'user_id' => $user->id,
                    'status' => 'received',
                    'amount' => $advanceDeduction,
                    'description' => 'Advance deduction added while generating salary slip for '
                        . Carbon::create($validated['year'], $validated['month'], 1)->format('F Y'),
                ]);
            }
        });

        return response()->json([
            'message' => 'Salary slip generated successfully.',
            'file_url' => asset($relativeFilePath),
            'already_generated' => false,
        ]);
    }

    public function salarySlipPreview(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'allowed_off' => 'nullable|integer|min:0|max:31',
            'advance_deduction' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $selectedMonthStart = Carbon::create($validated['year'], $validated['month'], 1)->startOfMonth();
        $currentMonthStart = now()->startOfMonth();

        if ($selectedMonthStart->gt($currentMonthStart)) {
            throw ValidationException::withMessages([
                'month' => 'You cannot generate salary for upcoming months.',
            ]);
        }

        $userJoinDate = Carbon::parse($user->created_at)->startOfDay();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

        if ($selectedMonthEnd->lt($userJoinDate)) {
            throw ValidationException::withMessages([
                'month' => 'Selected month/year is before this user registration date.',
            ]);
        }

        $entries = AdvancePayment::where('user_id', $validated['user_id'])
            ->whereMonth('created_at', $validated['month'])
            ->whereYear('created_at', $validated['year'])
            ->orderBy('created_at', 'desc')
            ->get();

        $advanceTotals = AdvancePayment::where('user_id', $validated['user_id'])
            ->whereDate('created_at', '<=', $selectedMonthEnd->toDateString())
            ->selectRaw("
                SUM(CASE WHEN status = 'given' THEN amount ELSE 0 END) as total_taken,
                SUM(CASE WHEN status = 'received' THEN amount ELSE 0 END) as total_given_back
            ")
            ->first();

        $daysInMonth = Carbon::create($validated['year'], $validated['month'], 1)->daysInMonth;
        $presentDates = WorkingHour::where('user_id', $validated['user_id'])
            ->whereMonth('date', $validated['month'])
            ->whereYear('date', $validated['year'])
            ->distinct()
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        $presents = count($presentDates);
        $offs = max($daysInMonth - $presents, 0);
        $allowedOff = isset($validated['allowed_off']) ? (int) $validated['allowed_off'] : 4;
        $extraOff = max($offs - $allowedOff, 0);
        $monthlySalary = (float) ($user->salary ?? 0);
        $singleDaySalary = $daysInMonth > 0 ? ($monthlySalary / $daysInMonth) : 0;

        $overtimeHourEntries = WorkingHour::where('user_id', $validated['user_id'])
            ->whereMonth('date', $validated['month'])
            ->whereYear('date', $validated['year'])
            ->pluck('overtime_hours')
            ->map(fn ($hours) => (float) ($hours ?? 0))
            ->filter(fn ($hours) => $hours > 0)
            ->values();

        $overtimeHours = (float) $overtimeHourEntries->sum();
        $adjustedOvertimeHours = (float) $overtimeHourEntries
            ->map(fn ($hours) => $hours > 3 ? ($hours + 2) : $hours)
            ->sum();
        $hourlyRate = $singleDaySalary / 8;
        $overtimeAmount = $hourlyRate * $adjustedOvertimeHours;
        $deduction = $singleDaySalary * $extraOff;
        $finalSalary = max(($monthlySalary - $deduction) + $overtimeAmount, 0);

        $startDate = Carbon::create($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::create($validated['year'], $validated['month'], 1)->endOfMonth();
        $presentDateMap = array_flip($presentDates);
        $dailyDetails = [];

        $workingRowsByDate = DB::table('working_hours')
            ->leftJoin('projects', 'projects.id', '=', 'working_hours.project_id')
            ->select(
                'working_hours.date',
                'working_hours.working_hours',
                'working_hours.overtime_hours',
                'working_hours.description',
                'projects.name as project_name'
            )
            ->where('working_hours.user_id', $validated['user_id'])
            ->whereMonth('working_hours.date', $validated['month'])
            ->whereYear('working_hours.date', $validated['year'])
            ->orderBy('working_hours.date')
            ->get()
            ->groupBy(fn ($row) => Carbon::parse($row->date)->format('Y-m-d'));

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $formatted = $date->format('Y-m-d');
            $rows = $workingRowsByDate->get($formatted, collect());

            if (!isset($presentDateMap[$formatted])) {
                $dailyDetails[] = [
                    'date' => $date->format('d/m/Y'),
                    'status' => 'absent',
                    'projects' => '-',
                    'working_hours' => 0,
                    'overtime_hours' => 0,
                    'adjusted_overtime_hours' => 0,
                    'description' => '-',
                ];
            } else {
                $projectNames = $rows->pluck('project_name')->filter()->unique()->values()->all();
                $descriptions = $rows->pluck('description')->filter()->unique()->values()->all();
                $rawOtHours = (float) $rows->sum(fn ($row) => (float) ($row->overtime_hours ?? 0));
                $dailyDetails[] = [
                    'date' => $date->format('d/m/Y'),
                    'status' => 'present',
                    'projects' => !empty($projectNames) ? implode(', ', $projectNames) : '-',
                    'working_hours' => (float) $rows->sum(fn ($row) => (float) ($row->working_hours ?? 0)),
                    'overtime_hours' => $rawOtHours,
                    'adjusted_overtime_hours' => $rawOtHours > 3 ? ($rawOtHours + 2) : $rawOtHours,
                    'description' => !empty($descriptions) ? implode(' | ', $descriptions) : '-',
                ];
            }
        }

        $totalTaken = (float) ($advanceTotals->total_taken ?? 0);
        $totalGivenBack = (float) ($advanceTotals->total_given_back ?? 0);
        $netAdvance = $totalTaken - $totalGivenBack;
        $advanceDeduction = isset($validated['advance_deduction']) ? (float) $validated['advance_deduction'] : 0.0;
        $maxDeductionByAdvance = max($netAdvance, 0);

        if ($advanceDeduction > $maxDeductionByAdvance) {
            throw ValidationException::withMessages([
                'advance_deduction' => 'Advance deduction cannot be greater than total advance.',
            ]);
        }

        if ($advanceDeduction > $finalSalary) {
            throw ValidationException::withMessages([
                'advance_deduction' => 'Advance deduction cannot be greater than salary of month.',
            ]);
        }

        $payableSalary = max($finalSalary - $advanceDeduction, 0);

        return view('payments.salary-slip-pdf', [
            'user' => $user,
            'month' => (int) $validated['month'],
            'year' => (int) $validated['year'],
            'allowedOff' => $allowedOff,
            'entries' => $entries,
            'dailyDetails' => $dailyDetails,
            'summary' => [
                'days_in_month' => $daysInMonth,
                'presents' => $presents,
                'offs' => $offs,
                'extra_off' => $extraOff,
                'single_day_salary' => $singleDaySalary,
                'hourly_rate' => $hourlyRate,
                'overtime_hours' => $overtimeHours,
                'adjusted_overtime_hours' => $adjustedOvertimeHours,
                'overtime_amount' => $overtimeAmount,
                'deduction' => $deduction,
                'monthly_salary' => $monthlySalary,
                'final_salary' => $finalSalary,
                'advance_deduction' => $advanceDeduction,
                'payable_salary' => $payableSalary,
                'total_taken' => $totalTaken,
                'total_given_back' => $totalGivenBack,
                'net_advance' => $netAdvance,
            ],
        ]);
    }

    public function index()
    {
        return view('payments.index', [
            'users' => User::where('role', 'labour')->orderBy('name')->get(),
            'advances' => AdvancePayment::with('user')->latest()->get(),
        ]);
    }

    public function storeAdvance(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
        ]);

        $advance = AdvancePayment::create($validated);
        $advance->load('user');

        return response()->json([
            'message' => 'Advance saved successfully.',
            'data' => $advance,
        ], 201);
    }

    public function updateAdvance(Request $request, AdvancePayment $advancePayment)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
        ]);

        $advancePayment->update($validated);
        $advancePayment->load('user');

        return response()->json([
            'message' => 'Advance updated successfully.',
            'data' => $advancePayment,
        ]);
    }

    public function destroyAdvance(AdvancePayment $advancePayment)
    {
        $advancePayment->delete();

        return response()->json([
            'message' => 'Advance deleted successfully.',
        ]);
    }

    public function salaries()
    {
        return view('payments.salaries', [
            'users' => User::where('role', 'labour')->orderBy('name')->get(),
        ]);
    }

    public function salariesSummary(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'allowed_off' => 'nullable|integer|min:0|max:31',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $selectedMonthStart = Carbon::create($validated['year'], $validated['month'], 1)->startOfMonth();
        $currentMonthStart = now()->startOfMonth();

        if ($selectedMonthStart->gt($currentMonthStart)) {
            throw ValidationException::withMessages([
                'month' => 'You cannot generate salary for upcoming months.',
            ]);
        }

        $userJoinDate = Carbon::parse($user->created_at)->startOfDay();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

        if ($selectedMonthEnd->lt($userJoinDate)) {
            throw ValidationException::withMessages([
                'month' => 'Selected month/year is before this user registration date.',
            ]);
        }

        $entries = AdvancePayment::where('user_id', $validated['user_id'])
            ->whereMonth('created_at', $validated['month'])
            ->whereYear('created_at', $validated['year'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($entry) {
            return [
                'id' => $entry->id,
                'status' => $entry->status,
                'amount' => (float) $entry->amount,
                'description' => $entry->description,
                'date' => $entry->created_at?->format('d/m/Y'),
            ];
        });

        $daysInMonth = Carbon::create($validated['year'], $validated['month'], 1)->daysInMonth;
        $presentDates = WorkingHour::where('user_id', $validated['user_id'])
            ->whereMonth('date', $validated['month'])
            ->whereYear('date', $validated['year'])
            ->distinct()
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        $presents = count($presentDates);

        $offs = max($daysInMonth - $presents, 0);
        $allowedOff = isset($validated['allowed_off']) ? (int) $validated['allowed_off'] : 4;
        $extraOff = max($offs - $allowedOff, 0);
        $monthlySalary = (float) ($user->salary ?? 0);
        $singleDaySalary = $daysInMonth > 0 ? ($monthlySalary / $daysInMonth) : 0;
        $overtimeHourEntries = WorkingHour::where('user_id', $validated['user_id'])
            ->whereMonth('date', $validated['month'])
            ->whereYear('date', $validated['year'])
            ->pluck('overtime_hours')
            ->map(fn ($hours) => (float) ($hours ?? 0))
            ->filter(fn ($hours) => $hours > 0)
            ->values();

        $overtimeHours = (float) $overtimeHourEntries->sum();
        $adjustedOvertimeHours = (float) $overtimeHourEntries
            ->map(fn ($hours) => $hours > 3 ? ($hours + 2) : $hours)
            ->sum();
        $hourlyRate = $singleDaySalary / 8;
        $overtimeAmount = $hourlyRate * $adjustedOvertimeHours;
        $deduction = $singleDaySalary * $extraOff;
        $finalSalary = max(($monthlySalary - $deduction) + $overtimeAmount, 0);

        $startDate = Carbon::create($validated['year'], $validated['month'], 1)->startOfMonth();
        $endDate = Carbon::create($validated['year'], $validated['month'], 1)->endOfMonth();
        $presentDateMap = array_flip($presentDates);
        $absentDates = [];
        $dailyDetails = [];

        $workingRowsByDate = DB::table('working_hours')
            ->leftJoin('projects', 'projects.id', '=', 'working_hours.project_id')
            ->select(
                'working_hours.date',
                'working_hours.working_hours',
                'working_hours.overtime_hours',
                'working_hours.description',
                'projects.name as project_name'
            )
            ->where('working_hours.user_id', $validated['user_id'])
            ->whereMonth('working_hours.date', $validated['month'])
            ->whereYear('working_hours.date', $validated['year'])
            ->orderBy('working_hours.date')
            ->get()
            ->groupBy(fn ($row) => Carbon::parse($row->date)->format('Y-m-d'));

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $formatted = $date->format('Y-m-d');
            $rows = $workingRowsByDate->get($formatted, collect());

            if (!isset($presentDateMap[$formatted])) {
                $absentDates[] = $date->format('d/m/Y');
                $dailyDetails[] = [
                    'date' => $date->format('d/m/Y'),
                    'status' => 'absent',
                    'projects' => '-',
                    'working_hours' => 0,
                    'overtime_hours' => 0,
                    'description' => '-',
                ];
            } else {
                $projectNames = $rows->pluck('project_name')->filter()->unique()->values()->all();
                $descriptions = $rows->pluck('description')->filter()->unique()->values()->all();
                $dailyDetails[] = [
                    'date' => $date->format('d/m/Y'),
                    'status' => 'present',
                    'projects' => !empty($projectNames) ? implode(', ', $projectNames) : '-',
                    'working_hours' => (float) $rows->sum(fn ($row) => (float) ($row->working_hours ?? 0)),
                    'overtime_hours' => (float) $rows->sum(fn ($row) => (float) ($row->overtime_hours ?? 0)),
                    'description' => !empty($descriptions) ? implode(' | ', $descriptions) : '-',
                ];
            }
        }

        $existingSalary = Salary::where('user_id', $validated['user_id'])
            ->where('month', (int) $validated['month'])
            ->where('year', (int) $validated['year'])
            ->first();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'salary' => (float) ($user->salary ?? 0),
            ],
            'entries' => $entries,
            'daily_details' => $dailyDetails,
            'existing_salary' => $existingSalary ? [
                'id' => $existingSalary->id,
                'file_url' => asset($existingSalary->file_path),
                'generated_at' => optional($existingSalary->generated_at)->format('d/m/Y h:i A'),
            ] : null,
            'salary_summary' => [
                'days_in_month' => $daysInMonth,
                'presents' => $presents,
                'offs' => $offs,
                'allowed_off' => $allowedOff,
                'extra_off' => $extraOff,
                'single_day_salary' => $singleDaySalary,
                'hourly_rate' => $hourlyRate,
                'overtime_hours' => $overtimeHours,
                'adjusted_overtime_hours' => $adjustedOvertimeHours,
                'overtime_amount' => $overtimeAmount,
                'deduction' => $deduction,
                'monthly_salary' => $monthlySalary,
                'final_salary' => $finalSalary,
                'absent_dates' => $absentDates,
            ],
        ]);
    }
}

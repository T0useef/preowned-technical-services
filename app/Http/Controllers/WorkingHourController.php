<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkingHourController extends Controller
{
    public function storeWorkingHours(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|exists:users,id',
            'date' => 'required|date_format:d/m/Y',
            'description' => 'nullable|string|max:1000',
        ]);

        $labourIds = User::whereIn('id', $validated['user_ids'])
            ->where('role', 'labour')
            ->pluck('id')
            ->all();

        if (count($labourIds) !== count($validated['user_ids'])) {
            return response()->json([
                'message' => 'Only users with labour role are allowed.',
            ], 422);
        }

        $date = Carbon::createFromFormat('d/m/Y', $validated['date'])->format('Y-m-d');
        $alreadySubmittedUsers = $this->getAlreadySubmittedUsers($labourIds, $date, 'working');

        if (!empty($alreadySubmittedUsers)) {
            return response()->json([
                'message' => 'These users already have working-hour entry for this date: ' . implode(', ', $alreadySubmittedUsers),
            ], 422);
        }

        DB::transaction(function () use ($validated, $labourIds, $date) {
            foreach ($labourIds as $labourId) {
                WorkingHour::create([
                    'user_id' => $labourId,
                    'project_id' => $validated['project_id'],
                    'added_by_user_id' => Auth::id(),
                    'date' => $date,
                    'working_hours' => null,
                    'overtime_hours' => null,
                    'description' => $validated['description'] ?? null,
                ]);
            }
        });

        return response()->json([
            'message' => 'Working hours saved successfully.',
            'count' => count($labourIds),
        ], 201);
    }

    public function storeOvertimeHours(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|exists:users,id',
            'date' => 'required|date_format:d/m/Y',
            'hours' => 'required|numeric|min:0.25|max:24',
            'description' => 'nullable|string|max:1000',
        ]);

        $labourIds = User::whereIn('id', $validated['user_ids'])
            ->where('role', 'labour')
            ->pluck('id')
            ->all();

        if (count($labourIds) !== count($validated['user_ids'])) {
            return response()->json([
                'message' => 'Only users with labour role are allowed.',
            ], 422);
        }

        $date = Carbon::createFromFormat('d/m/Y', $validated['date'])->format('Y-m-d');
        $alreadySubmittedUsers = $this->getAlreadySubmittedUsers($labourIds, $date, 'overtime');

        if (!empty($alreadySubmittedUsers)) {
            return response()->json([
                'message' => 'These users already have overtime entry for this date: ' . implode(', ', $alreadySubmittedUsers),
            ], 422);
        }

        DB::transaction(function () use ($validated, $labourIds, $date) {
            foreach ($labourIds as $labourId) {
                WorkingHour::create([
                    'user_id' => $labourId,
                    'project_id' => $validated['project_id'],
                    'added_by_user_id' => Auth::id(),
                    'date' => $date,
                    'working_hours' => null,
                    'overtime_hours' => $validated['hours'],
                    'description' => $validated['description'] ?? null,
                ]);
            }
        });

        return response()->json([
            'message' => 'Overtime hours saved successfully.',
            'count' => count($labourIds),
        ], 201);
    }

    private function getAlreadySubmittedUsers(array $userIds, string $date, string $type): array
    {
        $query = WorkingHour::whereIn('user_id', $userIds)
            ->whereDate('date', $date);

        if ($type === 'working') {
            $query->whereNull('overtime_hours');
        } else {
            $query->whereNotNull('overtime_hours');
        }

        $submittedUserIds = $query->pluck('user_id')->unique()->values()->all();

        if (empty($submittedUserIds)) {
            return [];
        }

        return User::whereIn('id', $submittedUserIds)
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }
}

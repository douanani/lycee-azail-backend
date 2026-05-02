<?php

// ============================================================
// app/Http/Controllers/API/DashboardController.php
// ============================================================
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{Resource, User, Announcement, AcademicYear};
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    // GET /api/v1/dashboard
    public function index(): JsonResponse
    {
        $currentYear = AcademicYear::current();

        return response()->json([
            'stats' => [
                'total_users'         => User::count(),
                'teachers'            => User::whereHas('role', fn($q) => $q->where('name', 'teacher'))->count(),
                'total_lessons'       => Resource::type('lesson')->count(),
                'total_exams'         => Resource::type('exam')->count(),
                'total_homework'      => Resource::type('homework')->count(),
                'total_downloads'     => Resource::sum('download_count'),
                'active_announcements'=> Announcement::active()->count(),
            ],
            'current_year'     => $currentYear,
            'recent_resources' => Resource::with(['uploader:id,name', 'subject', 'gradeLevel'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
            'top_downloaded'   => Resource::orderByDesc('download_count')
                ->limit(5)
                ->get(['id', 'title', 'type', 'download_count']),
        ]);
    }
}
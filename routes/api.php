<?php

// ============================================================
// routes/api.php
// ============================================================
use App\Http\Controllers\API\{
    AuthController,
    UserController,
    ResourceController,
    AnnouncementController,
    TimetableController,
    DashboardController,
    AcademicYearController,
};
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Public endpoints (no auth required) ────────────────
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });

    // Public read-only resource browsing for students
    Route::get('resources',              [ResourceController::class, 'index']);
    Route::get('resources/{resource}',   [ResourceController::class, 'show']);
    Route::get('resources/{resource}/download', [ResourceController::class, 'download']);
    Route::get('announcements',          [AnnouncementController::class, 'index']);
    Route::get('timetables',             [TimetableController::class, 'index']);

    // ── Authenticated endpoints ─────────────────────────────
    Route::middleware(['auth:sanctum', 'ensure.active'])->group(function () {

        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',      [AuthController::class, 'me']);

        // ── Admin only ────────────────────────────────────
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive']);

            Route::get('dashboard',                   [DashboardController::class, 'index']);

            Route::apiResource('academic-years', AcademicYearController::class);
            Route::post('academic-years/{academicYear}/set-current',
                [AcademicYearController::class, 'setCurrent']);

            Route::apiResource('grade-levels', \App\Http\Controllers\API\GradeLevelController::class);
            Route::apiResource('subjects',     \App\Http\Controllers\API\SubjectController::class);
            Route::apiResource('classrooms',   \App\Http\Controllers\API\ClassroomController::class);
        });

        // ── Teacher, Admin — resource management ──────────
        Route::middleware('role:admin,teacher')->group(function () {
            Route::post('resources',              [ResourceController::class, 'store']);
            Route::put('resources/{resource}',    [ResourceController::class, 'update']);
            Route::delete('resources/{resource}', [ResourceController::class, 'destroy']);
        });

        // ── Announcement management (role-filtered inside controller) ──
        Route::middleware('role:admin,counselor,admin_staff,supervisor')->group(function () {
            Route::post('announcements',                  [AnnouncementController::class, 'store']);
            Route::put('announcements/{announcement}',    [AnnouncementController::class, 'update']);
            Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy']);
        });

        // ── Timetable management ──────────────────────────
        Route::middleware('role:admin,supervisor')->group(function () {
            Route::post('timetables',              [TimetableController::class, 'store']);
            Route::post('timetables/bulk',         [TimetableController::class, 'bulk']);
            Route::put('timetables/{timetable}',   [TimetableController::class, 'update']);
            Route::delete('timetables/{timetable}',[TimetableController::class, 'destroy']);
        });

        // ── Notifications ─────────────────────────────────
        Route::get('notifications',           fn() => auth()->user()->unreadNotifications);
        Route::post('notifications/read-all', fn() => auth()->user()->unreadNotifications->markAsRead());
        Route::post('notifications/{id}/read', function (string $id) {
            auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
            return response()->json(['ok' => true]);
        });
    });
});

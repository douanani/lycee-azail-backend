<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{User, AcademicYear, GradeLevel, Subject, Classroom, Resource, Announcement, Timetable};
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    /**
     * GET /api/v1/backup/export
     * JSON export of core tables — NOT a full SQL dump.
     * File attachments (PDFs on disk) are NOT included; only their metadata/paths.
     */
    public function export(): StreamedResponse
    {
        $data = [
            'exported_at'    => now()->toIso8601String(),
            'academic_years' => AcademicYear::all(),
            'grade_levels'   => GradeLevel::with('subjects:id,name,code')->get(),
            'subjects'       => Subject::all(),
            'classrooms'     => Classroom::all(),
            'users'          => User::with('role:id,name')
                ->get(['id', 'role_id', 'name', 'email', 'phone', 'is_active', 'last_login_at', 'created_at']),
            'resources'      => Resource::all(),
            'announcements'  => Announcement::all(),
            'timetables'     => Timetable::all(),
        ];

        $filename = 'lycee-azail-backup-' . now()->format('Y-m-d_His') . '.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }, $filename, ['Content-Type' => 'application/json']);
    }
}
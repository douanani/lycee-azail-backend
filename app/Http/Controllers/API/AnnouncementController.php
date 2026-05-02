<?php

// ============================================================
// app/Http/Controllers/API/AnnouncementController.php
// ============================================================
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(private FileUploadService $fileService) {}

    // GET /api/v1/announcements
    public function index(Request $request): JsonResponse
    {
        $announcements = Announcement::with('author:id,name,role_id')
            ->active()
            ->when($request->audience, fn($q) => $q->where('audience', $request->audience))
            ->when($request->search,   fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->orderByDesc('published_at')
            ->paginate(10);

        return response()->json($announcements);
    }

    // POST /api/v1/announcements
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'audience'   => 'required|in:all,teachers,students,parents,guidance',
            'expires_at' => 'nullable|date|after:now',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        // Validate audience matches role permissions
        $this->validateAudiencePermission($user->role->name, $data['audience']);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $info = $this->fileService->upload($request->file('attachment'), 'announcements');
            $attachmentPath = $info['path'];
        }

        $announcement = Announcement::create([
            ...$data,
            'user_id'          => $user->id,
            'published_by_role'=> $user->role->name,
            'is_published'     => true,
            'published_at'     => now(),
            'attachment_path'  => $attachmentPath,
        ]);

        return response()->json($announcement->load('author:id,name'), 201);
    }

    // PUT /api/v1/announcements/{announcement}
    public function update(Request $request, Announcement $announcement): JsonResponse
    {
        $this->authorize('update', $announcement);

        $data = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'body'         => 'sometimes|string',
            'audience'     => 'sometimes|in:all,teachers,students,parents,guidance',
            'is_published' => 'sometimes|boolean',
            'expires_at'   => 'nullable|date',
        ]);

        $announcement->update($data);
        return response()->json($announcement);
    }

    // DELETE /api/v1/announcements/{announcement}
    public function destroy(Announcement $announcement): JsonResponse
    {
        $this->authorize('delete', $announcement);
        if ($announcement->attachment_path) {
            $this->fileService->delete($announcement->attachment_path);
        }
        $announcement->delete();
        return response()->json(['message' => 'تم الحذف.']);
    }

    private function validateAudiencePermission(string $role, string $audience): void
    {
        $permissions = [
            'admin'       => ['all', 'teachers', 'students', 'parents', 'guidance'],
            'counselor'   => ['students', 'guidance'],
            'admin_staff' => ['teachers', 'all'],
            'supervisor'  => ['students', 'teachers'],
        ];

        if (!in_array($audience, $permissions[$role] ?? [])) {
            abort(403, 'غير مصرح لك بنشر إعلانات لهذا الجمهور.');
        }
    }
}
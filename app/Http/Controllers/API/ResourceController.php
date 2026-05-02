<?php


// ============================================================
// app/Http/Controllers/API/ResourceController.php
// (Handles Lessons, Exams, Homework — filtered by `type`)
// ============================================================
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ResourceController extends Controller
{
    public function __construct(private FileUploadService $fileService) {}

    /**
     * GET /api/v1/resources
     * Query params: type, grade_level_id, subject_id, academic_year_id, semester, search
     */
    public function index(Request $request): JsonResponse
    {
        $resources = Resource::with(['uploader:id,name', 'gradeLevel', 'subject', 'academicYear'])
            ->published()
            ->when($request->type,             fn($q) => $q->type($request->type))
            ->when($request->grade_level_id,   fn($q) => $q->where('grade_level_id', $request->grade_level_id))
            ->when($request->subject_id,       fn($q) => $q->where('subject_id', $request->subject_id))
            ->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))
            ->when($request->semester,         fn($q) => $q->where('semester', $request->semester))
            ->search($request->search)
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($resources);
    }

    /**
     * GET /api/v1/resources/{resource}
     */
    public function show(Resource $resource): JsonResponse
    {
        $resource->load(['uploader:id,name', 'gradeLevel', 'subject', 'academicYear']);
        return response()->json($resource->append(['file_url', 'file_size_human']));
    }

    /**
     * POST /api/v1/resources
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'             => 'required|in:lesson,exam,homework,guide',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'grade_level_id'   => 'required|exists:grade_levels,id',
            'subject_id'       => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester'         => 'nullable|string|max:50',
            'is_published'     => 'boolean',
            'file'             => 'required|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:51200', // 50MB
        ]);

        $fileInfo = $this->fileService->upload($request->file('file'), 'resources');

        $resource = Resource::create([
            ...$data,
            'user_id'    => auth()->id(),
            'file_path'  => $fileInfo['path'],
            'file_name'  => $fileInfo['original_name'],
            'file_type'  => $fileInfo['extension'],
            'file_size'  => $fileInfo['size'],
        ]);

        return response()->json($resource->load(['gradeLevel', 'subject', 'academicYear']), 201);
    }

    /**
     * PUT /api/v1/resources/{resource}
     */
    public function update(Request $request, Resource $resource): JsonResponse
    {
        Gate::authorize('update', $resource);

        $data = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'description'  => 'nullable|string',
            'semester'     => 'nullable|string|max:50',
            'is_published' => 'sometimes|boolean',
            'file'         => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:51200',
        ]);

        if ($request->hasFile('file')) {
            $this->fileService->delete($resource->file_path);
            $fileInfo = $this->fileService->upload($request->file('file'), 'resources');
            $data['file_path'] = $fileInfo['path'];
            $data['file_name'] = $fileInfo['original_name'];
            $data['file_type'] = $fileInfo['extension'];
            $data['file_size'] = $fileInfo['size'];
        }

        $resource->update($data);
        return response()->json($resource->load(['gradeLevel', 'subject', 'academicYear']));
    }

    /**
     * DELETE /api/v1/resources/{resource}
     */
    public function destroy(Resource $resource): JsonResponse
    {
        Gate::authorize('delete', $resource);
        $this->fileService->delete($resource->file_path);
        $resource->delete();
        return response()->json(['message' => 'تم الحذف.']);
    }

    /**
     * GET /api/v1/resources/{resource}/download
     */
    public function download(Resource $resource): mixed
    {
        $resource->incrementDownload();
        return response()->download(
            storage_path('app/public/' . $resource->file_path),
            $resource->file_name
        );
    }
}

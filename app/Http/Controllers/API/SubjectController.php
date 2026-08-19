<?php

// ============================================================
// app/Http/Controllers/API/SubjectController.php
// ============================================================
namespace App\Http\Controllers\API;
 
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
 
class SubjectController extends Controller
{
    /**
     * GET /api/v1/subjects
     * Query params: grade_level_id
     */
    public function index(Request $request): JsonResponse
    {
        $subjects = Subject::withCount('resources')
            ->when(
                $request->grade_level_id,
                fn($q) => $q->whereHas(
                    'gradeLevels',
                    fn($r) => $r->where('grade_levels.id', $request->grade_level_id)
                )
            )
            ->when(
                $request->search,
                fn($q) => $q->where('name', 'like', "%{$request->search}%")
                             ->orWhere('code', 'like', "%{$request->search}%")
            )
            ->orderBy('name')
            ->get();
 
        return response()->json($subjects);
    }
 
    /**
     * POST /api/v1/subjects
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'name_fr'         => 'nullable|string|max:255',
            'code'            => 'required|string|max:10|unique:subjects',
            'icon'            => 'nullable|string|max:100',    // e.g. 'bi-calculator-fill'
            'color'           => 'nullable|string|max:7',      // e.g. '#4f46e5'
            'grade_level_ids' => 'nullable|array',
            'grade_level_ids.*' => 'exists:grade_levels,id',
        ]);
 
        $gradeLevelIds = $data['grade_level_ids'] ?? [];
        unset($data['grade_level_ids']);
 
        $subject = Subject::create($data);
 
        if (!empty($gradeLevelIds)) {
            $subject->gradeLevels()->sync($gradeLevelIds);
        }
 
        return response()->json($subject->load('gradeLevels:id,name,code'), 201);
    }
 
    /**
     * GET /api/v1/subjects/{subject}
     */
    public function show(Subject $subject): JsonResponse
    {
        $subject->load('gradeLevels:id,name,code')->loadCount('resources');
        return response()->json($subject);
    }
 
    /**
     * PUT /api/v1/subjects/{subject}
     */
    public function update(Request $request, Subject $subject): JsonResponse
    {
        $data = $request->validate([
            'name'            => 'sometimes|string|max:255',
            'name_fr'         => 'nullable|string|max:255',
            'code'            => 'sometimes|string|max:10|unique:subjects,code,' . $subject->id,
            'icon'            => 'nullable|string|max:100',
            'color'           => 'nullable|string|max:7',
            'grade_level_ids' => 'nullable|array',
            'grade_level_ids.*' => 'exists:grade_levels,id',
        ]);
 
        $gradeLevelIds = $data['grade_level_ids'] ?? null;
        unset($data['grade_level_ids']);
 
        $subject->update($data);
 
        if (!is_null($gradeLevelIds)) {
            $subject->gradeLevels()->sync($gradeLevelIds);
        }
 
        return response()->json($subject->load('gradeLevels:id,name,code'));
    }
 
    /**
     * DELETE /api/v1/subjects/{subject}
     */
    public function destroy(Subject $subject): JsonResponse
    {
        if ($subject->resources()->exists()) {
            return response()->json([
                'message' => 'لا يمكن حذف هذه المادة لأنها مرتبطة بملفات وموارد.',
            ], 422);
        }
 
        $subject->gradeLevels()->detach();
        $subject->delete();
 
        return response()->json(['message' => 'تم حذف المادة الدراسية.']);
    }
}
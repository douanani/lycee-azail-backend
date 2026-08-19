<?php

// ============================================================
// app/Http/Controllers/API/GradeLevelController.php
// ============================================================
namespace App\Http\Controllers\API;
 
use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
 
class GradeLevelController extends Controller
{
    /**
     * GET /api/v1/grade-levels
     */
    public function index(): JsonResponse
    {
        $levels = GradeLevel::withCount('resources')
            ->with('subjects:id,name,code,icon,color')
            ->orderBy('year_number')
            ->orderBy('name')
            ->get();
 
        return response()->json($levels);
    }
 
    /**
     * POST /api/v1/grade-levels
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:10|unique:grade_levels',
            'year_number' => 'required|integer|in:1,2,3',
            'stream'      => 'nullable|string|in:sciences,literature,common,technology',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);
 
        $subjectIds = $data['subject_ids'] ?? [];
        unset($data['subject_ids']);
 
        $level = GradeLevel::create($data);
 
        if (!empty($subjectIds)) {
            $level->subjects()->sync($subjectIds);
        }
 
        return response()->json($level->load('subjects:id,name,code,icon,color'), 201);
    }
 
    /**
     * GET /api/v1/grade-levels/{gradeLevel}
     */
    public function show(GradeLevel $gradeLevel): JsonResponse
    {
        $gradeLevel->load([
            'subjects:id,name,code,icon,color',
            'classrooms.academicYear',
        ])->loadCount('resources');
 
        return response()->json($gradeLevel);
    }
 
    /**
     * PUT /api/v1/grade-levels/{gradeLevel}
     */
    public function update(Request $request, GradeLevel $gradeLevel): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'code'        => 'sometimes|string|max:10|unique:grade_levels,code,' . $gradeLevel->id,
            'year_number' => 'sometimes|integer|in:1,2,3',
            'stream'      => 'nullable|string|in:sciences,literature,common,technology',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);
 
        $subjectIds = $data['subject_ids'] ?? null;
        unset($data['subject_ids']);
 
        $gradeLevel->update($data);
 
        if (!is_null($subjectIds)) {
            $gradeLevel->subjects()->sync($subjectIds);
        }
 
        return response()->json($gradeLevel->load('subjects:id,name,code,icon,color'));
    }
 
    /**
     * DELETE /api/v1/grade-levels/{gradeLevel}
     */
    public function destroy(GradeLevel $gradeLevel): JsonResponse
    {
        // Prevent deletion if linked resources exist
        if ($gradeLevel->resources()->exists()) {
            return response()->json([
                'message' => 'لا يمكن حذف هذا المستوى لأنه مرتبط بملفات وموارد.',
            ], 422);
        }
 
        $gradeLevel->subjects()->detach();
        $gradeLevel->delete();
 
        return response()->json(['message' => 'تم حذف المستوى الدراسي.']);
    }
}
 
<?php

// ============================================================
// app/Http/Controllers/API/ClassroomController.php
// ============================================================
namespace App\Http\Controllers\API;
 
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
 
class ClassroomController extends Controller
{
    /**
     * GET /api/v1/classrooms
     * Query params: grade_level_id, academic_year_id
     */
    public function index(Request $request): JsonResponse
    {
        $classrooms = Classroom::with(['gradeLevel:id,name,code,stream', 'academicYear:id,label'])
            ->when(
                $request->grade_level_id,
                fn($q) => $q->where('grade_level_id', $request->grade_level_id)
            )
            ->when(
                $request->academic_year_id,
                fn($q) => $q->where('academic_year_id', $request->academic_year_id)
            )
            ->when(
                $request->search,
                fn($q) => $q->where('name', 'like', "%{$request->search}%")
            )
            ->orderBy('name')
            ->get();
 
        return response()->json($classrooms);
    }
 
    /**
     * POST /api/v1/classrooms
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'grade_level_id'   => 'required|exists:grade_levels,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'required|string|max:50',
            'capacity'         => 'nullable|integer|min:1|max:60',
        ]);
 
        // Prevent duplicate classroom name within same year
        $exists = Classroom::where('grade_level_id',   $data['grade_level_id'])
                           ->where('academic_year_id', $data['academic_year_id'])
                           ->where('name', $data['name'])
                           ->exists();
 
        if ($exists) {
            return response()->json([
                'message' => 'يوجد قسم بهذا الاسم مسبقاً في نفس المستوى والسنة الدراسية.',
            ], 422);
        }
 
        $classroom = Classroom::create($data);
 
        return response()->json(
            $classroom->load(['gradeLevel:id,name,code', 'academicYear:id,label']),
            201
        );
    }
 
    /**
     * GET /api/v1/classrooms/{classroom}
     */
    public function show(Classroom $classroom): JsonResponse
    {
        $classroom->load([
            'gradeLevel:id,name,code,stream',
            'academicYear:id,label',
            'timetables.subject:id,name,icon,color',
            'timetables.teacher:id,name',
        ]);
 
        return response()->json($classroom);
    }
 
    /**
     * PUT /api/v1/classrooms/{classroom}
     */
    public function update(Request $request, Classroom $classroom): JsonResponse
    {
        $data = $request->validate([
            'grade_level_id'   => 'sometimes|exists:grade_levels,id',
            'academic_year_id' => 'sometimes|exists:academic_years,id',
            'name'             => 'sometimes|string|max:50',
            'capacity'         => 'nullable|integer|min:1|max:60',
        ]);
 
        // Check for duplicates when name/level/year is changing
        if (isset($data['name']) || isset($data['grade_level_id']) || isset($data['academic_year_id'])) {
            $checkName      = $data['name']             ?? $classroom->name;
            $checkLevelId   = $data['grade_level_id']   ?? $classroom->grade_level_id;
            $checkYearId    = $data['academic_year_id'] ?? $classroom->academic_year_id;
 
            $exists = Classroom::where('grade_level_id',   $checkLevelId)
                               ->where('academic_year_id', $checkYearId)
                               ->where('name', $checkName)
                               ->where('id', '!=', $classroom->id)
                               ->exists();
 
            if ($exists) {
                return response()->json([
                    'message' => 'يوجد قسم بهذا الاسم مسبقاً في نفس المستوى والسنة الدراسية.',
                ], 422);
            }
        }
 
        $classroom->update($data);
 
        return response()->json(
            $classroom->load(['gradeLevel:id,name,code', 'academicYear:id,label'])
        );
    }
 
    /**
     * DELETE /api/v1/classrooms/{classroom}
     */
    public function destroy(Classroom $classroom): JsonResponse
    {
        // Prevent deletion if timetable slots exist
        if ($classroom->timetables()->exists()) {
            return response()->json([
                'message' => 'لا يمكن حذف هذا القسم لأنه يحتوي على جدول حصص. احذف الجدول أولاً.',
            ], 422);
        }
 
        $classroom->delete();
 
        return response()->json(['message' => 'تم حذف القسم.']);
    }
}
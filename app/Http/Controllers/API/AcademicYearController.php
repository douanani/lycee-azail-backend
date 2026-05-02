<?php
// ============================================================
// app/Http/Controllers/API/AcademicYearController.php
// ============================================================
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(AcademicYear::orderByDesc('start_date')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'label'      => 'required|string|unique:academic_years',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'is_current' => 'boolean',
        ]);

        $year = AcademicYear::create($data);
        if ($year->is_current) $year->setCurrent();

        return response()->json($year, 201);
    }

    public function setCurrent(AcademicYear $academicYear): JsonResponse
    {
        $academicYear->setCurrent();
        return response()->json(['message' => "تم تعيين {$academicYear->label} كسنة دراسية حالية."]);
    }
}

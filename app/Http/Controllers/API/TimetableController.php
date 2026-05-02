<?php
// ============================================================
// app/Http/Controllers/API/TimetableController.php
// ============================================================
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    // GET /api/v1/timetables?classroom_id=&academic_year_id=
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'classroom_id'     => 'required|exists:classrooms,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $timetable = Timetable::with(['subject', 'teacher:id,name'])
            ->where('classroom_id', $request->classroom_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->orderByRaw("FIELD(day,'Sunday','Monday','Tuesday','Wednesday','Thursday')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return response()->json($timetable);
    }

    // POST /api/v1/timetables
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'classroom_id'     => 'required|exists:classrooms,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_id'       => 'required|exists:subjects,id',
            'user_id'          => 'nullable|exists:users,id',
            'day'              => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string',
            'is_break'         => 'boolean',
        ]);

        $slot = Timetable::create($data);
        return response()->json($slot->load(['subject', 'teacher:id,name']), 201);
    }

    // PUT /api/v1/timetables/{timetable}
    public function update(Request $request, Timetable $timetable): JsonResponse
    {
        $data = $request->validate([
            'subject_id' => 'sometimes|exists:subjects,id',
            'user_id'    => 'nullable|exists:users,id',
            'day'        => 'sometimes|in:Sunday,Monday,Tuesday,Wednesday,Thursday',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time'   => 'sometimes|date_format:H:i',
            'room'       => 'nullable|string',
            'is_break'   => 'boolean',
        ]);

        $timetable->update($data);
        return response()->json($timetable->load(['subject', 'teacher:id,name']));
    }

    // DELETE /api/v1/timetables/{timetable}
    public function destroy(Timetable $timetable): JsonResponse
    {
        $timetable->delete();
        return response()->json(['message' => 'تم الحذف.']);
    }

    /**
     * POST /api/v1/timetables/bulk
     * Replace an entire classroom's timetable at once.
     */
    public function bulk(Request $request): JsonResponse
    {
        $request->validate([
            'classroom_id'     => 'required|exists:classrooms,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'slots'            => 'required|array',
            'slots.*.subject_id' => 'required|exists:subjects,id',
            'slots.*.day'        => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday',
            'slots.*.start_time' => 'required|date_format:H:i',
            'slots.*.end_time'   => 'required|date_format:H:i',
        ]);

        // Delete existing and re-insert
        Timetable::where('classroom_id', $request->classroom_id)
                 ->where('academic_year_id', $request->academic_year_id)
                 ->delete();

        $slots = collect($request->slots)->map(fn($s) => [
            ...$s,
            'classroom_id'     => $request->classroom_id,
            'academic_year_id' => $request->academic_year_id,
            'created_at'       => now(),
            'updated_at'       => now(),
        ])->toArray();

        Timetable::insert($slots);
        return response()->json(['message' => 'تم حفظ جدول الحصص.'], 201);
    }
}
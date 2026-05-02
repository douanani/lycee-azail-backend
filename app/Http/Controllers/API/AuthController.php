<?php
// ============================================================
// app/Http/Controllers/API/AuthController.php
// ============================================================
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::with('role')
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['بيانات تسجيل الدخول غير صحيحة.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'الحساب معطّل. تواصل مع المدير.'], 403);
        }

        $user->update(['last_login_at' => now()]);

        // Ability tokens scoped to the user's role
        $abilities = $this->getAbilitiesForRole($user->role->name);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role->name,
                'label' => $user->role->label,
                'avatar'=> $user->avatar,
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج بنجاح.']);
    }

    /**
     * GET /api/v1/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('role');
        return response()->json([
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'phone'  => $user->phone,
            'role'   => $user->role->name,
            'label'  => $user->role->label,
            'avatar' => $user->avatar,
        ]);
    }

    private function getAbilitiesForRole(string $role): array
    {
        return match ($role) {
            'admin'       => ['*'],
            'teacher'     => ['resources:read', 'resources:write', 'resources:delete-own'],
            'counselor'   => ['announcements:write', 'resources:read'],
            'admin_staff' => ['announcements:write', 'resources:read'],
            'supervisor'  => ['timetables:write', 'announcements:write', 'resources:read'],
            default       => ['resources:read'],
        };
    }
}

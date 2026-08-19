<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::with('role')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['بيانات تسجيل الدخول غير صحيحة.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'الحساب معطّل. تواصل مع المدير.'], 403);
        }

        $user->update(['last_login_at' => now()]);

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

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج بنجاح.']);
    }

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

    /**
     * PUT /api/v1/auth/change-password
     * Any authenticated user changes their own password.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['كلمة المرور الحالية غير صحيحة.'],
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Revoke every other active session — force re-login elsewhere
        $currentTokenId = $user->currentAccessToken()->id;
        $user->tokens()->where('id', '!=', $currentTokenId)->delete();

        return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح.']);
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
<?php

// ============================================================
// app/Http/Middleware/CheckRole.php
// ============================================================
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Usage in routes: ->middleware('role:admin,supervisor')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->role) {
            return response()->json(['message' => 'غير مصرح.'], 401);
        }

        if (!$user->hasRole($roles)) {
            return response()->json([
                'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد.',
                'required_roles' => $roles,
            ], 403);
        }

        return $next($request);
    }
}
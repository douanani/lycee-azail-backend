<?php


// ============================================================
// app/Http/Middleware/EnsureUserIsActive.php
// ============================================================
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->is_active) {
            // Revoke token for disabled accounts
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'الحساب معطّل. تواصل مع المدير.'], 403);
        }

        return $next($request);
    }
}

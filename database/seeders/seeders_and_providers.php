<?php











// ============================================================
// bootstrap/app.php — Middleware registration (Laravel 11)
// ============================================================
// In Laravel 11, add to bootstrap/app.php:

/*
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(...)
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'          => \App\Http\Middleware\CheckRole::class,
            'ensure.active' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(...)
    ->create();
*/




// ============================================================
// config/cors.php — Required for React SPA
// ============================================================
/*
return [
    'paths'                    => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods'          => ['*'],
    'allowed_origins'          => [env('FRONTEND_URL', 'http://localhost:3000')],
    'allowed_origins_patterns' => [],
    'allowed_headers'          => ['*'],
    'exposed_headers'          => [],
    'max_age'                  => 0,
    'supports_credentials'     => true,   // Required for Sanctum SPA
];
*/


// ============================================================
// config/sanctum.php — Key settings
// ============================================================
/*
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,localhost:3000')),

// For pure token-based (React with Bearer tokens), stateful is NOT needed.
// Use createToken() in AuthController (already done above).
*/

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Force JSON for API
        $middleware->appendToGroup('api', [
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);

        // IMPORTANT: Don't redirect guests to "login" (API should return 401 JSON)
        $middleware->redirectGuestsTo(function ($request) {
            return $request->is('api/*') ? null : route('login');
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        });

    })
    ->create();



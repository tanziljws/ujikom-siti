<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.petugas' => \App\Http\Middleware\AuthPetugas::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle database connection errors gracefully
        $exceptions->render(function (QueryException $e, $request) {
            \Illuminate\Support\Facades\Log::error('Database error: ' . $e->getMessage(), [
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'connection' => env('DB_CONNECTION'),
            ]);
            
            if (app()->environment('production')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Database connection error',
                        'message' => 'Please check your database configuration',
                    ], 500);
                }
                // Return simple error response if view doesn't exist
                return response('Database connection error. Please check your configuration.', 500);
            }
            
            // In development, show more details
            return response()->json([
                'error' => 'Database connection error',
                'message' => $e->getMessage(),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'connection' => env('DB_CONNECTION'),
            ], 500);
        });
    })->create();

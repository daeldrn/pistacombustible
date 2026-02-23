<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'pista',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // API middleware group
        $middleware->api(prepend: [
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);
        
        // Rate limiting para API
        $middleware->throttleApi();

        // Route middleware aliases (solo los necesarios)
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de excepciones para rutas API
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            // Solo para rutas API
            if ($request->is('api/*') || $request->is('pista/*')) {
                
                // Validación de errores
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return \App\Http\Responses\ApiResponse::validationError(
                        $e->errors(),
                        'Error de validación'
                    );
                }

                // Modelo no encontrado
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return \App\Http\Responses\ApiResponse::notFound(
                        'Recurso no encontrado'
                    );
                }

                // No autorizado
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return \App\Http\Responses\ApiResponse::unauthorized(
                        'No autenticado. Por favor inicie sesión.'
                    );
                }

                // Acceso prohibido
                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return \App\Http\Responses\ApiResponse::forbidden(
                        $e->getMessage() ?: 'No tiene permisos para realizar esta acción'
                    );
                }

                // Rate limiting
                if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
                    return \App\Http\Responses\ApiResponse::error(
                        'Demasiadas solicitudes. Por favor intente más tarde.',
                        null,
                        429
                    );
                }

                // Error de base de datos
                if ($e instanceof \Illuminate\Database\QueryException) {
                    \Illuminate\Support\Facades\Log::error('Database error', [
                        'message' => $e->getMessage(),
                        'sql' => $e->getSql() ?? 'N/A',
                    ]);

                    return \App\Http\Responses\ApiResponse::serverError(
                        config('app.debug') 
                            ? 'Error de base de datos: ' . $e->getMessage()
                            : 'Error al procesar la solicitud'
                    );
                }

                // Errores generales en producción
                if (!config('app.debug')) {
                    \Illuminate\Support\Facades\Log::error('API Error', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    return \App\Http\Responses\ApiResponse::serverError(
                        'Ha ocurrido un error. Por favor intente nuevamente.'
                    );
                }

                // En modo debug, mostrar detalles
                return \App\Http\Responses\ApiResponse::error(
                    $e->getMessage(),
                    [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => explode("\n", $e->getTraceAsString()),
                    ],
                    500
                );
            }
        });
    })
    ->withProviders([
        \App\Providers\AppServiceProvider::class,
        \App\Providers\EventServiceProvider::class,
    ])
    ->create();

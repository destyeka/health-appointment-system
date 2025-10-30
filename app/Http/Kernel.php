<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // You can add global middleware here, e.g.,
        // \App\Http\Middleware\TrustProxies::class,
        // \App\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\VerifyCsrfToken::class,
            // You can add other web middleware here if needed
        ],
        'api' => [
            // API middleware (e.g., throttling) if needed
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'manage.queue' => \App\Http\Middleware\CheckQueueManagementPermission::class,
    ];
}

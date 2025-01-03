<?php

use App\Http\Middleware\TenantStoreUrl;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            $centralDomain = config('tenancy.central_domains');

            foreach ($centralDomain as $domain) {
                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/web.php'));
            }

            Route::middleware('web')->group(base_path('routes/tenant.php'));
        },
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('universal', []);
        $middleware->append(TenantStoreUrl::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

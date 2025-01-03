<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Jobs\CreateTenant;
use Illuminate\Support\Facades\{Event, Route};
use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportFileUploads\FilePreviewController;
use Livewire\Livewire;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Middleware\{InitializeTenancyByDomain, InitializeTenancyByDomainOrSubdomain};
use Stancl\Tenancy\{Events, Jobs, Listeners, Middleware};

class TenancyServiceProvider extends ServiceProvider
{
    // By default, no namespace is used to support the callable array syntax.
    public static string $controllerNamespace = '';

    /** @return array<class-string,mixed> */
    public function events(): array
    {
        return [
            // Tenant events
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class  => [
                JobPipeline::make([
                    Jobs\CreateDatabase::class,
                    Jobs\MigrateDatabase::class,
                    CreateTenant::class,
                    // Jobs\SeedDatabase::class,

                    // Your own jobs to prepare the tenant.
                    // Provision API keys, create S3 buckets, anything you want!

                ])->send(function (Events\TenantCreated $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],
            Events\SavingTenant::class   => [],
            Events\TenantSaved::class    => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class  => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class  => [
                JobPipeline::make([
                    Jobs\DeleteDatabase::class,
                ])->send(function (Events\TenantDeleted $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class  => [],
            Events\SavingDomain::class   => [],
            Events\DomainSaved::class    => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class  => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class  => [],

            // Database events
            Events\DatabaseCreated::class    => [],
            Events\DatabaseMigrated::class   => [],
            Events\DatabaseSeeded::class     => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class    => [],

            // Tenancy events
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class  => [
                Listeners\BootstrapTenancy::class,
            ],

            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class  => [
                Listeners\RevertToCentralContext::class,
            ],

            Events\BootstrappingTenancy::class      => [],
            Events\TenancyBootstrapped::class       => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class  => [],

            // Resource syncing
            Events\SyncedResourceSaved::class => [
                Listeners\UpdateSyncedResource::class,
            ],

            // Fired only when a synced resource is changed in a different DB than the origin DB (to avoid infinite loops)
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->bootEvents();
        $this->mapRoutes();

        $this->makeTenancyMiddlewareHighestPriority();

        FilePreviewController::$middleware = ['web', 'universal', InitializeTenancyByDomain::class];

        $host = request()?->getHost(); //@phpstan-ignore-line

        if (!in_array($host, config('tenancy.central_domains'), true)) {
            Livewire::setUpdateRoute(static function ($handle) {
                return Route::post('/livewire/update', $handle)
                    ->middleware([
                        'web',
                        'universal',
                        InitializeTenancyByDomainOrSubdomain::class,
                    ]);
            });
        }

    }

    protected function bootEvents(): void
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }

                Event::listen($event, $listener);
            }
        }
    }

    protected function mapRoutes(): void
    {
        $this->app->booted(function () {
            if (file_exists(base_path('routes/tenant.php'))) {
                Route::namespace(static::$controllerNamespace)
                    ->group(base_path('routes/tenant.php'));
            }
        });
    }

    protected function makeTenancyMiddlewareHighestPriority(): void
    {
        $tenancyMiddleware = [
            // Even higher priority than the initialization middleware
            Middleware\PreventAccessFromCentralDomains::class,

            Middleware\InitializeTenancyByDomain::class,
            Middleware\InitializeTenancyBySubdomain::class,
            Middleware\InitializeTenancyByDomainOrSubdomain::class,
            Middleware\InitializeTenancyByPath::class,
            Middleware\InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }
}

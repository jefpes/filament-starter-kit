<?php

namespace App\Providers\Filament;

use App\Http\Middleware\{ApplyTenantScopes, FilamentSettings};
use App\Livewire\UserProfile;
use App\Models\Store;
use Filament\Http\Middleware\{Authenticate, DisableBladeIconComponents, DispatchServingFilamentEvent};
use Filament\Navigation\MenuItem;
use Filament\Support\Enums\MaxWidth;
use Filament\{Panel, PanelProvider};
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\{AuthenticateSession, StartSession};
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Stancl\Tenancy\Middleware\{InitializeTenancyByDomain, PreventAccessFromCentralDomains};

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->tenant(Store::class, 'slug')
            ->tenantMenu(fn () => auth_user()->stores()->count() > 1)
            ->login()
            ->breadcrumbs(false)
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->discoverClusters(in: app_path('Filament/Admin/Clusters'), for: 'App\\Filament\\Admin\\Clusters')
            ->userMenuItems([
                'profile' => MenuItem::make()->icon('heroicon-o-user'),
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->slug('me')
                    ->shouldShowEditProfileForm(false)
                    ->shouldRegisterNavigation(false)
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowBrowserSessionsForm()
                    ->customProfileComponents([
                        UserProfile::class,
                    ]),
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn () => auth_user()->name)
                    ->url(fn (): string => EditProfilePage::getUrl()),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                FilamentSettings::class,
            ])
            ->middleware([
                'universal',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
            ], isPersistent: true)
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenantMiddleware([
                ApplyTenantScopes::class,
            ], isPersistent: true)
            ->spa()
            ->maxContentWidth(MaxWidth::Full);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use Symfony\Component\HttpFoundation\Response;

class FilamentSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        FilamentColor::register([
            'danger'  => (auth_user()->tertiary_color ?? Color::Red),
            'gray'    => (auth_user()->primary_color ?? Color::Gray),
            'info'    => (auth_user()->quaternary_color ?? Color::Blue),
            'primary' => (auth_user()->secondary_color ?? Color::Indigo),
            'success' => (auth_user()->quinary_color ?? Color::Green),
            'warning' => (auth_user()->senary_color ?? Color::Yellow),
            'pink'    => (auth_user()->septenary_color ?? Color::Pink),
        ]);

        $settings   = Settings::query()->first();
        $name       = $settings->name ?? env('APP_NAME');
        $favicon    = $settings->favicon ?? 'nao existe';
        $logo       = $settings->logo ?? 'nao existe';
        $font       = auth_user()->font ?? 'Inter';
        $navigation = auth_user()->navigation_mode ?? true;

        Filament::getPanel()
            ->topNavigation($navigation)
            ->sidebarFullyCollapsibleOnDesktop()
            ->font($font)
            ->brandName($name);

        if (Storage::disk('public')->exists($favicon)) {
            Filament::getPanel()
                ->favicon(image_path($favicon));
        }

        if (Storage::disk('public')->exists($logo)) {
            Filament::getPanel()
                ->brandLogo(image_path($logo))
                ->brandLogoHeight(fn () => Auth::check() ? '3rem' : '6rem');
        }

        return $next($request);
    }
}

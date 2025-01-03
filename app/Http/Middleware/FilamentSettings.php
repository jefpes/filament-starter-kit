<?php

namespace App\Http\Middleware;

use App\Models\Company;
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
            'danger'  => (Auth::user()->settings->tertiary_color ?? Color::Red),
            'gray'    => (Auth::user()->settings->primary_color ?? Color::Gray),
            'info'    => (Auth::user()->settings->quaternary_color ?? Color::Blue),
            'primary' => (Auth::user()->settings->secondary_color ?? Color::Indigo),
            'success' => (Auth::user()->settings->quinary_color ?? Color::Green),
            'warning' => (Auth::user()->settings->senary_color ?? Color::Yellow),
            'pink'    => (Auth::user()->settings->septenary_color ?? Color::Pink),
        ]);

        $company = Company::query()->first();
        $favicon = $company->favicon ?? 'nao existe';
        $logo    = $company->logo ?? 'nao existe';

        Filament::getPanel()
            ->topNavigation(Auth::user()->settings->navigation_mode ?? true)
            ->sidebarFullyCollapsibleOnDesktop()
            ->font(Auth::user()->settings->font ?? 'Inter')
            ->brandName($company->name ?? env('APP_NAME'));

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

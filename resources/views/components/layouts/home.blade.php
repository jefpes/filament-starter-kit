<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
@php
$company = App\Models\Settings::query()->first();

// Construir os estilos dinâmicos
$bodyStyles = [];
$bgStyles = [];
if ($company) {
    if ($company?->font_family) {
        $bodyStyles[] = "{$company?->font_family};";
    }
    if ($company?->body_bg_color) {
        $bodyStyles[] = "background-color: {$company?->body_bg_color};";
    }
    if ($company?->font_color) {
        $bodyStyles[] = "color: {$company?->font_color};";
    }
    if ($company?->bg_img) {
        $bgStyles[] = "background-image: url('" . image_path($company?->bg_img) . "');";
        $bgStyles[] = "background-repeat: repeat;";
        $bgStyles[] = "opacity: {$company?->bg_img_opacity};";
    }
}

    // Transformar os arrays de estilos em strings
$bodyStyleString = implode(' ', $bodyStyles);
$bgStyleString = implode(' ', $bgStyles);
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name ?? 'Motor Market' }}</title>
    @if($company && $company->favicon)
    <link rel="icon" type="image/x-icon" href="{{ image_path($company->favicon) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen relative" style="{{ $bodyStyleString }}">
    @if($company && $company->bg_img)
    <div class="bg-overlay" style="{{ $bgStyleString }}"></div>
    @endif

    <header class="shadow-md relative"
        style="{{ $company && $company->nav_color ? 'background-color: ' . $company->nav_color . ';' : '' }}">
        <livewire:home.navigation />
    </header>

    <main class="flex-grow container mx-auto md:px-4 py-4 relative">
        {{ $slot }}
    </main>

    <footer class="shadow-md mt-auto relative"
        style="{{ $company && $company->footer_color ? 'background-color: ' . $company->footer_color . ';' : '' }} . {{ $company && $company->footer_text_color ? '; color: ' . $company->footer_text_color . ';' : '' }}">
        <livewire:home.footer />
    </footer>

    @livewireScripts
</body>

</html>

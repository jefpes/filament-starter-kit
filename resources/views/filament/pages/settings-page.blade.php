<x-filament-panels::page>
<form wire:submit="save">
    {{ $this->form }}

    <div class="py-4 flex flex-row-reverse">
        <x-filament::button type="submit">
            {{ __('Save') }}
        </x-filament::button>
    </div>
</form>
</x-filament-panels::page>

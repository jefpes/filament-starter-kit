<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\{TextInput};
use Filament\Forms\{Set};
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Livewire\Component as Livewire;

class ZipCode extends TextInput
{
    /**
     * @param array<string> $setFields
     */
    public function viaCep(
        string $errorMessage = 'CEP inválido.',
        array $setFields = []
    ): static {

        $viaCepRequest = function ($state, $livewire, $set, $component, string $errorMessage, array $setFields) {
            $livewire->validateOnly($component->getKey());
            $request = Http::get("https://viacep.com.br/ws/$state/json/")->json();

            foreach ($setFields as $field => $value) {
                $set($field, $request[$value] ?? null);
            }

            if (Arr::has($request, 'erro')) {
                throw ValidationException::withMessages([
                    $component->getKey() => $errorMessage,
                ]);
            }
        };

        $this->mask('99999-999')
            ->length(9)
            ->required()
            ->suffixAction(
                Action::make('search-zip-code')
                    ->icon('heroicon-o-magnifying-glass')
                    ->label('Buscar')
                    ->action(function ($state, Livewire $livewire, Set $set, Component $component) use ($errorMessage, $setFields, $viaCepRequest) {
                        $viaCepRequest($state, $livewire, $set, $component, $errorMessage, $setFields);
                    })
            );

        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->viaCep(setFields: [
            'state'        => 'estado',
            'city'         => 'localidade',
            'neighborhood' => 'bairro',
            'street'       => 'logradouro',
            'number'       => 'numero',
            'complement'   => 'complemento',
            // 'uf'           => 'uf',
            // 'region'       => 'regiao',
            // 'ibge'         => 'ibge',
            // 'ddd'          => 'ddd',
            // 'siafi'        => 'siafi',
        ]);
    }
}

<?php

namespace App\Tools;

use App\Forms\Components\{PhoneInput, ZipCode};
use Filament\Forms\Components\{Component, Grid, Repeater, TextInput, Textarea};

class FormFields
{
    public static function setAddressFields(): Component
    {
        return Repeater::make('addresses')
                ->grid(2)
                ->columnSpanFull()
                ->hiddenLabel()
                ->addActionLabel(__('Add Address'))
                ->relationship('addresses')
                ->schema([
                    Grid::make()->columns(['md' => 2, 1])->schema([
                        ZipCode::make('zip_code'),
                        TextInput::make('state')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('city')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('neighborhood')
                            ->required()
                            ->maxLength(255),
                        Grid::make()
                            ->columns(5)
                            ->schema([
                                TextInput::make('street')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(['md' => 4, 'sm' => 5]),
                                TextInput::make('number')
                                    ->columnSpan(['md' => 1, 'sm' => 5])
                                    ->minValue(0),
                            ]),
                        Textarea::make('complement')
                            ->maxLength(255)
                            ->rows(1)
                            ->columnSpanFull(),
                    ]),
                ]);
    }

    public static function setPhoneFields(): Repeater
    {
        return Repeater::make('phones')
                ->grid(2)
                ->columnSpanFull()
                ->hiddenLabel()
                ->addActionLabel(__('Add phone'))
                ->relationship('phones')
                ->schema([
                    Grid::make()
                        ->columns(5)
                        ->schema([
                            Grid::make()
                                ->columnSpan(2)
                                ->columns(2)
                            ->schema([
                                TextInput::make('ddi')
                                    ->required()
                                    ->label('DDI')
                                    ->validationAttribute('DDI')
                                    ->prefixIcon('heroicon-o-plus')
                                    ->mask('99999')
                                    ->default('55')
                                    ->minLength(1)
                                    ->maxLength(5),
                                TextInput::make('ddd')
                                    ->required()
                                    ->label('DDD')
                                    ->validationAttribute('DDD')
                                    ->mask('99')
                                    ->length(2),
                            ]),
                            PhoneInput::make('number')
                                ->columnSpan(2)
                                ->required(),
                            TextInput::make('type')
                                ->columnSpan(['md' => 1, 'sm' => 'full'])
                                ->maxLength(50),
                        ]),
                ]);
    }
}

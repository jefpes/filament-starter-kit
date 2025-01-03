<?php

namespace App\Filament\Master\Resources;

use App\Filament\Master\Resources\TenantResource\{Pages};
use App\Models\Tenant;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Str;
use Leandrocfe\FilamentPtbrFormFields\Money;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('Database Name')
                            ->live(debounce: 1000, onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('id', Str::slug($state, '_')))
                            ->unique()
                            ->visibleOn(['create'])
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->visibleOn(['create'])
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->visibleOn(['create'])
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->visibleOn(['create'])
                            ->maxLength(255),
                        Forms\Components\TextInput::make('domain')
                            ->visibleOn(['create']),
                        Money::make('monthly_fee')
                            ->label('Monthly fee')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Database Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monthly_fee')
                    ->money('BRL'),
                Tables\Columns\ToggleColumn::make('active')->onColor('success')->offColor('danger'),
                Tables\Columns\ToggleColumn::make('marketplace')->onColor('success')->offColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
        ];
    }
}

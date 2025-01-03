<?php

namespace App\Filament\Master\Resources;

use App\Filament\Master\Resources\TenantResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Models\{Tenant};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Str;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('domain')
                            ->unique()
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 700)
                            ->afterStateUpdated(fn ($set, $get) => $set('domain', Str::slug($get('domain')))),
                        MoneyInput::make('monthly_fee'),
                        Forms\Components\TextInput::make('due_day')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(28)
                            ->mask('99'),

                        Forms\Components\Fieldset::make('Master User')
                            ->columnSpanFull()
                            ->columns(2)
                            ->visibleOn('create')
                            ->schema([
                                Forms\Components\TextInput::make('user_name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('user_email')
                                    ->label('Email')
                                    ->unique(table: 'users', column: 'email')
                                    ->email()
                                    ->live()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('user_password')
                                    ->label('Password')
                                    ->password()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn (?string $state) => filled($state))
                                    ->confirmed()
                                    ->maxLength(8),
                                Forms\Components\TextInput::make('user_password_confirmation')
                                    ->label('Password Confirmation')
                                    ->password()
                                    ->requiredWith('password')
                                    ->dehydrated(false)
                                    ->maxLength(8),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monthly_fee')
                    ->money('BRL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_day')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('include_in_marketplace'),
                Tables\Columns\ToggleColumn::make('is_active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->authorize(fn () => auth_user()->roles()->pluck('hierarchy')->min() === 0), //@phpstan-ignore-line
                Tables\Actions\DeleteAction::make()->authorize(fn () => auth_user()->roles()->pluck('hierarchy')->min() === 0), //@phpstan-ignore-line
                Tables\Actions\RestoreAction::make()->authorize(fn () => auth_user()->roles()->pluck('hierarchy')->min() === 0), //@phpstan-ignore-line
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
            'edit'   => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}

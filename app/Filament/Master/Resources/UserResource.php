<?php

namespace App\Filament\Master\Resources;

use App\Filament\Master\Resources\UserResource\{Pages};
use App\Models\{User};
use Filament\Forms\Components\{CheckboxList};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static bool $isScopedToTenant = false;

    protected static ?string $recordTitleAttribute = 'email';

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->visibleOn('create')
                    ->confirmed()
                    ->maxLength(8),
                Forms\Components\TextInput::make('password_confirmation')
                    ->visibleOn('create')
                    ->password()
                    ->requiredWith('password')
                    ->dehydrated(false)
                    ->maxLength(8),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        CheckboxList::make('roles')
                            ->relationship(
                                'roles',
                                'name',
                                modifyQueryUsing: fn ($query) => $query->orderBy('name')->where('hierarchy', '>=', auth_user()->roles->min('hierarchy')) //@phpstan-ignore-line
                            )
                            ->columns(['sm' => 1, 'md' => 3])
                            ->gridDirection('row')
                            ->bulkToggleable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Permission;
use App\Filament\Admin\Clusters\ManagementCluster;
use App\Filament\Admin\Resources\UserResource\{Pages};
use App\Models\{User};
use Filament\Forms\Components\{CheckboxList};
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $cluster = ManagementCluster::class;

    protected static ?int $navigationSort = 12;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return auth_user()->navigation_mode ? SubNavigationPosition::Start : SubNavigationPosition::Top;
    }

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
                        CheckboxList::make('stores')
                            ->relationship(
                                'stores',
                                'name',
                                modifyQueryUsing: fn ($query) => $query->orderBy('name')
                                ->when(
                                    auth_user()->hasAbility(Permission::MASTER->value) === false,
                                    fn ($query) => $query->whereIn('id', auth_user()->stores->pluck('id')->toArray())
                                )
                            )
                            ->columns(['sm' => 1, 'md' => 2])
                            ->gridDirection('row')
                            ->bulkToggleable(),
                    ]),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        CheckboxList::make('roles')
                            ->relationship(
                                'roles',
                                'name',
                                modifyQueryUsing: fn ($query) => $query->orderBy('name')->where('hierarchy', '>=', Auth::user()->roles->min('hierarchy')) //@phpstan-ignore-line
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
                Tables\Columns\TextColumn::make('people.name')
                    ->label('Person')
                    ->sortable(),
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

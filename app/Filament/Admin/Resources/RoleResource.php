<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Clusters\ManagementCluster;
use App\Filament\Admin\Resources\RoleResource\{Pages};
use App\Models\Ability;
use App\Models\{Role};
use App\Policies\RolePolicy;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Auth;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = ManagementCluster::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return auth_user()->navigation_mode ? SubNavigationPosition::Start : SubNavigationPosition::Top;
    }

    public static function getModelLabel(): string
    {
        return __('Role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100)
                    ->rules([unique_within_tenant_rule(static::$model)]),
                Forms\Components\Select::make('hierarchy')
                    ->options(
                        function () {
                            $user = Auth::user();

                            $roles = $user->roles->pluck('hierarchy'); //@phpstan-ignore-line

                            if ($roles->isNotEmpty()) {
                                $minHierarchy = $roles->min();

                                return collect(range($minHierarchy, 100))->mapWithKeys(fn ($value) => [$value => $value]);
                            }

                            return [];
                        }
                    )
                    ->searchable()
                    ->optionsLimit(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth_user()->tenant_id === null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hierarchy')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('add-abilities')
                    ->requiresConfirmation()
                    ->slideOver()
                    ->modalHeading(__('Abilities'))
                    ->modalWidth('5xl')
                    ->modalDescription(null)
                    ->modalIcon(null)
                    ->label('Abilities')
                    ->translateLabel()
                    ->icon('heroicon-o-shield-check')
                    ->iconSize('md')
                    ->color('success')
                    ->fillForm(function ($record) {
                        $abilities = Role::find($record->id)->abilities->pluck('id')->toArray(); //@phpstan-ignore-line

                        return ['abilities' => $abilities];
                    })
                    ->form([
                        Forms\Components\ToggleButtons::make('abilities')
                            ->options(
                                function () {
                                    // Carregar as habilidades com o id e nome
                                    $abilities = Ability::query()->orderBy('id')->pluck('name', 'id')->toArray();

                                    // Traduzir os nomes das habilidades usando os arquivos de tradução
                                    return collect($abilities)->mapWithKeys(function ($name, $id) {
                                        // Retornar a chave como o id e o valor como o nome traduzido
                                        return [$id => __($name)];
                                    })->toArray();
                                }
                            )
                            ->multiple()
                            ->columns([
                                'sm' => 3,
                                'xl' => 4,
                            ]),
                    ])
                    ->action(function (array $data, Role $role) {
                        $role->abilities()->sync($data['abilities']);
                    })
                    ->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Abilities updated'))
                            ->send();
                    })
                    ->authorize('addAbilities', RolePolicy::class),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}

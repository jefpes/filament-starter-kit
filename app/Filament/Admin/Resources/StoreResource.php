<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Clusters\ManagementCluster;
use App\Filament\Admin\Resources\StoreResource\{Pages};
use App\Forms\Components\{ZipCode};
use App\Models\Store;
use App\Tools\{FormFields, PhotosRelationManager};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Tables};
use Illuminate\Support\Str;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $cluster = ManagementCluster::class;

    protected static ?int $navigationSort = 11;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return auth_user()->navigation_mode ? SubNavigationPosition::Start : SubNavigationPosition::Top;
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $isScopedToTenant = false;

    public static function getModelLabel(): string
    {
        return __('Store');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Stores');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true, debounce: 1000)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state, '-')))
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Subdomain')
                            ->required()
                            ->readOnly()
                            ->maxLength(255),
                        Forms\Components\Grid::make()
                        ->columns(2)
                        ->schema([
                            Forms\Components\Grid::make()
                            ->columnSpan(1)
                            ->schema([
                                ZipCode::make('zip_code'),
                                Forms\Components\TextInput::make('state')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('neighborhood')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Grid::make()
                                    ->columns(5)
                                    ->schema([
                                        Forms\Components\TextInput::make('street')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(['md' => 4, 'sm' => 5]),
                                        Forms\Components\TextInput::make('number')
                                            ->columnSpan(['md' => 1, 'sm' => 5])
                                            ->minValue(0),
                                    ]),
                                Forms\Components\Textarea::make('complement')
                                    ->maxLength(255)
                                    ->rows(1)
                                    ->columnSpanFull(),
                            ]),
                            Forms\Components\Grid::make()->columnSpan(1)->columns(1)->schema([
                                FormFields::setPhoneFields()->grid(1),
                            ]),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('neighborhood')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('street')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('complement')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phones.full_phone')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit'   => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}

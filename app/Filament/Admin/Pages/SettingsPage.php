<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Clusters\ManagementCluster;
use App\Models\Settings;
use Filament\Forms\{Form};
use Filament\Notifications\Notification;
use Filament\Pages\{Page, SubNavigationPosition};
use Filament\{Forms};
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class SettingsPage extends Page
{
    protected static string $view = 'filament.pages.settings';

    protected static ?int $navigationSort = 4;

    protected static ?string $cluster = ManagementCluster::class;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        static::$subNavigationPosition = auth_user()->navigation_mode ? SubNavigationPosition::Start : SubNavigationPosition::Top;

        if (Auth::user()->settings === null) {//@phpstan-ignore-line
            Settings::query()->create(['user_id' => Auth::id()]);
        }

        try {
            $this->form->fill(Auth::user()->settings->toArray()); //@phpstan-ignore-line
        } catch (\Throwable) {
            redirect(request()->header('Referer'));
        }
    }

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public function getTitle(): string | Htmlable
    {
        return __('Settings');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Theme'))->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Select::make('font')
                        ->allowHtml()
                        ->options([
                            'Times New Roman' => "<span style='font-family:Times New Roman, Times, serif'>Times New Roman</span>",
                            'Roboto'          => "<span style='font-family:Roboto, sans-serif'>Roboto</span>",
                            'Arial'           => "<span style='font-family:Arial, sans-serif'>Arial</span>",
                            'Courier New'     => "<span style='font-family:Courier New, Courier, monospace'>Courier New</span>",
                            'Georgia'         => "<span style='font-family:Georgia, serif'>Georgia</span>",
                            'Lucida Console'  => "<span style='font-family:Lucida Console, Monaco, monospace'>Lucida Console</span>",
                            'Tahoma'          => "<span style='font-family:Tahoma, Geneva, sans-serif'>Tahoma</span>",
                            'Trebuchet MS'    => "<span style='font-family:Trebuchet MS, sans-serif'>Trebuchet MS</span>",
                            'Verdana'         => "<span style='font-family:Verdana, Geneva, sans-serif'>Verdana</span>",
                            'Open Sans'       => "<span style='font-family:Open Sans, sans-serif'>Open Sans</span>",
                            'Inter'           => "<span style='font-family:Inter, sans-serif'>Inter</span>",
                        ])
                        ->native(false),
                        Forms\Components\Select::make('navigation_mode')
                            ->label(__('Navigation bar'))
                            ->options([
                                false => __('Sidebar'),
                                true  => __('Topbar'),
                            ])
                            ->native(true)
                            ->live(),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('Primary color'),
                        Forms\Components\ColorPicker::make('secondary_color')
                            ->label('Secondary color'),
                        Forms\Components\ColorPicker::make('tertiary_color')
                            ->label('Tertiary color'),
                        Forms\Components\ColorPicker::make('quaternary_color')
                            ->label('Quaternary color'),
                        Forms\Components\ColorPicker::make('quinary_color')
                            ->label('Quinary color'),
                        Forms\Components\ColorPicker::make('senary_color')
                            ->label('Senary color'),
                    ]),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Settings::query()->whereUserId(Auth::id())->update($this->form->getState()); //@phpstan-ignore-line
        Notification::make()->body(__('Theme updated successfully'))->icon('heroicon-o-check-circle')->iconColor('success')->send();
        redirect(request()->header('Referer'));
    }
}

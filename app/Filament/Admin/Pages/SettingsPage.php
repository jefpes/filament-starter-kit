<?php

namespace App\Filament\Admin\Pages;

use App\Enums\{Permission};
use App\Filament\Admin\Clusters\ManagementCluster;
use App\Models\{Settings};
use Filament\Forms\Components\{Section};
use Filament\Forms\{Form};
use Filament\Notifications\Notification;
use Filament\Pages\{Page, SubNavigationPosition};
use Filament\{Forms};
use Leandrocfe\FilamentPtbrFormFields\Money;

class SettingsPage extends Page
{
    protected static string $view = 'filament.pages.settings-page';

    protected static ?string $cluster = ManagementCluster::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static bool $isScopedToTenant = false;

    public Settings $settings;

    public static function canAccess(): bool
    {
        $user = auth_user();

        return $user->hasAbility(Permission::MASTER->value);
    }

    protected static ?int $navigationSort = 15;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        static::$subNavigationPosition = auth_user()->navigation_mode ? SubNavigationPosition::Start : SubNavigationPosition::Top;
        $this->settings                = Settings::query()->first();
        $this->form->fill($this->settings->toArray()); //@phpstan-ignore-line
    }

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public function getTitle(): string
    {
        return __('Settings');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->maxLength(50)
                        ->required(),
                    Forms\Components\TextInput::make('cnpj')
                        ->label('CNPJ')
                        ->mask('99.999.999/9999-99')
                        ->length(18)
                        ->validationAttribute('CNPJ'),
                    Forms\Components\DatePicker::make('opened_in')
                        ->label('Opened in'),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-m-envelope'),
                    Forms\Components\MarkdownEditor::make('about')
                        ->label('About')
                        ->disableToolbarButtons([
                            'attachFiles',
                        ])
                        ->columnSpanFull(),
                ])->columns(3),
                Section::make('Redes Sociais')->schema([
                    Forms\Components\TextInput::make('whatsapp')
                        ->label('Whatsapp')
                        ->maxLength(255)
                        ->prefixIcon('icon-whatsapp'),
                    Forms\Components\TextInput::make('x')
                        ->label('X')
                        ->maxLength(255)
                        ->prefixIcon('icon-twitter'),
                    Forms\Components\TextInput::make('instagram')
                        ->label('Instagram')
                        ->maxLength(255)
                        ->prefixIcon('icon-instagram'),
                    Forms\Components\TextInput::make('facebook')
                        ->label('Facebook')
                        ->maxLength(255)
                        ->prefixIcon('icon-facebook'),
                    Forms\Components\TextInput::make('linkedin')
                        ->label('Linkedin')
                        ->maxLength(255)
                        ->prefixIcon('icon-linkedin'),
                    Forms\Components\TextInput::make('youtube')
                        ->label('Youtube')
                        ->maxLength(255)
                        ->prefixIcon('icon-youtube'),
                ])->columns(2),
                Section::make('Taxas')->schema([
                    Money::make('interest_rate_sale')
                        ->label('Interest rate sales')
                        ->prefix(null)
                        ->suffix('%'),
                    Money::make('interest_rate_installment')
                        ->label('Interest rate installment')
                        ->prefix(null)
                        ->suffix('%'),
                    Money::make('late_fee')->label('Late fee'),
                ])->columns(3),
                Section::make('Home Page')->schema([
                    Forms\Components\FileUpload::make('logo')
                        ->image()
                        ->directory('settings'),
                    Forms\Components\FileUpload::make('bg_img')
                        ->label('Backgroud image')
                        ->image()
                        ->directory('settings'),
                    Forms\Components\FileUpload::make('favicon')
                        ->image()
                        ->directory('settings'),
                    Forms\Components\Select::make('bg_img_opacity')
                        ->label('Backgroud image opacity')
                        ->options([
                            '0'   => '0%',
                            '0.1' => '10%',
                            '0.2' => '20%',
                            '0.3' => '30%',
                            '0.4' => '40%',
                            '0.5' => '50%',
                            '0.6' => '60%',
                            '0.7' => '70%',
                            '0.8' => '80%',
                            '0.9' => '90%',
                            '1'   => '100%',
                        ])
                        ->native(false),
                    Forms\Components\Select::make('font_family')
                    ->allowHtml()
                    ->options([
                        "font-family:Times New Roman, Times, serif"     => "<span style='font-family:Times New Roman, Times, serif'>Times New Roman</span>",
                        'font-family:Roboto, sans-serif'                => "<span style='font-family:Roboto, sans-serif'>Roboto</span>",
                        'font-family:Arial, sans-serif'                 => "<span style='font-family:Arial, sans-serif'>Arial</span>",
                        'font-family:Courier New, Courier, monospace'   => "<span style='font-family:Courier New, Courier, monospace'>Courier New</span>",
                        'font-family:Georgia, serif'                    => "<span style='font-family:Georgia, serif'>Georgia</span>",
                        'font-family:Lucida Console, Monaco, monospace' => "<span style='font-family:Lucida Console, Monaco, monospace'>Lucida Console</span>",
                        'font-family:Tahoma, Geneva, sans-serif'        => "<span style='font-family:Tahoma, Geneva, sans-serif'>Tahoma</span>",
                        'font-family:Trebuchet MS, sans-serif'          => "<span style='font-family:Trebuchet MS, sans-serif'>Trebuchet MS</span>",
                        'font-family:Verdana, Geneva, sans-serif'       => "<span style='font-family:Verdana, Geneva, sans-serif'>Verdana</span>",
                        'font-family:Open Sans, sans-serif'             => "<span style='font-family:Open Sans, sans-serif'>Open Sans</span>",
                        'font-family:Inter, sans-serif'                 => "<span style='font-family:Inter, sans-serif'>Inter</span>",
                    ])
                    ->native(false),
                    Forms\Components\ColorPicker::make('font_color')
                        ->label('Font color'),
                    Forms\Components\ColorPicker::make('promo_price_color')
                        ->label('Promo price color'),
                    Forms\Components\ColorPicker::make('body_bg_color')
                        ->label('Body background color'),
                    Forms\Components\ColorPicker::make('card_color')
                        ->label('Card background color'),
                    Forms\Components\ColorPicker::make('card_text_color')
                        ->label('Card text color'),
                    Forms\Components\ColorPicker::make('nav_color')
                        ->label('Heading background color'),
                    Forms\Components\ColorPicker::make('footer_color')
                        ->label('Footer background color'),
                    Forms\Components\ColorPicker::make('footer_text_color')
                        ->label('Footer text color'),
                    Forms\Components\ColorPicker::make('link_color')
                        ->label('Link color'),
                    Forms\Components\ColorPicker::make('link_text_color')
                        ->label('Link text color'),
                    Forms\Components\ColorPicker::make('btn_1_color')
                        ->label('Button color 1'),
                    Forms\Components\ColorPicker::make('btn_1_text_color')
                        ->label('Button text color 1'),
                    Forms\Components\ColorPicker::make('btn_2_color')
                        ->label('Button color 2'),
                    Forms\Components\ColorPicker::make('btn_2_text_color')
                        ->label('Button text color 2'),
                    Forms\Components\ColorPicker::make('btn_3_color')
                        ->label('Button color 3'),
                    Forms\Components\ColorPicker::make('btn_3_text_color')
                        ->label('Button text color 3'),
                    Forms\Components\ColorPicker::make('select_color')
                        ->label('Select color'),
                    Forms\Components\ColorPicker::make('select_text_color')
                        ->label('Select text color'),
                    Forms\Components\ColorPicker::make('check_color')
                        ->label('Check color'),
                    Forms\Components\ColorPicker::make('check_text_color')
                        ->label('Check text color'),
                ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState(); //@phpstan-ignore-line

        $this->settings->fill($data);
        $this->settings->save();

        Notification::make()->body(__('Settings updated successfully'))->icon('heroicon-o-check-circle')->iconColor('success')->send();
    }
}

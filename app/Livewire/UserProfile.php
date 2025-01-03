<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Components\{FileUpload, Grid, Section, TextInput};
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Joaopaulolndev\FilamentEditProfile\Livewire\EditProfileForm;

class UserProfile extends EditProfileForm
{
    public function mount(): void
    {
        $this->user = $this->getUser();

        $this->userClass = get_class($this->user);

        $this->form->fill($this->user->only( //@phpstan-ignore-line
            config('filament-edit-profile.avatar_column', 'avatar_url'),
            'name',
            'email',
            'primary_color',
            'secondary_color',
            'tertiary_color',
            'quaternary_color',
            'quinary_color',
            'senary_color',
            'font',
            'navigation_mode',
            'avatar_url',
        ));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament-edit-profile::default.profile_information'))
                    ->aside()
                    ->description(__('filament-edit-profile::default.profile_information_description'))
                    ->schema([
                        Grid::make()->columns(3)->schema([
                            FileUpload::make('avatar_url')
                                ->label(__('filament-edit-profile::default.avatar'))
                                ->avatar()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('avatars'),
                            Grid::make()->columns(1)->columnSpan(2)->schema([
                                TextInput::make('name')
                            ->label(__('filament-edit-profile::default.name'))
                            ->required(),
                                TextInput::make('email')
                                    ->label(__('filament-edit-profile::default.email'))
                                    ->email()
                                    ->required()
                                    ->unique($this->userClass, ignorable: $this->user),
                            ]),
                        ]),

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
                                ->required()
                                ->options([
                                    false => __('Sidebar'),
                                    true  => __('Topbar'),
                                ]),
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
    public function updateProfile(): void
    {
        try {
            $data = $this->form->getState(); //@phpstan-ignore-line

            $this->user->update($data);
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.saved_successfully'))
            ->send();

        redirect(request()->header('Referer'));
    }
}

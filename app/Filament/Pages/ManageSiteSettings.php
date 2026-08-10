<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ManageSiteSettings extends Page
{
    protected static ?string $slug = 'site-settings';

    protected string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return 'Настройки сайта';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Сайт';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public function getTitle(): string
    {
        return 'Настройки сайта';
    }

    public function mount(): void
    {
        $this->form->fill(
            $this->getRecord()->attributesToArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    FileUpload::make('logo')
                        ->label('Логотип')
                        ->image()
                        ->disk('public')
                        ->directory('site')
                        ->visibility('public'),

                    FileUpload::make('favicon')
                        ->label('Favicon')
                        ->image()
                        ->disk('public')
                        ->directory('site')
                        ->visibility('public'),

                    FileUpload::make('hero_image')
                        ->label('Изображение в центральном блоке')
                        ->image()
                        ->disk('public')
                        ->directory('site')
                        ->visibility('public'),

                    TextInput::make('footer_email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    Tabs::make('Языки')
                        ->tabs([
                            $this->languageTab('Русский', 'ru'),
                            $this->languageTab('English', 'en'),
                            $this->languageTab('Հայերեն', 'am'),
                        ])
                        ->persistTab()
                        ->id('site-language-tabs'),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Сохранить изменения')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    private function languageTab(string $label, string $locale): Tab
    {
        return Tab::make($label)
            ->schema([
                TextInput::make("site_name_{$locale}")
                    ->label('Название сайта')
                    ->required()
                    ->maxLength(255),

                TextInput::make("hero_title_{$locale}")
                    ->label('Главный заголовок')
                    ->required()
                    ->maxLength(255),

                TextInput::make("form_title_{$locale}")
                    ->label('Заголовок над номером')
                    ->required()
                    ->maxLength(255),

                TextInput::make("input_placeholder_{$locale}")
                    ->label('Placeholder поля')
                    ->maxLength(255),

                TextInput::make("button_text_{$locale}")
                    ->label('Текст кнопки')
                    ->required()
                    ->maxLength(100),

                Textarea::make("helper_text_{$locale}")
                    ->label('Поясняющий текст')
                    ->rows(4),

                TextInput::make("footer_title_{$locale}")
                    ->label('Заголовок футера')
                    ->maxLength(255),

                Textarea::make("footer_address_{$locale}")
                    ->label('Адрес / контакты')
                    ->rows(3),

                TextInput::make("copyright_{$locale}")
                    ->label('Copyright')
                    ->maxLength(255),

                TextInput::make("seo_title_{$locale}")
                    ->label('SEO Title')
                    ->maxLength(255),

                Textarea::make("seo_description_{$locale}")
                    ->label('SEO Description')
                    ->rows(3),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord();
        $record->fill($data);
        $record->save();

        Notification::make()
            ->success()
            ->title('Настройки сохранены')
            ->send();
    }

    public function getRecord(): SiteSetting
    {
        return SiteSetting::query()->firstOrCreate([
            'id' => 1,
        ]);
    }
}

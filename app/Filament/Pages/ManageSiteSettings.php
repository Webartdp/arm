<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
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
                    Section::make('Брендинг и изображения')
                        ->columns(2)
                        ->schema([
                            FileUpload::make('logo')
                                ->label('Логотип')
                                ->image()
                                ->disk('public')
                                ->directory('site')
                                ->visibility('public')
                                ->preventFilePathTampering(),

                            FileUpload::make('favicon')
                                ->label('Favicon')
                                ->image()
                                ->disk('public')
                                ->directory('site')
                                ->visibility('public')
                                ->preventFilePathTampering(),

                            FileUpload::make('hero_image')
                                ->label('Изображение в блоке проверки')
                                ->image()
                                ->disk('public')
                                ->directory('site')
                                ->visibility('public')
                                ->preventFilePathTampering(),

                            FileUpload::make('about_image')
                                ->label('Изображение страницы «О системе»')
                                ->image()
                                ->disk('public')
                                ->directory('site')
                                ->visibility('public')
                                ->preventFilePathTampering(),

                            FileUpload::make('footer_left_image')
                                ->label('Изображение футера слева')
                                ->image()
                                ->disk('public')
                                ->directory('site')
                                ->visibility('public')
                                ->preventFilePathTampering(),

                            FileUpload::make('footer_right_image')
                                ->label('Изображение футера справа')
                                ->image()
                                ->disk('public')
                                ->directory('site')
                                ->visibility('public')
                                ->preventFilePathTampering(),

                            TextInput::make('footer_email')
                                ->label('Email в футере')
                                ->email()
                                ->maxLength(255),
                        ]),

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
                Section::make('Шапка и главный экран')
                    ->columns(2)
                    ->schema([
                        TextInput::make("site_name_{$locale}")
                            ->label('Название сайта')
                            ->required()
                            ->maxLength(255),

                        TextInput::make("nav_about_{$locale}")
                            ->label('Пункт меню: О системе')
                            ->maxLength(255),

                        TextInput::make("nav_statistics_{$locale}")
                            ->label('Пункт меню: Статистика')
                            ->maxLength(255),

                        TextInput::make("hero_title_{$locale}")
                            ->label('Главный заголовок')
                            ->required()
                            ->maxLength(255),

                        TextInput::make("hero_subtitle_{$locale}")
                            ->label('Подзаголовок')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Форма проверки')
                    ->columns(2)
                    ->schema([
                        TextInput::make("form_title_{$locale}")
                            ->label('Заголовок над кодом')
                            ->required()
                            ->maxLength(255),

                        TextInput::make("input_placeholder_{$locale}")
                            ->label('Placeholder кода')
                            ->maxLength(255),

                        TextInput::make("date_placeholder_{$locale}")
                            ->label('Placeholder даты')
                            ->maxLength(255),

                        TextInput::make("button_text_{$locale}")
                            ->label('Текст кнопки')
                            ->required()
                            ->maxLength(100),

                        Textarea::make("helper_text_{$locale}")
                            ->label('Поясняющий текст')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Страница «О системе»')
                    ->schema([
                        TextInput::make("about_title_{$locale}")
                            ->label('Заголовок страницы')
                            ->maxLength(255),

                        Textarea::make("about_intro_{$locale}")
                            ->label('Основной текст')
                            ->rows(6),

                        Repeater::make("about_items_{$locale}")
                            ->label('Список пунктов')
                            ->simple(
                                TextInput::make('text')
                                    ->label('Пункт')
                                    ->maxLength(500)
                            )
                            ->addActionLabel('Добавить пункт')
                            ->reorderable(),
                    ]),

                Section::make('Страница «Статистика»')
                    ->schema([
                        TextInput::make("statistics_title_{$locale}")
                            ->label('Заголовок страницы')
                            ->maxLength(255),

                        Textarea::make("statistics_intro_{$locale}")
                            ->label('Поясняющий текст')
                            ->rows(3),

                        Repeater::make("statistics_items_{$locale}")
                            ->label('Страны и проценты')
                            ->schema([
                                TextInput::make('country')
                                    ->label('Страна')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('value')
                                    ->label('Процент')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['country'] ?? null)
                            ->addActionLabel('Добавить страну')
                            ->reorderable(),
                    ]),

                Section::make('Футер')
                    ->columns(2)
                    ->schema([
                        TextInput::make("footer_title_{$locale}")
                            ->label('Заголовок футера')
                            ->maxLength(255),

                        TextInput::make("copyright_{$locale}")
                            ->label('Copyright')
                            ->maxLength(255),

                        Textarea::make("footer_address_{$locale}")
                            ->label('Адрес / контакты')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('SEO')
                    ->schema([
                        TextInput::make("seo_title_{$locale}")
                            ->label('Главная — SEO Title')
                            ->maxLength(255),

                        Textarea::make("seo_description_{$locale}")
                            ->label('Главная — SEO Description')
                            ->rows(3),

                        TextInput::make("about_seo_title_{$locale}")
                            ->label('О системе — SEO Title')
                            ->maxLength(255),

                        Textarea::make("about_seo_description_{$locale}")
                            ->label('О системе — SEO Description')
                            ->rows(2),

                        TextInput::make("statistics_seo_title_{$locale}")
                            ->label('Статистика — SEO Title')
                            ->maxLength(255),

                        Textarea::make("statistics_seo_description_{$locale}")
                            ->label('Статистика — SEO Description')
                            ->rows(2),
                    ]),
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

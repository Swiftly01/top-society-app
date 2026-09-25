<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterResource\Pages\CreateNewsletter;
use App\Filament\Resources\NewsletterResource\Pages\EditNewsletter;
use App\Filament\Resources\NewsletterResource\Pages\ListNewsletters;
use App\Models\Newsletter;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class NewsletterResource extends Resource
{
    protected static ?string $model = Newsletter::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|UnitEnum|null $navigationGroup = 'Newsletters';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create'
                    ? $set('slug', Str::slug($state))
                    : null),

            TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            TextInput::make('badge')
                ->required()
                ->maxLength(50)
                ->helperText('e.g. "Daily", "Weekly", "Weekend" — shown as a small tag on the plan card.'),

            Textarea::make('description')
                ->required()
                ->rows(3),

            Grid::make(3)->schema([
                Toggle::make('notify_on_article_publish')
                    ->label('Instant Article Alerts')
                    ->helperText('Emails this newsletter\'s subscribers every time any article is published. Use sparingly.'),

                Toggle::make('is_default')
                    ->helperText('The newsletter generic "Subscribe" buttons (homepage, footer) subscribe to when no specific newsletter is chosen.'),

                Toggle::make('is_active')
                    ->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('badge'),
                TextColumn::make('subscriptions_count')->counts('subscriptions')->label('Subscribers'),
                IconColumn::make('notify_on_article_publish')->boolean()->label('Article Alerts'),
                IconColumn::make('is_default')->boolean()->label('Default'),
                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletters::route('/'),
            'create' => CreateNewsletter::route('/create'),
            'edit' => EditNewsletter::route('/{record}/edit'),
        ];
    }
}
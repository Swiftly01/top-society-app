<?php

namespace App\Filament\Resources;

use App\Enums\NewsletterEditionStatus;
use App\Filament\Resources\NewsletterEditionResource\Pages\CreateNewsletterEdition;
use App\Filament\Resources\NewsletterEditionResource\Pages\EditNewsletterEdition;
use App\Filament\Resources\NewsletterEditionResource\Pages\ListNewsletterEditions;
use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use App\Services\NewsletterEditionService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class NewsletterEditionResource extends Resource
{
    protected static ?string $model = NewsletterEdition::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Editions (Archive)';

    protected static string|UnitEnum|null $navigationGroup = 'Newsletters';

    protected static ?string $modelLabel = 'Newsletter Edition';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('newsletter_id')
                ->label('Newsletter')
                ->options(fn () => Newsletter::query()->active()->pluck('name', 'id'))
                ->required()
                ->searchable(),

            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create'
                    ? $set('slug', Str::slug($state))
                    : null),

            TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->helperText('Used in the public URL: /newsletter/archive/{slug}'),

            Textarea::make('excerpt')
                ->rows(2)
                ->maxLength(500),

            RichEditor::make('body')
                ->required()
                ->columnSpanFull()
                ->helperText('This is what goes both in the email and on the public "read online" page.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->limit(50),
                TextColumn::make('newsletter.name')->label('Newsletter')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('sent_at')->dateTime()->sortable()->placeholder('Not sent'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    NewsletterEditionStatus::Draft->value => 'Draft',
                    NewsletterEditionStatus::Sent->value => 'Sent',
                ]),
                SelectFilter::make('newsletter_id')->label('Newsletter')->relationship('newsletter', 'name'),
            ])
            ->recordActions([
                Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (NewsletterEdition $record) => "/newsletter/archive/{$record->slug}")
                    ->openUrlInNewTab()
                    ->visible(fn (NewsletterEdition $record) => $record->isSent()),

                Action::make('send')
                    ->label('Send Now')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription(fn (NewsletterEdition $record) => "This emails every active subscriber of \"{$record->newsletter?->name}\" and cannot be undone.")
                    ->visible(fn (NewsletterEdition $record) => ! $record->isSent())
                    ->action(fn (NewsletterEdition $record) => app(NewsletterEditionService::class)->send($record)),

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
            'index' => ListNewsletterEditions::route('/'),
            'create' => CreateNewsletterEdition::route('/create'),
            'edit' => EditNewsletterEdition::route('/{record}/edit'),
        ];
    }
}
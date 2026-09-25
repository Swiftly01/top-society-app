<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MagazineResource\Pages\CreateMagazine;
use App\Filament\Resources\MagazineResource\Pages\EditMagazine;
use App\Filament\Resources\MagazineResource\Pages\ListMagazines;
use App\Models\Magazine;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use UnitEnum;

class MagazineResource extends Resource
{
    protected static ?string $model = Magazine::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Magazine Issues';

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $modelLabel = 'Magazine Issue';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Issue Details')
                ->schema([
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
                        ->helperText('Used in the public download URL: /magazines/{slug}/download. A final unique slug is also enforced server-side by MagazineService.'),

                    TextInput::make('issue_label')
                        ->maxLength(255)
                        ->helperText('e.g. "September 2026" or "Issue No. 12" — shown on the homepage card.'),

                    Textarea::make('description')
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull(),
                ]),

            Section::make('Cover & File')
                ->description('The cover image is what readers see; the PDF is what they download when they click it.')
                ->schema([
                    Placeholder::make('current_cover_image')
                        ->label('Current Cover Image')
                        ->content(fn (?Magazine $record) => $record?->coverImage
                            ? new HtmlString('<img src="'.e($record->coverImage->url).'" style="max-height:160px;border-radius:6px" />')
                            : 'No cover image set yet.')
                        ->visible(fn (?Magazine $record) => $record !== null),

                    FileUpload::make('cover_image_temp')
                        ->label('Cover Image')
                        ->image()
                        ->disk('local')
                        ->directory('tmp-uploads/magazines/cover')
                        ->visibility('private')
                        ->maxSize(config('media.max_sizes.image'))
                        ->helperText('Uploading a new file replaces the current cover. Leave empty to keep it.')
                        ->required(fn (?Magazine $record) => $record === null)
                        ->dehydrated(),

                    Placeholder::make('current_pdf')
                        ->label('Current PDF')
                        ->content(fn (?Magazine $record) => $record?->pdf
                            ? new HtmlString('<a href="'.e($record->pdf->url).'" target="_blank" rel="noopener">'.e($record->pdf->file_name).' ('.e($record->pdf->formattedSize()).')</a>')
                            : 'No PDF uploaded yet.')
                        ->visible(fn (?Magazine $record) => $record !== null),

                    FileUpload::make('pdf_temp')
                        ->label('Magazine PDF')
                        ->disk('local')
                        ->directory('tmp-uploads/magazines/pdf')
                        ->visibility('private')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(config('media.max_sizes.attachment'))
                        ->helperText('Uploading a new file replaces the current PDF. Leave empty to keep it.')
                        ->required(fn (?Magazine $record) => $record === null)
                        ->dehydrated(),
                ]),

            Section::make('Publishing')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Manual kill switch — turn off to pull this issue without deleting it.'),

                    Grid::make(2)->schema([
                        DateTimePicker::make('published_at')
                            ->label('Published At')
                            ->helperText('Leave blank to publish immediately.'),

                        TextInput::make('display_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Breaks ties when multiple issues share a publish date.'),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('coverImage.url')
                    ->label('')
                    ->square(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('issue_label')
                    ->label('Issue')
                    ->searchable(),

                TextColumn::make('downloads_count')
                    ->label('Downloads')
                    ->sortable(),

                IconColumn::make('is_active')->boolean()->label('Active'),

                TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Magazine $record) => $record->pdf ? route('magazines.download', $record->slug) : null)
                    ->visible(fn (Magazine $record) => $record->hasPdf())
                    ->openUrlInNewTab(),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMagazines::route('/'),
            'create' => CreateMagazine::route('/create'),
            'edit' => EditMagazine::route('/{record}/edit'),
        ];
    }
}

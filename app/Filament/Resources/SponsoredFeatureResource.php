<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SponsoredFeatureResource\Pages\CreateSponsoredFeature;
use App\Filament\Resources\SponsoredFeatureResource\Pages\EditSponsoredFeature;
use App\Filament\Resources\SponsoredFeatureResource\Pages\ListSponsoredFeatures;
use App\Models\SponsoredFeature;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
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

class SponsoredFeatureResource extends Resource
{
    protected static ?string $model = SponsoredFeature::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Sponsored Content';

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $modelLabel = 'Sponsored Feature';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Content')
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
                        ->helperText('Used in the public URL: /features/{slug}. A final unique slug is also enforced server-side by SponsoredFeatureService.'),

                    Textarea::make('excerpt')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Short summary shown on the homepage card.'),

                    RichEditor::make('body')
                        ->label('Full Body (read-more page)')
                        ->columnSpanFull(),
                ]),

            Section::make('Sponsor & Disclosure')
                ->description('Every field here is shown to readers — this is what keeps the placement clearly marked as an ad, not editorial content.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('sponsor_name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g. "Audemars Piguet"'),

                        TextInput::make('sponsor_label')
                            ->maxLength(255)
                            ->helperText('Overrides the auto "Sponsored by {sponsor_name}" text, e.g. "Presented by Audemars Piguet". Leave blank to use the default.'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('collaboration_label')
                            ->maxLength(255)
                            ->helperText('Overlay badge shown only when this is the featured (large) placement, e.g. "In Collaboration with Audemars Piguet".'),

                        TextInput::make('category_label')
                            ->maxLength(255)
                            ->helperText('The vertical this is filed under, e.g. "Horology & Heritage". Free text — not tied to article categories.'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('cta_label')
                            ->required()
                            ->default('Read Feature')
                            ->maxLength(100),

                        TextInput::make('disclosure_label')
                            ->required()
                            ->default('Sponsored')
                            ->maxLength(100)
                            ->helperText('The small disclosure tag shown on the card and the read-more page, e.g. "Sponsored", "Paid Partnership", "Advertisement".'),
                    ]),
                ]),

            Section::make('Media')
                ->description('The featured image (or video) is what makes a placement eligible for the large hero tile — see the Placement section below.')
                ->schema([
                    Placeholder::make('current_featured_image')
                        ->label('Current Featured Image')
                        ->content(fn (?SponsoredFeature $record) => $record?->featuredImage
                            ? new HtmlString('<img src="'.e($record->featuredImage->url).'" style="max-height:160px;border-radius:6px" />')
                            : 'No featured image set yet.')
                        ->visible(fn (?SponsoredFeature $record) => $record !== null),

                    FileUpload::make('featured_image_temp')
                        ->label('Featured Image')
                        ->image()
                        ->disk('local')
                        ->directory('tmp-uploads/sponsored/featured')
                        ->visibility('private')
                        ->maxSize(config('media.max_sizes.image'))
                        ->helperText('Uploading a new file replaces the current featured image. Leave empty to keep it.')
                        ->dehydrated(),

                    Placeholder::make('current_video')
                        ->label('Current Video')
                        ->content(fn (?SponsoredFeature $record) => $record?->video?->url
                            ? new HtmlString('<video src="'.e($record->video->url).'" controls style="max-height:200px;border-radius:6px" />')
                            : 'No video uploaded yet.')
                        ->visible(fn (?SponsoredFeature $record) => $record !== null),

                    FileUpload::make('video_temp')
                        ->label('Video')
                        ->disk('local')
                        ->directory('tmp-uploads/sponsored/video')
                        ->visibility('private')
                        ->acceptedFileTypes(config('media.accepted_mime_types.video'))
                        ->maxSize(config('media.max_sizes.video'))
                        ->helperText('A sponsored placement can be a video instead of (or alongside) an image — the read-more page renders whichever is present, video first.')
                        ->dehydrated(),
                ]),

            Section::make('Placement & Flighting')
                ->schema([
                    Toggle::make('is_featured')
                        ->label('Featured (large hero tile)')
                        ->helperText('Only one placement is featured at a time — marking this one featured automatically un-features whichever placement currently holds the slot.'),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Manual kill switch, independent of the dates below.'),

                    Grid::make(2)->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Starts At')
                            ->helperText('Leave blank to start immediately.'),

                        DateTimePicker::make('ends_at')
                            ->label('Ends At')
                            ->after('starts_at')
                            ->helperText('Leave blank to run indefinitely.'),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featuredImage.url')
                    ->label('')
                    ->square(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('sponsor_name')
                    ->label('Sponsor')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_featured')->boolean()->label('Featured'),
                IconColumn::make('is_active')->boolean()->label('Active'),

                TextColumn::make('starts_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ends_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_featured'),
                TernaryFilter::make('is_active'),
            ])
            ->recordActions([
                Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (SponsoredFeature $record) => "/features/{$record->slug}")
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
            'index' => ListSponsoredFeatures::route('/'),
            'create' => CreateSponsoredFeature::route('/create'),
            'edit' => EditSponsoredFeature::route('/{record}/edit'),
        ];
    }
}

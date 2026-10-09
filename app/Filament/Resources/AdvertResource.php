<?php

namespace App\Filament\Resources;

use App\Enums\AdvertPlacement;
use App\Filament\Resources\AdvertResource\Pages\CreateAdvert;
use App\Filament\Resources\AdvertResource\Pages\EditAdvert;
use App\Filament\Resources\AdvertResource\Pages\ListAdverts;
use App\Models\Advert;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use UnitEnum;

class AdvertResource extends Resource
{
    protected static ?string $model = Advert::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Adverts';

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $modelLabel = 'Advert';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Advert')
                ->schema([
                    Select::make('placement')
                        ->label('Where it appears')
                        ->options(static::placementOptions())
                        ->default(AdvertPlacement::HeroRail->value)
                        ->required()
                        ->native(false)
                        ->helperText('Each place on the site has its own slider. Adverts are only shown in the one you pick here.'),

                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Internal name — also used as the image\'s accessible description.'),

                    TextInput::make('target_url')
                        ->label('Website Link (optional)')
                        ->url()
                        ->regex('/^https?:\/\//i')
                        ->maxLength(2048)
                        ->placeholder('https://advertiser.com/landing-page')
                        ->helperText('Where readers go when they click the advert. Leave empty for a banner that isn\'t clickable. Must start with http:// or https://.'),
                ]),

            Section::make('Image or video')
                ->description('Upload a JPG, PNG, WebP or GIF — or a short MP4/WebM video (it plays muted and loops). Recommended: '.collect(AdvertPlacement::cases())->map(fn (AdvertPlacement $p) => $p->label().': '.$p->recommendedSize())->implode('; ').'. Keep videos under about 10 MB so they start quickly.')
                ->schema([
                    Placeholder::make('current_image')
                        ->label('Current File')
                        ->content(function (?Advert $record) {
                            $media = $record?->coverImage;

                            if (! $media) {
                                return 'No file set yet.';
                            }

                            $url = e($media->url);

                            return new HtmlString($media->isVideo()
                                ? '<video src="'.$url.'" controls muted style="max-height:160px;border-radius:6px"></video>'
                                : '<img src="'.$url.'" style="max-height:160px;border-radius:6px" />');
                        })
                        ->visible(fn (?Advert $record) => $record !== null),

                    FileUpload::make('image_temp')
                        ->label('Advert Image or Video')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/webm'])
                        ->disk('local')
                        ->directory('tmp-uploads/adverts')
                        ->visibility('private')
                        // KB. Adverts are short clips, so far below the 200 MB general video limit.
                        ->maxSize(30 * 1024)
                        ->helperText('Uploading a new file replaces the current one. Leave empty to keep it.')
                        ->required(fn (?Advert $record) => $record === null)
                        ->dehydrated(),
                ]),

            Section::make('Publishing')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Manual kill switch — turn off to pull this advert without deleting it.'),

                    Grid::make(2)->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Starts At')
                            ->helperText('Leave blank to start immediately.'),

                        DateTimePicker::make('ends_at')
                            ->label('Ends At')
                            ->after('starts_at')
                            ->helperText('Leave blank to run until you switch it off.'),
                    ]),

                    TextInput::make('display_order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers appear first. You can also drag rows in the list to reorder.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Videos have no still to show, so only images get a thumbnail;
                // the Type column says which is which.
                ImageColumn::make('thumbnail')
                    ->label('')
                    ->state(fn (Advert $record) => $record->isVideo() ? null : $record->coverImage?->url)
                    ->width(96)
                    ->height(54),

                TextColumn::make('placement')
                    ->label('Slot')
                    ->state(fn (Advert $record) => $record->placement->label())
                    ->badge()
                    ->color('gray'),

                TextColumn::make('type')
                    ->state(fn (Advert $record) => $record->isVideo() ? 'Video' : 'Image')
                    ->badge()
                    ->color(fn (string $state) => $state === 'Video' ? 'info' : 'gray'),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('target_url')
                    ->label('Link')
                    ->placeholder('No link')
                    ->limit(40)
                    ->url(fn (Advert $record) => $record->target_url)
                    ->openUrlInNewTab(),

                TextColumn::make('status')
                    ->state(fn (Advert $record) => $record->status())
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Live' => 'success',
                        'Scheduled' => 'info',
                        'Expired' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('clicks_count')
                    ->label('Clicks')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_active')->boolean()->label('Active'),

                TextColumn::make('starts_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ends_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('placement')->options(static::placementOptions()),
                TernaryFilter::make('is_active'),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    protected static function placementOptions(): array
    {
        return collect(AdvertPlacement::cases())
            ->mapWithKeys(fn (AdvertPlacement $placement) => [$placement->value => $placement->label()])
            ->all();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdverts::route('/'),
            'create' => CreateAdvert::route('/create'),
            'edit' => EditAdvert::route('/{record}/edit'),
        ];
    }
}

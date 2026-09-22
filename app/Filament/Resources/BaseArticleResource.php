<?php

namespace App\Filament\Resources;

use App\Enums\ArticleStatus;
use App\Enums\ContentType;
use App\Enums\PermissionName;
use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

abstract class BaseArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    /**
     * The one thing that actually distinguishes a "News Article" resource
     * from a "Blog Post" resource — everything else below is identical.
     */
    abstract protected static function contentType(): ContentType;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['category', 'author', 'tags', 'featuredImage'])
            ->where('type', static::contentType()->value);
    }

    public static function form(Schema $schema): Schema
    {
        $canUpdateAny = auth()->user()?->can(PermissionName::UpdateAnyArticle->value) ?? false;
        $canPublish = auth()->user()?->can(PermissionName::PublishArticles->value) ?? false;

        return $schema->components([
            Hidden::make('type')->default(static::contentType()->value),

            Tabs::make('Article')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Content')
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                    if ($operation === 'create') {
                                        $set('slug', Str::slug($state));
                                    }
                                }),

                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->helperText('Auto-generated from the title on create. A final unique slug is also enforced server-side by ArticleService.'),

                            Textarea::make('excerpt')
                                ->rows(3)
                                ->maxLength(500)
                                ->helperText('Short summary shown in article cards and previews.'),

                            RichEditor::make('body')
                                ->required()
                                ->columnSpanFull(),
                        ]),

                    Tab::make('Media')
                        ->schema([
                            Placeholder::make('current_featured_image')
                                ->label('Current Featured Image')
                                ->content(fn (?Article $record) => $record?->featuredImage
                                    ? new HtmlString('<img src="'.e($record->featuredImage->url).'" style="max-height:160px;border-radius:6px" />')
                                    : 'No featured image set yet.')
                                ->visible(fn (?Article $record) => $record !== null),

                            FileUpload::make('featured_image_temp')
                                ->label('Featured Image')
                                ->image()
                                ->disk('local')
                                ->directory('tmp-uploads/featured')
                                ->visibility('private')
                                ->maxSize(config('media.max_sizes.image'))
                                ->helperText('Uploading a new file replaces the current featured image. Leave empty to keep it.')
                                ->dehydrated(),

                            FileUpload::make('gallery_temp')
                                ->label('Gallery Images')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->disk('local')
                                ->directory('tmp-uploads/gallery')
                                ->visibility('private')
                                ->maxSize(config('media.max_sizes.image'))
                                ->dehydrated(),

                            FileUpload::make('video_temp')
                                ->label('Video')
                                ->disk('local')
                                ->directory('tmp-uploads/video')
                                ->visibility('private')
                                ->acceptedFileTypes(config('media.accepted_mime_types.video'))
                                ->maxSize(config('media.max_sizes.video'))
                                ->dehydrated(),
                        ]),

                    Tab::make('Categorization')
                        ->schema([
                            Select::make('category_id')
                                ->label('Category')
                                ->options(fn () => Category::query()->pluck('name', 'id'))
                                ->searchable()
                                ->preload(),

                            TagsInput::make('tags')
                                ->helperText('Press enter after each tag. New tags are created automatically.'),

                            Grid::make(2)->schema([
                                Toggle::make('is_featured')->label('Featured'),
                                Toggle::make('is_trending')->label('Trending'),
                            ]),
                        ]),

                    Tab::make('Publishing')
                        ->schema([
                            Select::make('status')
                                ->options(ArticleStatus::options())
                                ->default(ArticleStatus::Draft->value)
                                ->required()
                                ->live()
                                ->disableOptionWhen(fn (string $value) => $value === ArticleStatus::Published->value && ! $canPublish)
                                ->helperText($canPublish ? null : 'Publishing requires the publish_articles permission — save as Draft or submit for review instead.'),

                            DateTimePicker::make('published_at')
                                ->label('Published At')
                                ->visible(fn (callable $get) => $get('status') === ArticleStatus::Published->value),

                            DateTimePicker::make('scheduled_for')
                                ->label('Scheduled For')
                                ->minDate(now())
                                ->required(fn (callable $get) => $get('status') === ArticleStatus::Scheduled->value)
                                ->visible(fn (callable $get) => $get('status') === ArticleStatus::Scheduled->value)
                                ->helperText('A scheduled job checks every minute and publishes this automatically once the time arrives.'),

                            Select::make('author_id')
                                ->label('Author')
                                ->relationship('author', 'name')
                                ->default(auth()->id())
                                ->required()
                                ->visible($canUpdateAny)
                                ->searchable(),

                            Hidden::make('author_id')
                                ->default(auth()->id())
                                ->visible(! $canUpdateAny),
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
                    ->limit(50),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                IconColumn::make('is_featured')->boolean()->label('Featured'),
                IconColumn::make('is_trending')->boolean()->label('Trending'),

                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options(ArticleStatus::options()),
                SelectFilter::make('category_id')->label('Category')->relationship('category', 'name'),
                TernaryFilter::make('is_featured')->label('Featured'),
                TernaryFilter::make('is_trending')->label('Trending'),
            ])
            ->recordActions([
                Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Article $record) => $record->type === ContentType::BlogPost
                        ? route('home') // swap for a real blog-post show route once the public site has one
                        : route('articles.show', $record->slug))
                    ->openUrlInNewTab(),

                Action::make('publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Article $record) => auth()->user()?->can('publish', $record)
                        && $record->status !== ArticleStatus::Published)
                    ->action(fn (Article $record) => app(ArticleService::class)->publish($record)),

                Action::make('unpublish')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Article $record) => auth()->user()?->can('publish', $record)
                        && $record->status === ArticleStatus::Published)
                    ->action(fn (Article $record) => app(ArticleService::class)->unpublish($record)),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

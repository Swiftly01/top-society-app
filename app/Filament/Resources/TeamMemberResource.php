<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages\CreateTeamMember;
use App\Filament\Resources\TeamMemberResource\Pages\EditTeamMember;
use App\Filament\Resources\TeamMemberResource\Pages\ListTeamMembers;
use App\Models\TeamMember;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use UnitEnum;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static   BackedEnum|string|null  $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Team Members';

    protected static UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?string $modelLabel = 'Team Member';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('role')
                ->required()
                ->maxLength(255)
                ->helperText('e.g. "Editor-in-Chief" — shown as the badge on the photo and under the name.'),

            Textarea::make('bio')
                ->rows(3)
                ->maxLength(500),

            TextInput::make('href')
                ->label('Profile Link (optional)')
                ->url()
                ->maxLength(255)
                ->helperText('Leave blank to render the card as non-clickable.'),

            Placeholder::make('current_photo')
                ->label('Current Photo')
                ->content(fn (?TeamMember $record) => $record?->photo
                    ? new HtmlString('<img src="'.e($record->photo->url).'" style="max-height:160px;border-radius:6px" />')
                    : 'No photo uploaded yet.')
                ->visible(fn (?TeamMember $record) => $record !== null),

            FileUpload::make('photo_temp')
                ->label('Photo')
                ->image()
                ->disk('local')
                ->directory('tmp-uploads/team')
                ->visibility('private')
                ->maxSize(config('media.max_sizes.image'))
                ->helperText('Uploading a new photo replaces the current one. Leave empty to keep it.')
                ->dehydrated(),

            Toggle::make('is_active')
                ->default(true)
                ->helperText('Inactive members are hidden from the About page slider without deleting their record.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Drag-to-reorder writes display_order directly — the slider's
            // member order is set here, not by typing numbers into a
            // field. This is the one write path in this resource that
            // intentionally bypasses TeamMemberService: it's a pure
            // ordinal update with no business logic attached to it.
            ->reorderable('display_order')
            ->defaultSort('display_order')
            ->columns([
                ImageColumn::make('photo.url')
                    ->label('')
                    ->circular(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->searchable(),

                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => ListTeamMembers::route('/'),
            'create' => CreateTeamMember::route('/create'),
            'edit' => EditTeamMember::route('/{record}/edit'),
        ];
    }
}
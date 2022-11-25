<?php

namespace App\Filament\Resources\CMS;

use App\Filament\Resources\CMS\PostResource\Pages;
use App\Models\Post;
use App\Overrides\Filament\Forms\Components\CustomTinyEditor as TinyEditor;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $slug = 'cms/posts';

    protected static ?int $navigationSort = -1;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $modelLabel = 'Post';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Card::make([
                            \Camya\Filament\Forms\Components\TitleWithSlugInput::make(
                                fieldTitle: 'title',
                                fieldSlug: 'slug',
                                urlPath: '/blog/',
                                titleRules:[
                                    'required',
                                    'string',
                                    'max:255',
                                ],
                            ),

                            TinyEditor::make('body')
                                ->label('Body')
                                ->required(),
                        ])->columnSpan(2),

                        Forms\Components\Card::make([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('featured')
                                ->collection('featured')
                                ->preserveFilenames()
                                ->label('Featured Image')
                                ->image()
                                ->rules(['required', 'image']),

                            Forms\Components\Select::make('post_category_id')
                                ->label('Category')
                                ->relationship('category', 'name')
                                ->nullable()
                                ->createOptionForm(PostCategoryResource::form($form)->getSchema()),

                            Forms\Components\TextInput::make('order_column')
                                ->label('Order')
                                ->rules(['nullable', 'numeric'])
                                ->numeric(),

                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('Publish Date')
                                ->rules(['nullable', 'date']),

                            Forms\Components\TextInput::make('seo.title')
                                ->usesMeta()
                                ->label('SEO - Title'),

                            Forms\Components\Textarea::make('seo.description')
                                ->usesMeta()
                                ->label('SEO - Description'),
                        ])->columnSpan(1),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured')
                    ->collection('featured')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->url(fn ($record) => $record->url)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing(fn (Post $record): string => is_null($record->published_at) ? 'Draft' : ($record->published_at->isFuture() ? 'Scheduled' : 'Published'))
                    ->colors([
                        'success' => 'Published',
                        'warning' => 'Scheduled',
                        'danger' => 'Draft',
                    ]),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published Date')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth('7xl'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('published_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

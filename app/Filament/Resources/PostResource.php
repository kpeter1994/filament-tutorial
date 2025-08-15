<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

//    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Create New Post')->tabs([
                    Tab::make('Content')
                        ->icon('heroicon-o-folder-open')
                        ->schema([
                        TextInput::make('title')->rules('min:3|max:10')->required(),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Post::class, 'slug', ignoreRecord: true)
                            ->label('Slug (URL)'),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->label('Category'),
                        ColorPicker::make('color'),
                        MarkdownEditor::make('content')->required()->columnSpan(1),
                    ]),
                    Tab::make('Meta')->schema([
                        Section::make('Meta')->schema([
                            FileUpload::make('thumbnail')->disk('public')->directory('thumbnail'),
                            TagsInput::make('tags')->required(),
                            Checkbox::make('is_published')->required()
                        ]),
                    ]),
                ]),

//                Section::make('Create a Post')
//                    ->description('Create a Post')
//                    ->collapsible()->schema([Group::make()->schema([])->columnSpanFull()->columns()])->columns(),

                Section::make('Authors')->schema([
                    Forms\Components\CheckboxList::make('authors')->relationship('authors', 'name')
                ]),
            ])->columns([
                'default' => 1,
                'lg' => 1,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Cím')->searchable()->sortable(),
                TextColumn::make('slug')->label('Url')->searchable()->sortable(),
                ColorColumn::make('color'),
                TextColumn::make('category.name')->label('Kategória')->searchable()->sortable(),
                ImageColumn::make('thumbnail')->toggleable(),
            ])
            ->filters([
                Filter::make('Published Posts')->query(
                    function (Builder $query) : Builder {
                        return $query->where('is_published', true);
                    }
                ),
                Filter::make('Unpublished Posts')->query(
                    function (Builder $query) : Builder {
                        return $query->where('is_published', false);
                    }
                ),
                SelectFilter::make('category_id')
                    ->label('Kategória')
                    ->relationship('category', 'name')
                    ->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AuthorsRelationManager::class,
            RelationManagers\CommentsRelationManager::class
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

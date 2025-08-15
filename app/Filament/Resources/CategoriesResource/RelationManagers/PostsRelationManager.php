<?php

namespace App\Filament\Resources\CategoriesResource\RelationManagers;

use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create a Post')
                    ->description('Create a Post')
                    ->collapsible()
                    ->schema([
                        Group::make()->schema([
                            TextInput::make('title')->rules('min:3|max:10')->required(),
                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(Post::class, 'slug', ignoreRecord: true)
                                ->label('Slug (URL)'),
                            ColorPicker::make('color'),
                        ])->columnSpanFull()->columns(
                            [
                                'default' => 1,
                                'md' => 2,
                            ]
                        ),
                    ])->columns([
                        'default' => 1,
                        'md' => 1,
                        'lg' => 2,
                    ]),

                MarkdownEditor::make('content')->required()->columnSpan(1),
                FileUpload::make('thumbnail')->disk('public')->directory('thumbnail'),
                TagsInput::make('tags')->required(),
                Checkbox::make('is_published')->required()
            ])->columns([
                'default' => 1,
                'lg' => 2,
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

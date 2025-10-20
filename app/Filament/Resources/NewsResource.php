<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use App\Services\ImageOptimizationService;
use Hexters\HexaLite\HasHexaLite;

class NewsResource extends Resource
{
    use HasHexaLite;
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Berita';
    protected static ?string $navigationGroup = 'Pengelolaan Website';

    protected static ?string $modelLabel = 'Berita';

    protected static ?string $pluralModelLabel = 'Berita';

    protected static ?int $navigationSort = 2;

    public $hexaSort = 10;

    public function defineGates()
    {
        return [
            'berita.index' => __('Lihat Berita'),
            'berita.create' => __('Buat Berita Baru'),
            'berita.update' => __('Ubah Berita'),
            'berita.delete' => __('Hapus Berita'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Utama')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required(),

                        RichEditor::make('content')
                            ->label('Konten')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                    ])
                    ->columns(2),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->disk('public')
                            ->directory('news')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull()
                            ->helperText('Upload gambar dengan ukuran maksimal 2MB (JPG, PNG, WebP)'),
                    ]),

                Section::make('Publikasi')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->default('draft')
                            ->options(News::getStatusOptions())
                            ->live(),

                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->default(now())
                            ->required(fn($get) => $get('status') === 'published')
                            ->hidden(fn($get) => $get('status') === 'draft'),

                        Hidden::make('author_id')
                            ->default(auth()->id()),

                        Select::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options(News::getCategoryOptions())
                            ->default('general'),

                        TagsInput::make('tags')
                            ->label('Tag')
                            ->placeholder('Tekan Enter untuk menambah tag')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),


                Hidden::make('meta_title'),

                Hidden::make('meta_description'),

                Hidden::make('slug')
                    ->rules(['alpha_dash']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->square()
                    ->width(80)
                    ->height(80)
                    ->disk('public')
                    ->visibility('public')
                    ->action(function (News $record) {
                        if ($record->featured_image) {
                            $imageUrl = url('storage/' . $record->featured_image);
                            return redirect($imageUrl);
                        }
                    })
                    ->tooltip('Klik untuk melihat gambar ukuran penuh'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                        'warning' => 'scheduled',
                    ]),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => News::getCategoryOptions()[$state] ?? $state),

                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->alignment('center'),

                TextColumn::make('published_at')
                    ->label('Dipublikasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(News::getStatusOptions()),

                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(News::getCategoryOptions()),

                SelectFilter::make('author_id')
                    ->label('Penulis')
                    ->relationship('author', 'name'),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                ->visible(fn()=>hexa()->can('berita.update')),
                Tables\Actions\Action::make('view_image')
                    ->label('Lihat Gambar')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn(News $record): string => "Gambar: {$record->title}")
                    ->modalContent(function (News $record) {
                        if (!$record->featured_image) {
                            return view('filament.components.no-image-placeholder');
                        }

                        $imageUrl = url('storage/' . $record->featured_image);
                        $stats = ImageOptimizationService::getOptimizationStats($record->featured_image, 'public');

                        return view('filament.components.image-viewer', [
                            'imageUrl' => $imageUrl,
                            'title' => $record->title,
                            'stats' => $stats,
                            'featured_image' => $record->featured_image
                        ]);
                    })
                    ->modalWidth('6xl')
                    ->modalFooterActions([
                        Tables\Actions\Action::make('download')
                            ->label('Download')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->color('success')
                            ->url(fn(News $record): string => url('storage/' . $record->featured_image))
                            ->openUrlInNewTab(),
                    ])
                    ->modalCancelAction(
                        fn() => \Filament\Actions\Action::make('cancel')
                            ->label('Close')
                            ->color('secondary')
                    )
                    ->visible(fn(News $record) => $record->featured_image),
                Tables\Actions\DeleteAction::make()
                ->visible(fn()=>hexa()->can('berita.delete')),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn()=>hexa()->can('berita.delete')),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('optimize_images')
                        ->label('Optimize Images')
                        ->icon('heroicon-o-photo')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $imagePaths = $records->whereNotNull('featured_image')
                                ->pluck('featured_image')
                                ->toArray();

                            if (empty($imagePaths)) {
                                \Filament\Notifications\Notification::make()
                                    ->title('No Images to Optimize')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            $results = ImageOptimizationService::optimizeBatch($imagePaths, 'public');

                            $message = "Optimized: {$results['success']}, Failed: {$results['failed']}";

                            \Filament\Notifications\Notification::make()
                                ->title('Batch Optimization Complete')
                                ->body($message)
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'view' => Pages\ViewNews::route('/{record}'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'content', 'author.name'];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'محصولات';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static  ?string $modelLabel = 'محصول';

    protected static ?string $pluralModelLabel = 'محصولات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->label('مدرس')
                    ->relationship('teacher', 'name')
                    ->required(),
                Forms\Components\TextInput::make('spot_player_id')
                    ->label('شناسه دوره در اسپات پلیر')
                    ->maxLength(2048)
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('موضوع')
                    ->required()
                    ->maxLength(2048),
                Forms\Components\TextInput::make('slug')
                    ->label('نامک')
                    ->required()
                    ->maxLength(2048),
                Forms\Components\Select::make('type')
                    ->label('نوع دوره')
                    ->options([
                        'حضوری' => 'حضوری',
                        'آنلاین' => 'آنلاین',
                        'حضوری/آنلاین' => 'حضوری/آنلاین',
                    ]),
                Forms\Components\TextInput::make('price')
                    ->label('قیمت دوره')
                    ->numeric()
                    ->prefix('تومان'),
                Forms\Components\Select::make('discount_type')
                    ->label('نوع تخفیف')
                    ->options([
                        'percent' => 'درصدی',
                        'static' => 'ثابت',
                    ]),
                Forms\Components\TextInput::make('discount_value')
                    ->label('مقدار تخفیف')
                    ->numeric(),
                Forms\Components\TextInput::make('number_of_session')
                    ->label('تعداد جلسات')
                    ->maxLength(2048),
                Forms\Components\FileUpload::make('thumbnail')
                    ->label('پوستر')
                    ->columnSpanFull()
                    ->preserveFilenames(),
                Forms\Components\RichEditor::make('body')
                    ->label('متن')
                    ->required()
                    ->hintColor('primary')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('headline')
                    ->label('سر تیتر')
                    ->required()
                    ->hintColor('primary')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('start_time')
                    ->label('تاریخ شروع')
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('تاریخ انتشار')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('کاربر منتشر کننده محصول')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Toggle::make('active')
                    ->label('وضعیت')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('مدرس')
                    ->numeric(),
                Tables\Columns\TextColumn::make('title')
                    ->label('موضوع')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('نامک')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('پوستر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('قیمت دوره')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_of_session')
                    ->label('تعداد جلسات')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color('primary')
                    ->label('نوع دوره')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label('وضعیت')
                    ->boolean(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('تاریخ شروع')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('نام منتشر کننده')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاریخ به روز رسانی')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}

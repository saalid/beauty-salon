<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserBoughtLicenseResource\Pages;
use App\Filament\Resources\UserBoughtLicenseResource\RelationManagers;
use App\Models\UserBoughtLicense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserBoughtLicenseResource extends Resource
{
    protected static ?string $model = UserBoughtLicense::class;

    protected static ?string $navigationLabel = 'دوره های خریداری شده توسط کاربران';

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static  ?string $modelLabel = 'دوره های خریداری شده';

    protected static ?string $pluralModelLabel = 'دوره های خریداری شده';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('نام خریدار')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->label('نام دوره')
                    ->relationship('product', 'title')
                    ->required(),
                Forms\Components\TextInput::make('license_id')
                    ->label('آیدی لایسنس')
                    ->maxLength(2048)
                    ->required(),
                Forms\Components\TextInput::make('license_key')
                    ->label('کلید لایسنس')
                    ->maxLength(2048)
                    ->required(),
                Forms\Components\TextInput::make('url_download')
                    ->label('لینک دانلود اسپات پلیر')
                    ->maxLength(2048)
                    ->required(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('کاربر'),
                Tables\Columns\TextColumn::make('product.title')
                ->label('نام محصول'),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUserBoughtLicenses::route('/'),
        ];
    }
}

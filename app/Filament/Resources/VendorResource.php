<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vendor;
use App\Models\Service;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VendorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VendorResource\RelationManagers;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Vendor Management';

    // protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('contact_person')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(50),
                        Forms\Components\DatePicker::make('appointed_at')
                        ->required(),
                        Forms\Components\DatePicker::make('terminated_at'),
                    ])->columns(3),
   
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('contact_person'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('services_count')->counts('services'),
                Tables\Columns\TextColumn::make('email')
                ->toggleable()
                ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('appointed_at')
                    ->date(),
                Tables\Columns\TextColumn::make('terminated_at')
                    ->date()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
             RelationManagers\ServicesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }    
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Models\Location;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    protected static ?string $navigationGroup = 'Location Management';
    // protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(50),
                    Forms\Components\TextInput::make('code')
                        ->maxLength(20),
                    Forms\Components\TextInput::make('country')
                        ->maxLength(60),
                    Forms\Components\TextInput::make('city')
                        ->maxLength(85),           
                    Forms\Components\Radio::make('type')
                        ->label('Location is ')
                        ->inline()
                        ->options([
                            'port' => 'Port',
                            'warehouse' => 'Warehouse',
                            'showroom' => 'Showroom'
                        ])
                ])
                ->columnSpan(['lg' => fn (?Location $record) => $record === null ? 3 : 2]),
 
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Location $record): ?string => $record->created_at?->diffForHumans()),
    
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Location $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Location $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()
                ,
                Tables\Columns\TextColumn::make('code')
                ->searchable(),
                Tables\Columns\TextColumn::make('country')
                ->searchable(),
                Tables\Columns\TextColumn::make('city')
                ->searchable(),
                Tables\Columns\TextColumn::make('type')
                ->searchable()
                ,
                Tables\Columns\TextColumn::make('created_at')                    
                    ->dateTime()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('updated_at')
            ->toggleable()
            ->toggledHiddenByDefault(),
            ])
            ->filters([
               
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }    
}

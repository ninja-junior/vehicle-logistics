<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Brand;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Widgets\ListModels;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BrandResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BrandResource\RelationManagers;

class BrandResource extends Resource
{
    
    protected static ?string $model = Brand::class;
    
   
    protected static ?string $navigationIcon = 'heroicon-o-color-swatch';

    protected static ?string $navigationGroup = 'Inventory Management';

    // protected static ?int $navigationSort = 8;

    public static function getWidgets(): array
    {
        return [
            ListModels::class,
        ];
    }
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
                            ->maxLength(20)
                           ,
                        Forms\Components\TextInput::make('code')
                            ->unique(ignoreRecord: true)
                            ->maxLength(10),
                        Forms\Components\TextInput::make('manufacturer')
                            ->maxLength(50),
                    ])->columns(3),
                    Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Modells'),
                        Forms\Components\Repeater::make('modells')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(20),                            
                            Forms\Components\TextInput::make('code')
                            ->maxLength(10),
                            Forms\Components\TextInput::make('default_engine_power')
                            ->label('Default Engine Power (cc)')
                            ->numeric(),
                            Forms\Components\TextInput::make('default_group')
                            ->maxLength(30),
                        ])->columns(2)                        
                    ]),
                ])
                ->columnSpan(['lg' => fn (?Brand $record) => $record === null ? 3 : 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Brand $record): ?string => $record->created_at?->diffForHumans()),
    
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Brand $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Brand $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('code')
                ->searchable(),
                Tables\Columns\TextColumn::make('manufacturer')
                ->toggleable(),
                Tables\Columns\TextColumn::make('modells_count')->counts('modells'),
                
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }    
}

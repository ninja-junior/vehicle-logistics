<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\Modell;
use App\Models\Location;
use App\Enums\VehicleType;
use Squire\Models\Country;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StockResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StockResource\RelationManagers;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Inventory Management';

    // protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Vehicle')
                        ->schema(static::getFormSchema()),

                    Forms\Components\Section::make('Stock')
                        ->schema(static::getFormSchema('stock')),
                ])
                ->columnSpan(['lg' => fn (?Stock $record) => $record === null ? 3 : 2]),

            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Placeholder::make('created_at')
                        ->label('Created at')
                        ->content(fn (Stock $record): ?string => $record->created_at?->diffForHumans()),

                    Forms\Components\Placeholder::make('updated_at')
                        ->label('Last modified at')
                        ->content(fn (Stock $record): ?string => $record->updated_at?->diffForHumans()),
                ])
                ->columnSpan(['lg' => 1])
                ->hidden(fn (?Stock $record) => $record === null),
        ])
        ->columns(3);
    }

    public static function getFormSchema(?string $section = null): array
    {
        if ($section === 'stock') {
            return [
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('number')
                    ->label('Stock No')
                    ->default(Stock::max('id') +1001)
                    ->disabled()
                    ->unique(Stock::class, 'number', ignoreRecord: true)
                    ->required()
                    ,
                    Forms\Components\TextInput::make('currency')
                    ->default('USD')
                    ,
                    Forms\Components\TextInput::make('cif_price')
                    ->label('CIF Price')
                    ->numeric(3)
                    ,
                    Forms\Components\Select::make('location_id')
                    ->options(Location::all()->pluck('full_location','id'))
                    ->columnSpan(3),                   
                ])
                ->columns([
                    'md' => 6,
                ])
            ];
        }
        return [
            Forms\Components\Card::make()
            ->schema([
                Forms\Components\Select::make('brand_id')
                        ->relationship('brand', 'name')
                        ->required()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(fn(callable $set)=>$set('modell_id',null)),
    
                    Forms\Components\Select::make('modell_id')
                        ->relationship('modell', 'name')
                        ->required()
                        ->options(function (callable $get) {
    
                            $brand=Brand::find($get('brand_id'));
    
                            if(!$brand){
    
                                return Modell::all()->pluck('name','id');
                            }
    
                            return $brand->modells->pluck('name','id');
                        }) 
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set){
                            // fn ($state, callable $set) => $set('engine_power', Modell::find($state)?->default_engine_power ?? 0)
                            $set('engine_power', Modell::find($state)?->default_engine_power ?? 0);
                            $set('group', Modell::find($state)?->default_group ??'');
                        }),
                    Forms\Components\TextInput::make('vin')
                            ->required()
                            ->maxLength(17)
                            ->unique(Stock::class, 'vin', ignoreRecord: true),
                    Forms\Components\TextInput::make('engine_power')
                        ->numeric()
                        ->suffix('(cc)')
                        ->required(),
                    Forms\Components\TextInput::make('group')
                        ->required(),
                    Forms\Components\TextInput::make('model_year')
                        ->required()
                        ->numeric()
                        ->minValue(2000)
                        ->maxValue(2100)
                        ->default(2020),
                    Forms\Components\Select::make('type')
                        ->required()
                        ->options(
                            ['pc'=>'PC','cv'=>'CV']
                        )
                        ->default('pc'),    
                    Forms\Components\Select::make('country')
                        ->label('Country Of Origin')
                        ->required()
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $query) => Country::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                        ->getOptionLabelUsing(fn ($value): ?string => Country::find($value)?->getAttribute('name')),                    
                    
                ])->columns([
                    'md'=>3
                ])
               
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                ->label('Stock No')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('vin')
                ->label('Chassis No')
                ->searchable(),
                Tables\Columns\TextColumn::make('brand.name')
                ->label('Brand')
                ->searchable(),
                Tables\Columns\TextColumn::make('modell.name')
                ->label('Model Name')
                ->searchable(),
                Tables\Columns\TextColumn::make('location.full_location')
                ->label('Current Location')
                ->searchable()
                ->sortable()
                ->toggleable(),

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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }    
}

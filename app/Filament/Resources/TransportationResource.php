<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Stock;
use App\Models\Vendor;
use App\Models\Location;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Transportation;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransportationResource\Pages;
use App\Filament\Resources\TransportationResource\RelationManagers;

class TransportationResource extends Resource
{
    protected static ?string $model = Transportation::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Transportation Management';

    // protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Location')
                ->description('Choose movement location')
                ->schema([
                    Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('booking_number')
                            ->label('Booking Number')
                            ->default('TBook-' . Transportation::max('id')+1001)
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('stock_id')                        
                        ->label('Chassis Number')
                        ->relationship('stock','stock_vin')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search)
                            => Stock::getStocksWithRoDate($search)->pluck('stock_vin', 'id'))
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set){
                            $set('origin_id', Stock::find($state)?->location_id ?? 0);
                        }),
                        Forms\Components\Select::make('vendor_id')
                        ->label('Vendor Name')
                        ->relationship('vendor','name')
                        ->options(
                            Vendor::transportationVendor()->pluck('name', 'id')
                        ),
                        Forms\Components\Select::make('origin_id')
                        ->relationship('origin', 'name')
                        ->options(
                            Location::locationInMyanmar()->pluck('name', 'id')
                        )
                        ->createOptionForm([
                            Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('code')
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('country')
                                    ->default('Myanmar')
                                    ->disabled(),
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
                            ])->columns(2)
                        ])
                        ,

                        Forms\Components\Select::make('destination_id')
                        ->relationship('destination', 'name')
                        ->different('origin_id')
                        ->options(
                            Location::locationInMyanmar()->pluck('name', 'id')
                        )
                        ->createOptionForm([
                            Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('code')
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('country')
                                    ->default('Myanmar')
                                    ->disabled(),
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
                            ])->columns(2)
                        ]),
                        Forms\Components\Textarea::make('route_description')
                        ->maxLength(300)
                        ->columnSpanFull(),
                        ])->columns(2),
                    ]),
                Forms\Components\Wizard\Step::make('Carrier')
                ->description('Fill carrier information')
                ->schema([
                    Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('carrier_number')
                        ->maxLength(20),
                        Forms\Components\TextInput::make('driver_name')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('driver_name', ucwords($state));
                        })
                        ->maxLength(50),
                        Forms\Components\DateTimePicker::make('depature_time')
                        ->withoutSeconds(),

                        ]),
                    ]),
                Forms\Components\Wizard\Step::make('Receive')
                ->description('Note for receive')
                ->schema([
                    Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('arrival_time')
                        ->withoutSeconds()
                        ->afterOrEqual('depature_time'),
                        Forms\Components\TextInput::make('received_by')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('received_by', ucwords($state));
                        })
                        ->maxLength(50),
                        Forms\Components\FileUpload::make('photo'),
                        Forms\Components\Textarea::make('note')
                            ->maxLength(2000),
                        ])
                    ]),
                ])                              
                ->columnSpan(['lg' => fn (?Transportation $record) => $record === null ? 3 : 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Transportation $record): ?string => $record->created_at?->diffForHumans()),
    
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Transportation $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Transportation $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('vendor.name'),
                Tables\Columns\TextColumn::make('stock.stock_vin'),
                Tables\Columns\TextColumn::make('booking_number')
                ->label('Booking Number'),
                Tables\Columns\TextColumn::make('origin.name')
                ->label('Origin'),
                Tables\Columns\TextColumn::make('destination.name')
                ->label('Destination'),
                Tables\Columns\TextColumn::make('carrier_number')
                ->toggleable()
                ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('driver_name')
                ->toggleable()
                ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('depature_time')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->dateTime("M j, Y H:i"),
                Tables\Columns\TextColumn::make('arrival_time')                    
                    ->toggleable()
                    ->dateTime("M j, Y H:i"),
                Tables\Columns\TextColumn::make('received_by')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('photo')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('note')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->dateTime(),
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
            'index' => Pages\ListTransportations::route('/'),
            'create' => Pages\CreateTransportation::route('/create'),
            'edit' => Pages\EditTransportation::route('/{record}/edit'),
        ];
    }    
}

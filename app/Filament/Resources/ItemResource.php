<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Stock;
use App\Models\Import;
use App\Models\Vendor;
use App\Models\Customs;
use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use App\Models\ImportLicense;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\ItemResource\Pages;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-grid-add';

    protected static ?string $navigationGroup = 'Importation Management';

    protected static ?string $navigationLabel = 'Import Items';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Import')
                ->description('Importation Request')
                ->schema([
                    Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('import_id')
                        ->required()
                        ->label('Import Order Number')
                        ->relationship('import', 'number')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) 
                            => Import::where('number', 'like', "%{$search}%")->limit(20)->pluck('number', 'id'))
                        ->createOptionForm([
                            Forms\Components\TextInput::make('number')                                
                                ->label('Import Order Number')
                                ->default('OR-' . Import::max('id')+1001)
                                ->disabled()
                                ->required(),
                            Forms\Components\Textarea::make('note')
                                ->maxLength(500)                                
                        ]),
                        Forms\Components\Select::make('stock_id')
                            ->label('Stock No or Vin')
                            ->required()
                            ->relationship('stock','stock_vin')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search) => Stock::getVinsNotInItemsAndNotInMyanmar($search)->pluck('stock_vin','id'))
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, $state){
                                $set('model_name', Stock::getModellName($state)                                 
                                    ?->name ?? null);
                            })
                            ->afterStateHydrated(function (Closure $set, $state){
                                if($state)
                                {

                                        $set('model_name', Stock::getModellName($state)                                 
                                        ?->name ?? null);
                                }
                            })
                            ,
                        Forms\Components\TextInput::make('model_name')
                        ->label('Model Name')
                        ->disabled(),                                                                
                    ]) 
                    ->columns(3)                   
                ]),

                Forms\Components\Tabs::make('Heading')
                ->tabs([
                Forms\Components\Tabs\Tab::make('Import License')
                ->icon('heroicon-o-document-text')
                    ->schema([     
                        Forms\Components\Card::make()
                        ->schema([    
                            Forms\Components\Select::make('import_license_id')
                                ->relationship('import_license', 'number')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set){
                                    $set('received_at', ImportLicense::find($state)?->received_at ?? null);
                                    $set('expired_at', ImportLicense::find($state)?->expired_at ?? null);
                                })
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search) 
                                => ImportLicense::where('number','like',"%{$search}%")->pluck('number', 'id'))
                                ->afterStateHydrated(function ($state, callable $set){
                                    $set('received_at', ImportLicense::find($state)?->received_at ?? null);
                                    $set('expired_at', ImportLicense::find($state)?->expired_at ?? null);
                                })
                                ->createOptionForm([
                                    Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Select::make('vendor_id')
                                        ->label('Vendor Name')
                                        ->relationship('vendor','name')
                                        ->options(
                                            Vendor::importLicenseVendor()->pluck('name', 'id')
                                        ),
                                        Forms\Components\TextInput::make('number')
                                            ->label('Import License Number')
                                            ->required()
                                            ->maxLength(20),
                                        Forms\Components\DatePicker::make('received_at')
                                        ->before('expired_at'),
                                        Forms\Components\DatePicker::make('expired_at')
                                        ->after('received_at'),                               
                                    ])->columns(2)
                                 ]), 
                             Forms\Components\DatePicker::make('received_at')
                                ->label('Received date')
                                ->disabled(),

                            Forms\Components\DatePicker::make('expired_at')
                                ->label('Expired date')
                                ->disabled(),
                        ])->columns(3)
                        
                    ]),
                    Forms\Components\Tabs\Tab::make('Shipping / Forwarding')
                    ->icon('heroicon-o-calendar')
                    ->schema([                            
                        Forms\Components\Card::make()
                        ->schema([                                                            
                            Forms\Components\Select::make('schedule_id')
                                ->label('Vessel Name')
                                ->relationship('schedule', 'vessel')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set){
                                    $set('eta', Schedule::find($state)?->eta ?? null);
                                    $set('etd', Schedule::find($state)?->etd ?? null);
                                 })
                                 ->searchable()
                                 ->getSearchResultsUsing(fn (string $search) 
                                 => Schedule::where('vessel','like',"%{$search}%")->pluck('vessel', 'id'))
                                 ->afterStateHydrated(function ($state, callable $set){
                                    $set('eta', Schedule::find($state)?->eta ?? null);
                                    $set('etd', Schedule::find($state)?->etd ?? null);
                                 })
                                 ->createOptionForm([
                                    Forms\Components\Card::make()
                                        ->schema([
                                            Forms\Components\Select::make('vendor_id')
                                        ->label('Vendor Name')
                                        ->relationship('vendor','name')
                                        ->options(
                                            Vendor::forwardingVendor()->pluck('name', 'id')
                                        )
                                    // ->searchable()
                                    // ->getSearchResultsUsing(fn (string $search) => Vendor::forwardingVendor($search)->pluck('name', 'id'))
                                    ->columnSpan('full'),
                                    Forms\Components\TextInput::make('name')
                                        ->label('Vessel Name')
                                        ->required()
                                        ->maxLength(50),
                                    Forms\Components\TextInput::make('voy')
                                        ->label('Voyage No')
                                        ->required()
                                        ->maxLength(10),
                                    Forms\Components\DatePicker::make('etd')
                                        ->label('Estimated Departure')
                                        ->before('eta'),
                                        
                                    Forms\Components\DatePicker::make('eta')
                                        ->label('Estimated Arrival')
                                        ->after('etd'),
                                        Forms\Components\Select::make('pol_id')                            
                                        ->label('Port of Loading')                                        
                                        ->relationship('pol','full_location')
                                        ->different('pod_id')
                                        ->searchable()                                        
                                        ->getSearchResultsUsing(fn (string $search) 
                                        => Location::where(
                                            [
                                                ['city', 'like', "%{$search}%"],
                                                ['type','=','port']
                                            ]
            
                                            )->pluck('full_location', 'id'))
                                        ,
                                    Forms\Components\Select::make('pod_id')
                                        ->label('Port of Discharged')
                                        ->relationship('pod','full_location')
                                        ->different('pol_id')
                                        ->searchable()
                                        ->getSearchResultsUsing(fn (string $search) 
                                        => Location::where(
                                            [
                                                ['city', 'like', "%{$search}%"],
                                                ['type','=','port']
                                            ]
            
                                            )->pluck('full_location', 'id'))
                                        ,  
                                   
                                    ])->columns(2)
                                ])                                 
                            ,
                            Forms\Components\TextInput::make('tracking_number')
                                ->label('Bill of landing')
                                ->requiredWith('schedule_id')
                                ->maxLength(30),
                                Forms\Components\DatePicker::make('etd')
                                ->label('Estimated Departure')
                                ->disabled(),

                                Forms\Components\DatePicker::make('eta')
                                ->label('Estimated Arrival')
                                ->disabled(),
                                
                           
                            ])->columns(4)
                    ]),
                    Forms\Components\Tabs\Tab::make('Customs Clearance')
                    ->icon('heroicon-o-cash')
                    ->schema([
                        Forms\Components\Select::make('customs_id')
                            ->label('Release Order Number')
                            ->relationship('customs', 'ro_number')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set){
                                $set('ro_date', Customs::find($state)?->ro_date ?? null);
                                $set('total_taxes', Customs::find($state)?->total_taxes ?? null);
                                })
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search) 
                                => Customs::where('ro_number','like',"%{$search}%")->pluck('ro_number', 'id'))
                                ->afterStateHydrated(function ($state, callable $set){
                                $set('ro_number', Customs::find($state)?->ro_date ?? null);
                                $set('total_taxes', Customs::find($state)?->total_taxes ?? null);
                                })
                                ->createOptionForm([
                                    Forms\Components\Card::make()
                                        ->schema([

                                    Forms\Components\Select::make('vendor_id')
                                        ->label('Vendor Name')
                                        ->relationship('vendor','name')
                                        // ->searchable()
                                        ->options(
                                                Vendor::customsVendor()->pluck('name', 'id')
                                        )
                                        ->columnSpanFull()
                                        // ->getSearchResultsUsing(fn (string $search) 
                                        //     => Vendor::customsVendor($search)->pluck('name', 'id')), 
                                        ,
                                    Forms\Components\TextInput::make('ro_number')
                                        ->label('Release Order Number')
                                        ->required()
                                        ->maxLength(20),
                                    Forms\Components\DatePicker::make('started_at')
                                    ->before('ro_date'),
                                    Forms\Components\DatePicker::make('ro_date')
                                    ->after('started_at'),
                                    Forms\Components\TextInput::make('currency')
                                        ->default(Str::upper('usd'))
                                        ->maxLength(10),
                                    Forms\Components\TextInput::make('ex_rate'),
                                    Forms\Components\TextInput::make('total_taxes')
                                    ->numeric()
                                    ->prefix('MMK'),
                                    ])->columns(3)
                                ])
                                        ,
                                Forms\Components\DatePicker::make('ro_date')           
                                    ->disabled(),
                                Forms\Components\TextInput::make('total_taxes')
                                ->prefix('MMK')                                   
                                ->disabled(),
                    ])->columns(3),
                ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stock.number')
                ->label('Stock Number')
                ->sortable()
                ->searchable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('stock.vin')
                ->label('Chassis')
                ->searchable(),
                Tables\Columns\TextColumn::make('import.number')
                ->label('Import Order Number')
                ->sortable()
                ->searchable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('schedule.vessel')
                ->label('Vessel Name'),
                Tables\Columns\TextColumn::make('schedule.eta')
                ->label('Estimated Arrival')
                ->date(),
                Tables\Columns\TextColumn::make('bl_no')
                ->searchable()
                ->toggleable()
                ->toggledHiddenByDefault()
                ,
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
                Tables\Filters\SelectFilter::make('import')
                ->relationship('import', 'number')
                ->label('Import Order Number')
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }    
}

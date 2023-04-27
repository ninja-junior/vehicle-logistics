<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use App\Models\Stock;
use App\Models\Customer;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SaleResource\RelationManagers;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sales')
                ->schema([

                    Forms\Components\Card::make()
                    ->schema([                    
                    Forms\Components\Select::make('stock_id')
                        ->required()                    
                        ->relationship('stock', 'id')
                        ->label('Chassis Number')
                        ->relationship('stock','stock_vin')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search)
                            => Stock::getStocksWithoutSales($search)->pluck('stock_vin', 'id')),
                    Forms\Components\select::make('customer_id')
                        ->required()
                        ->label('Search customer using identification')
                        ->relationship('customer','name')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) 
                        => Customer::where('identification', 'like', "%{$search}%")->pluck('name', 'id'))
                        ->getOptionLabelUsing(fn ($value): ?string => Customer::find($value)?->name)
                        ->createOptionForm([
                            Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('identification')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('phone')
                                    ->tel(),
                                Forms\Components\TextInput::make('email')
                                    ->email(),
                                Forms\Components\Textarea::make('address')
                                    ->maxLength(500)
                                    ->columnSpanFull(),                                
                            ])->columns(2)
                        ]),
                                            
                    Forms\Components\DatePicker::make('sales_date'),
                    Forms\Components\TextInput::make('currency')
                        ->default('USD')
                        ->maxLength(30),
                    Forms\Components\TextInput::make('sales_amount')
                        ->numeric(),
                    Forms\Components\TextInput::make('sales_person')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('sales_person', ucwords($state));
                        })
                        ->maxLength(50),
                    ])->columns(2)
                ])

                ->columnSpan(['lg' => fn (?Sale $record) => $record === null ? 3 : 2]),
 
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Sale $record): ?string => $record->created_at?->diffForHumans()),
    
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Sale $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Sale $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stock.stock_vin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sales_date')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('sales_amount'),
                Tables\Columns\TextColumn::make('sales_person')
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }    
}

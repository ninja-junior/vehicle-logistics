<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use App\Models\Stock;
use App\Models\Vendor;
use App\Models\Registration;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RegistrationResource\Pages;
use App\Filament\Resources\RegistrationResource\RelationManagers;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'Vehicel Registeration Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Vehicle Registration')
                ->schema([
                    Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('vendor_id')
                        ->label('Vendor Name')
                        ->relationship('vendor','name')
                        ->options(
                            Vendor::rtaVendor()->pluck('name', 'id')
                        )->columnSpanFull(),
                        Forms\Components\Select::make('stock_id')
                            ->label('Chassis Number')
                            ->relationship('stock','stock_vin')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search) => Stock::getStocksWithoutRegisteration($search)->pluck('stock_vin', 'id'))
                            ,
                        Forms\Components\TextInput::make('register_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('number_plate')
                            ->minValue(1000)
                            ->maxValue(9999)
                            ->numeric()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('regional_code')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('currency')
                            ->default('MMK')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('rta_tax')
                        ->numeric(),
                        Forms\Components\DatePicker::make('received_at'),
                        Forms\Components\DatePicker::make('expired_at'),
                        
                    ])->columns(2)
                ])
                ->columnSpan(['lg' => fn (?Registration $record) => $record === null ? 3 : 2]),
 
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Registration $record): ?string => $record->created_at?->diffForHumans()),
    
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Registration $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Registration $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.name'),
                Tables\Columns\TextColumn::make('stock.vin')
                ->label('Chassis Number')
                ->searchable(),
                Tables\Columns\TextColumn::make('register_name')
                ->searchable(),
                Tables\Columns\TextColumn::make('regional_code')
                ->sortable(),
                Tables\Columns\TextColumn::make('number_plate')
                ->label('Plate No')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                ->toggleable()
                ->toggledHiddenByDefault(),                
                Tables\Columns\TextColumn::make('rta_tax')
                ->sortable(),
                
                Tables\Columns\TextColumn::make('received_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->date(),
                Tables\Columns\TextColumn::make('expired_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->date(),
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
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }    
}

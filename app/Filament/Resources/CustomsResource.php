<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vendor;
use App\Models\Customs;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\CustomsResource\Pages;

class CustomsResource extends Resource
{
    protected static ?string $model = Customs::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $navigationGroup = 'Importation Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form 
            ->schema([
                Forms\Components\Group::make()
                ->schema([   
                    Forms\Components\Section::make('Customs Clearance')
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
                     ])->columns(['md'=>3]),
                     ])
                     ->columnSpan(['lg' => fn (?Customs $record) => $record === null ? 3 : 2]),
 
                     Forms\Components\Card::make()
                         ->schema([
                             Forms\Components\Placeholder::make('created_at')
                                 ->label('Created at')
                                 ->content(fn (Customs $record): ?string => $record->created_at?->diffForHumans()),
         
                             Forms\Components\Placeholder::make('updated_at')
                                 ->label('Last modified at')
                                 ->content(fn (Customs $record): ?string => $record->updated_at?->diffForHumans()),
                         ])
                         ->columnSpan(['lg' => 1])
                         ->hidden(fn (?Customs $record) => $record === null),
                 ])
                 ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.name'),
                Tables\Columns\TextColumn::make('ro_number'),
                Tables\Columns\TextColumn::make('started_at')
                    ->date(),
                Tables\Columns\TextColumn::make('ro_date')
                    ->date(),
                Tables\Columns\TextColumn::make('currency')
                    ->toggleable()                    
                    ->toggledHiddenByDefault(),                
                Tables\Columns\TextColumn::make('ex_rate')
                    ->toggleable()                    
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('total_taxes'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable()                    
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustoms::route('/'),
            'create' => Pages\CreateCustoms::route('/create'),
            'edit' => Pages\EditCustoms::route('/{record}/edit'),
        ];
    }    
}

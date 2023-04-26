<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vendor;
use App\Models\Location;
use App\Models\Schedule;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\ScheduleResource\Pages;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Importation Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form        
            ->schema([
                Forms\Components\Group::make()
                ->schema([

                    Forms\Components\Section::make('Shipping Schdule')
                    ->schema([
                        Forms\Components\Select::make('vendor_id')
                        ->label('Vendor Name')
                        ->relationship('vendor','name')
                        ->options(
                            Vendor::forwardingVendor()->pluck('name', 'id')
                        )
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
                            ->searchable()
                            ->different('pod_id')
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
                    ])->columns([
                        'md'=>2
                    ]) ,
                ])
                ->columnSpan(['lg' => fn (?Schedule $record) => $record === null ? 3 : 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Schedule $record): ?string => $record->created_at?->diffForHumans()),
    
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Schedule $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Schedule $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.name')
                    ->label('Vendor Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('vessel')
                    ->sortable()
                    ->searchable(),                
                Tables\Columns\TextColumn::make('pol')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('pod')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('etd')
                    ->label('Estimated Departure')  
                    ->date()
                    ->sortable()
                    ->toggleable(),                    
                Tables\Columns\TextColumn::make('eta')
                    ->label('Estimated Arrival')
                    ->date()
                    ->sortable()
                    ->toggleable(),
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

        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }    
}

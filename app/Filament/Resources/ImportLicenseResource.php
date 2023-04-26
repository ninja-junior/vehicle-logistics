<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vendor;
use Filament\Resources\Form;
use App\Models\ImportLicense;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ImportLicenseResource\Pages;
use App\Filament\Resources\ImportLicenseResource\RelationManagers;

class ImportLicenseResource extends Resource
{
    protected static ?string $model = ImportLicense::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Importation Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form 
            ->schema([
                Forms\Components\Group::make()
                ->schema([   
                    Forms\Components\Section::make('Import License')
                    ->schema([                  
                            Forms\Components\Select::make('vendor_id')
                            ->required()
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
                                ->required()
                                ->before('expired_at'),
                            Forms\Components\DatePicker::make('expired_at')
                                ->required()
                                ->after('received_at'),                                            
                         ])->columns(['md'=>2]),
                    ])
                    ->columnSpan(['lg' => fn (?ImportLicense $record) => $record === null ? 3 : 2]),

                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Placeholder::make('created_at')
                                ->label('Created at')
                                ->content(fn (ImportLicense $record): ?string => $record->created_at?->diffForHumans()),
        
                            Forms\Components\Placeholder::make('updated_at')
                                ->label('Last modified at')
                                ->content(fn (ImportLicense $record): ?string => $record->updated_at?->diffForHumans()),
                        ])
                        ->columnSpan(['lg' => 1])
                        ->hidden(fn (?ImportLicense $record) => $record === null),
                ])
                ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('License Number')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('received_at')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('expired_at')
                    ->date()
                    ->sortable()
                    ->searchable(),
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImportLicenses::route('/'),
            'create' => Pages\CreateImportLicense::route('/create'),
            'edit' => Pages\EditImportLicense::route('/{record}/edit'),
        ];
    }    
}

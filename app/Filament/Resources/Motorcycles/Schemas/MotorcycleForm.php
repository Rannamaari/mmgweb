<?php

namespace App\Filament\Resources\Motorcycles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MotorcycleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                TextInput::make('plate_no'),
                TextInput::make('make'),
                TextInput::make('model'),
                TextInput::make('year')
                    ->numeric(),
                TextInput::make('color'),
                TextInput::make('vin'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}

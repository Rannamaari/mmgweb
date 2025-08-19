<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),

                Select::make('type')
                    ->options([
                        'part' => 'Part (with stock)',
                        'service' => 'Service (no stock)',
                    ])
                    ->required()
                    ->live(),

                TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('price')
                    ->label('Price (ރ)')
                    ->required()
                    ->numeric()
                    ->rules(['min:0'])
                    ->prefix('ރ'),

                TextInput::make('cost')
                    ->label('Cost (ރ)')
                    ->numeric()
                    ->rules(['min:0'])
                    ->prefix('ރ')
                    ->helperText('Optional: Your purchase cost'),

                TextInput::make('stock_qty')
                    ->label('Stock Quantity')
                    ->numeric()
                    ->rules(['min:0'])
                    ->default(0)
                    ->visible(fn(Get $get): bool => $get('type') === 'part')
                    ->helperText('Only applies to parts'),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ])
            ->columns(3);
    }
}

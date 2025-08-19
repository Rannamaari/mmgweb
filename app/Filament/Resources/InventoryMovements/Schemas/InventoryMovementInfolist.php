<?php

namespace App\Filament\Resources\InventoryMovements\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InventoryMovementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product.name'),
                TextEntry::make('delta')
                    ->label('Delta'),
                TextEntry::make('reason'),
                TextEntry::make('reference_type'),
                TextEntry::make('reference_id')
                    ->numeric()
                    ->label('Reference ID'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('occurred_at')
                    ->label('Occurred At')
                    ->dateTime('M d, Y H:i')
                    ->timezone('Indian/Maldives'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}

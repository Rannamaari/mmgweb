<?php

namespace App\Filament\Resources\InventoryMovements\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;

class InventoryMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->searchable(),
                TextColumn::make('delta')
                    ->label('Delta')
                    ->sortable()
                    ->color(fn($record) => $record->delta_color),
                TextColumn::make('reason')
                    ->searchable(),
                TextColumn::make('reference_type')
                    ->searchable()
                    ->label('Reference Type'),
                TextColumn::make('reference_id')
                    ->numeric()
                    ->sortable()
                    ->label('Reference ID'),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('occurred_at')
                    ->label('Occurred At')
                    ->dateTime('M d, Y H:i')
                    ->timezone('Indian/Maldives')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.admin.resources.inventory-movements.view', $record)),
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn($record) => route('filament.admin.resources.inventory-movements.edit', $record)),
            ])
            ->bulkActions([
                Action::make('delete')
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $records->each->delete();
                    }),
            ]);
    }
}

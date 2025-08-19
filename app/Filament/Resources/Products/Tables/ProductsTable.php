<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'asc')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'part' => 'success',
                        'service' => 'info',
                    }),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->placeholder('N/A'),

                TextColumn::make('price')
                    ->label('Price')
                    ->money('MVR')
                    ->sortable(),

                TextColumn::make('stock_qty')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->type === 'service' ? 'N/A' : $record->stock_qty),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'part' => 'Parts',
                        'service' => 'Services',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn($record) => route('filament.admin.resources.products.edit', $record)),
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

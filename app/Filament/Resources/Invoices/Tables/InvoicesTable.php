<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->label('Invoice #'),

                TextColumn::make('date')
                    ->dateTime('M d, Y H:i')
                    ->timezone('Indian/Maldives')
                    ->sortable()
                    ->label('Date'),

                TextColumn::make('customer.name')
                    ->searchable()
                    ->placeholder('Walk-in Customer')
                    ->label('Customer'),

                TextColumn::make('motorcycle.plate_no')
                    ->searchable()
                    ->placeholder('N/A')
                    ->label('Motorcycle'),

                TextColumn::make('total')
                    ->money('MVR')
                    ->sortable()
                    ->label('Total'),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'danger' => 'unpaid',
                        'success' => 'paid',
                        'gray' => 'cancelled',
                    ])
                    ->label('Status'),

                TextColumn::make('payments_sum_amount')
                    ->money('MVR')
                    ->label('Paid Amount')
                    ->placeholder('ރ0.00'),

                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->timezone('Indian/Maldives')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created'),

                TextColumn::make('updated_at')
                    ->dateTime('M d, Y H:i')
                    ->timezone('Indian/Maldives')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Updated'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Status'),

                SelectFilter::make('payment_method')
                    ->relationship('payments', 'method')
                    ->options([
                        'cash' => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                    ])
                    ->label('Payment Method'),

                Filter::make('date_range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
                    ->label('Date Range'),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.admin.resources.invoices.view', $record)),
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn($record) => route('filament.admin.resources.invoices.edit', $record)),
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
            ])
            ->defaultSort('created_at', 'desc');
    }
}

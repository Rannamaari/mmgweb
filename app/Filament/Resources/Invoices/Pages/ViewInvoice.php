<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\KeyValueEntry;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('print')
                ->label('Print Invoice')
                ->icon('heroicon-o-printer')
                ->url(fn() => route('invoice.pdf', $this->record))
                ->openUrlInNewTab()
                ->color('success'),
        ];
    }

    protected function getInfolist(): \Filament\Infolists\Infolist
    {
        return \Filament\Infolists\Infolist::make()
            ->schema([
                \Filament\Infolists\Components\Section::make('Invoice Information')
                    ->schema([
                        \Filament\Infolists\Components\Grid::make(3)
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('number')
                                    ->label('Invoice Number')
                                    ->size(\Filament\Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold'),
                                \Filament\Infolists\Components\TextEntry::make('date')
                                    ->label('Invoice Date')
                                    ->dateTime('M d, Y H:i')
                                    ->timezone('Indian/Maldives'),
                                \Filament\Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'draft' => 'warning',
                                        'unpaid' => 'danger',
                                        'paid' => 'success',
                                        'cancelled' => 'gray',
                                    }),
                            ]),
                    ]),

                \Filament\Infolists\Components\Section::make('Customer & Motorcycle')
                    ->schema([
                        \Filament\Infolists\Components\Grid::make(2)
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('customer.name')
                                    ->label('Customer Name')
                                    ->placeholder('Walk-in Customer'),
                                \Filament\Infolists\Components\TextEntry::make('customer.phone')
                                    ->label('Customer Phone')
                                    ->placeholder('N/A'),
                                \Filament\Infolists\Components\TextEntry::make('motorcycle.plate_no')
                                    ->label('Motorcycle Plate')
                                    ->placeholder('N/A'),
                                \Filament\Infolists\Components\TextEntry::make('motorcycle.make')
                                    ->label('Motorcycle Make')
                                    ->placeholder('N/A'),
                            ]),
                    ])
                    ->collapsible(),

                \Filament\Infolists\Components\Section::make('Invoice Items')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                \Filament\Infolists\Components\Grid::make(4)
                                    ->schema([
                                        \Filament\Infolists\Components\TextEntry::make('description')
                                            ->label('Item')
                                            ->weight('medium'),
                                        \Filament\Infolists\Components\TextEntry::make('qty')
                                            ->label('Qty'),
                                        \Filament\Infolists\Components\TextEntry::make('unit_price')
                                            ->label('Unit Price')
                                            ->money('MVR'),
                                        \Filament\Infolists\Components\TextEntry::make('line_total')
                                            ->label('Total')
                                            ->money('MVR')
                                            ->weight('bold'),
                                    ]),
                            ])
                            ->contained(false),
                    ]),

                \Filament\Infolists\Components\Section::make('Payment Information')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('payments')
                            ->schema([
                                \Filament\Infolists\Components\Grid::make(4)
                                    ->schema([
                                        \Filament\Infolists\Components\TextEntry::make('method')
                                            ->label('Payment Method')
                                            ->badge()
                                            ->color(fn(string $state): string => match ($state) {
                                                'cash' => 'success',
                                                'bank_transfer' => 'info',
                                                default => 'gray',
                                            }),
                                        \Filament\Infolists\Components\TextEntry::make('amount')
                                            ->label('Amount')
                                            ->money('MVR')
                                            ->weight('bold'),
                                        \Filament\Infolists\Components\TextEntry::make('received_at')
                                            ->label('Received At')
                                            ->dateTime('M d, Y H:i')
                                            ->timezone('Indian/Maldives'),
                                        \Filament\Infolists\Components\TextEntry::make('reference_no')
                                            ->label('Reference')
                                            ->placeholder('N/A'),
                                    ]),
                            ])
                            ->contained(false),
                    ]),

                \Filament\Infolists\Components\Section::make('Totals')
                    ->schema([
                        \Filament\Infolists\Components\Grid::make(2)
                            ->schema([
                                \Filament\Infolists\Components\KeyValueEntry::make('totals')
                                    ->label('Invoice Summary')
                                    ->keyLabel('Item')
                                    ->valueLabel('Amount')
                                    ->data([
                                        'Subtotal' => 'ރ' . number_format($this->record->subtotal, 2),
                                        'Discount' => 'ރ' . number_format($this->record->discount, 2),
                                        'Tax' => 'ރ' . number_format($this->record->tax, 2),
                                        'Total' => 'ރ' . number_format($this->record->total, 2),
                                        'Paid Amount' => 'ރ' . number_format($this->record->paid_amount, 2),
                                        'Outstanding' => 'ރ' . number_format($this->record->outstanding_amount, 2),
                                    ]),
                            ]),
                    ]),

                \Filament\Infolists\Components\Section::make('Notes')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('notes')
                            ->label('Invoice Notes')
                            ->placeholder('No notes added')
                            ->markdown(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}

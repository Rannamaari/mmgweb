<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Models\Booking;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use App\Services\TelegramService;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),
                TextColumn::make('service_type')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bike_make')
                    ->label('Make')
                    ->searchable(),
                TextColumn::make('bike_model')
                    ->label('Model')
                    ->searchable(),
                TextColumn::make('preferred_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('preferred_time')
                    ->label('Time')
                    ->time('H:i'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                TextColumn::make('estimated_cost')
                    ->label('Est. Cost')
                    ->money('MVR')
                    ->sortable(),
                TextColumn::make('final_cost')
                    ->label('Final Cost')
                    ->money('MVR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Booked On')
                    ->dateTime('M d, Y H:i')
                    ->timezone('Indian/Maldives')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('service_type')
                    ->options([
                        'Full Service' => 'Full Service',
                        'Oil Change' => 'Oil Change',
                        'Tyre Change' => 'Tyre Change',
                        'Brake Service' => 'Brake Service',
                        'Electrical Repair' => 'Electrical Repair',
                        'Engine Overhaul' => 'Engine Overhaul',
                        'Wash/Detail' => 'Wash/Detail',
                        'Body Wrap' => 'Body Wrap',
                        'Road-Worthiness' => 'Road-Worthiness',
                        'Custom Work' => 'Custom Work',
                    ]),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn(Booking $record): string => route('filament.admin.resources.bookings.edit', ['record' => $record])),
                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn(Booking $record) => $record->delete()),
                Action::make('sendStatusUpdate')
                    ->label('Send Status Update')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->action(function (Booking $record) {
                        $telegramService = new TelegramService();
                        $success = $telegramService->sendStatusUpdate($record);
                        if ($success) {
                            return redirect()->back()->with('success', 'Status update sent to Telegram successfully!');
                        } else {
                            return redirect()->back()->with('error', 'Failed to send status update to Telegram.');
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Send Status Update')
                    ->modalDescription('This will send the current booking status to your Telegram bot.')
                    ->modalSubmitActionLabel('Send Update'),
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

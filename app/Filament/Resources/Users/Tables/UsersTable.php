<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                BadgeColumn::make('roles.name')
                    ->label('Roles')
                    ->colors(['primary', 'success', 'warning', 'danger'])
                    ->separator(',')
                    ->limit(3),
                    
                TextColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->dateTime()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->email_verified_at ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Verified' : 'Not Verified'),
                    
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
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Filter by Role')
                    ->multiple()
                    ->preload(),
                    
                SelectFilter::make('email_verified')
                    ->options([
                        '1' => 'Verified',
                        '0' => 'Not Verified',
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['values'])) {
                            if (in_array('1', $data['values'])) {
                                $query->whereNotNull('email_verified_at');
                            }
                            if (in_array('0', $data['values'])) {
                                $query->whereNull('email_verified_at');
                            }
                        }
                    })
                    ->label('Email Verification Status'),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn ($record) => route('filament.admin.resources.users.edit', $record)),
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

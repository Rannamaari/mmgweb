<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('description')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('permissions')
                    ->label('Permissions')
                    ->colors(['primary', 'success', 'warning'])
                    ->limit(5)
                    ->separator(',')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return array_slice($state, 0, 5);
                        }
                        return [];
                    }),

                BadgeColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->color('info'),

                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),

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
                SelectFilter::make('is_active')
                    ->options([
                        'true' => 'Active',
                        'false' => 'Inactive',
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['values'])) {
                            if (in_array('true', $data['values'])) {
                                $query->whereRaw('is_active = true');
                            }
                            if (in_array('false', $data['values'])) {
                                $query->whereRaw('is_active = false');
                            }
                        }
                    })
                    ->label('Status'),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn($record) => route('filament.admin.resources.roles.edit', $record)),
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

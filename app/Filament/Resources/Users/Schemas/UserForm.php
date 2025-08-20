<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Role;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                DateTimePicker::make('email_verified_at')
                    ->label('Email verified at')
                    ->nullable(),

                TextInput::make('password')
                    ->password()
                    ->required(fn(Get $get): bool => !$get('id'))
                    ->minLength(8)
                    ->confirmed()
                    ->dehydrated(fn($state): bool => filled($state))
                    ->rules(['min:8'])
                    ->live(onBlur: true),

                TextInput::make('password_confirmation')
                    ->password()
                    ->required(fn(Get $get): bool => !$get('id'))
                    ->minLength(8)
                    ->dehydrated(false)
                    ->live(onBlur: true),

                Select::make('roles')
                    ->label('User Roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->options(Role::whereRaw('is_active = true')->pluck('name', 'id'))
                    ->helperText('Select roles to assign to this user. Users can have multiple roles.'),
            ]);
    }
}

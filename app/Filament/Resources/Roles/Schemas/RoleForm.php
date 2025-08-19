<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Display name for the role (e.g., "POS Manager")'),
                    
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Unique identifier for the role (e.g., "pos-manager")')
                    ->rules(['regex:/^[a-z0-9-]+$/'])
                    ->helperText('Only lowercase letters, numbers, and hyphens allowed'),
                    
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('Brief description of what this role can do'),
                    
                CheckboxList::make('permissions')
                    ->label('Permissions')
                    ->options(Role::getAvailablePermissions())
                    ->columns(2)
                    ->searchable()
                    ->helperText('Select the permissions this role should have'),
                    
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Inactive roles cannot be assigned to users'),
            ]);
    }
}

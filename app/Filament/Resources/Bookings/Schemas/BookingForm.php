<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Customer Information
                TextInput::make('name')
                    ->label('Customer Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),

                TextInput::make('phone')
                    ->label('Phone/WhatsApp')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),

                // Service Information
                Select::make('service_type')
                    ->label('Service Type')
                    ->required()
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

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending'),

                // Bike Information
                TextInput::make('bike_make')
                    ->label('Bike Make')
                    ->required()
                    ->maxLength(255),

                TextInput::make('bike_model')
                    ->label('Bike Model')
                    ->required()
                    ->maxLength(255),

                TextInput::make('bike_year')
                    ->label('Year')
                    ->maxLength(255),

                TextInput::make('plate_number')
                    ->label('Plate Number')
                    ->maxLength(255),

                // Appointment Details
                DatePicker::make('preferred_date')
                    ->label('Preferred Date')
                    ->required(),

                TimePicker::make('preferred_time')
                    ->label('Preferred Time')
                    ->required(),

                // Pickup & Delivery
                Toggle::make('pickup_needed')
                    ->label('Pickup Needed')
                    ->default(false),

                Textarea::make('pickup_address')
                    ->label('Pickup Address')
                    ->rows(3)
                    ->visible(fn(Get $get): bool => $get('pickup_needed')),

                // Additional Information
                Textarea::make('issue_description')
                    ->label('Issue Description')
                    ->rows(4)
                    ->placeholder('Describe the issue or service needed...')
                    ->columnSpanFull(),

                // Pricing
                TextInput::make('estimated_cost')
                    ->label('Estimated Cost')
                    ->numeric()
                    ->prefix('ރ')
                    ->placeholder('0.00'),

                TextInput::make('final_cost')
                    ->label('Final Cost')
                    ->numeric()
                    ->prefix('ރ')
                    ->placeholder('0.00'),

                // Admin Notes
                Textarea::make('admin_notes')
                    ->label('Admin Notes')
                    ->rows(3)
                    ->placeholder('Internal notes for staff...')
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}

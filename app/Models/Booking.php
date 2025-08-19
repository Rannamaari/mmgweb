<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'service_type',
        'bike_make',
        'bike_model',
        'bike_year',
        'plate_number',
        'preferred_date',
        'preferred_time',
        'pickup_needed',
        'pickup_address',
        'issue_description',
        'status',
        'admin_notes',
        'estimated_cost',
        'final_cost',
        'completed_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'preferred_time' => 'datetime:H:i',
        'pickup_needed' => 'boolean',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }

    public function getFormattedCostAttribute(): string
    {
        if ($this->final_cost) {
            return 'ރ' . number_format($this->final_cost, 2);
        }
        if ($this->estimated_cost) {
            return 'ރ' . number_format($this->estimated_cost, 2);
        }
        return 'TBD';
    }
}

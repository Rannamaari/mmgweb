<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get users that have this role
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return !empty(array_intersect($permissions, $this->permissions ?? []));
    }

    /**
     * Check if role has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return empty(array_diff($permissions, $this->permissions ?? []));
    }

    /**
     * Get available permissions
     */
    public static function getAvailablePermissions(): array
    {
        return [
            // Admin Panel Permissions
            'admin.access' => 'Access Admin Panel',
            'admin.dashboard' => 'View Dashboard',
            'admin.users' => 'Manage Users',
            'admin.roles' => 'Manage Roles',
            'admin.products' => 'Manage Products',
            'admin.customers' => 'Manage Customers',
            'admin.motorcycles' => 'Manage Motorcycles',
            'admin.invoices' => 'Manage Invoices',
            'admin.inventory' => 'Manage Inventory',
            'admin.bookings' => 'Manage Bookings',
            'admin.reports' => 'View Reports',
            'admin.settings' => 'Manage Settings',
            
            // POS System Permissions
            'pos.access' => 'Access POS System',
            'pos.sales' => 'Create Sales',
            'pos.refunds' => 'Process Refunds',
            'pos.customers' => 'Manage Customers in POS',
            'pos.products' => 'View Products in POS',
            'pos.reports' => 'View POS Reports',
            
            // Booking System Permissions
            'booking.access' => 'Access Booking System',
            'booking.create' => 'Create Bookings',
            'booking.edit' => 'Edit Bookings',
            'booking.delete' => 'Delete Bookings',
            'booking.view' => 'View Bookings',
            
            // General Permissions
            'profile.edit' => 'Edit Own Profile',
            'profile.view' => 'View Own Profile',
        ];
    }

    /**
     * Get default roles
     */
    public static function getDefaultRoles(): array
    {
        return [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to all features',
                'permissions' => array_keys(self::getAvailablePermissions()),
                'is_active' => true
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access to most features',
                'permissions' => [
                    'admin.access', 'admin.dashboard', 'admin.users', 'admin.products',
                    'admin.customers', 'admin.motorcycles', 'admin.invoices', 'admin.inventory',
                    'admin.bookings', 'admin.reports', 'pos.access', 'pos.sales', 'pos.refunds',
                    'pos.customers', 'pos.products', 'pos.reports', 'booking.access',
                    'booking.create', 'booking.edit', 'booking.view', 'profile.edit', 'profile.view'
                ],
                'is_active' => true
            ],
            [
                'name' => 'POS Manager',
                'slug' => 'pos-manager',
                'description' => 'Access to POS system and basic admin features',
                'permissions' => [
                    'pos.access', 'pos.sales', 'pos.refunds', 'pos.customers', 'pos.products',
                    'pos.reports', 'admin.products', 'admin.customers', 'admin.invoices',
                    'admin.reports', 'booking.access', 'booking.create', 'booking.edit',
                    'booking.view', 'profile.edit', 'profile.view'
                ],
                'is_active' => true
            ],
            [
                'name' => 'POS Operator',
                'slug' => 'pos-operator',
                'description' => 'Basic POS operations',
                'permissions' => [
                    'pos.access', 'pos.sales', 'pos.customers', 'pos.products',
                    'booking.access', 'booking.create', 'booking.view', 'profile.edit', 'profile.view'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Booking Manager',
                'slug' => 'booking-manager',
                'description' => 'Manage booking system',
                'permissions' => [
                    'booking.access', 'booking.create', 'booking.edit', 'booking.delete',
                    'booking.view', 'admin.bookings', 'profile.edit', 'profile.view'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access to reports and data',
                'permissions' => [
                    'admin.dashboard', 'admin.reports', 'pos.reports', 'booking.view',
                    'profile.view'
                ],
                'is_active' => true
            ]
        ];
    }
}

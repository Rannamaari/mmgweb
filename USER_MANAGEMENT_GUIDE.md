# 👥 MMG User Management System Guide

## 🎯 Overview

The MMG User Management System provides **role-based access control** (RBAC) for your garage management system. Users can be assigned different roles with specific permissions for accessing the POS system, admin panel, and booking system.

---

## 🚀 Quick Start

### **1. Access User Management**

-   **Admin Panel**: `http://localhost/admin`
-   **Login**: `admin@mmg.mv` / `password`
-   **Navigate**: Users & Roles sections

### **2. Create New User**

1. Go to **Users** section
2. Click **Create User**
3. Fill in user details
4. Assign roles
5. Save

### **3. Assign Roles**

1. Go to **Roles** section
2. Select a role
3. Configure permissions
4. Assign to users

---

## 🛡️ Available Roles

### **1. Super Admin** 🔴

-   **Access**: Full system access
-   **Permissions**: All 25 permissions
-   **Use Case**: System administrator
-   **Features**:
    -   Manage all users and roles
    -   Access all admin features
    -   Full POS and booking access
    -   System settings

### **2. Admin** 🟠

-   **Access**: Administrative access
-   **Permissions**: 22 permissions
-   **Use Case**: General manager
-   **Features**:
    -   Manage users (except roles)
    -   Access most admin features
    -   Full POS and booking access
    -   Reports and analytics

### **3. POS Manager** 🟡

-   **Access**: POS system + basic admin
-   **Permissions**: 16 permissions
-   **Use Case**: Sales manager
-   **Features**:
    -   Full POS operations
    -   Manage products and customers
    -   View invoices and reports
    -   Basic booking access

### **4. POS Operator** 🟢

-   **Access**: Basic POS operations
-   **Permissions**: 9 permissions
-   **Use Case**: Cashier/sales staff
-   **Features**:
    -   Create sales transactions
    -   Manage customers
    -   View products
    -   Basic booking access

### **5. Booking Manager** 🔵

-   **Access**: Booking system focus
-   **Permissions**: 8 permissions
-   **Use Case**: Service coordinator
-   **Features**:
    -   Full booking management
    -   Create, edit, delete bookings
    -   View booking reports
    -   Profile management

### **6. Viewer** ⚪

-   **Access**: Read-only access
-   **Permissions**: 5 permissions
-   **Use Case**: Reports viewer
-   **Features**:
    -   View dashboard
    -   Access reports
    -   View bookings
    -   Profile viewing

---

## 🔑 Permission System

### **Admin Panel Permissions**

```
✅ admin.access          - Access Admin Panel
✅ admin.dashboard       - View Dashboard
✅ admin.users           - Manage Users
✅ admin.roles           - Manage Roles
✅ admin.products        - Manage Products
✅ admin.customers       - Manage Customers
✅ admin.motorcycles     - Manage Motorcycles
✅ admin.invoices        - Manage Invoices
✅ admin.inventory       - Manage Inventory
✅ admin.bookings        - Manage Bookings
✅ admin.reports         - View Reports
✅ admin.settings        - Manage Settings
```

### **POS System Permissions**

```
✅ pos.access            - Access POS System
✅ pos.sales             - Create Sales
✅ pos.refunds           - Process Refunds
✅ pos.customers         - Manage Customers in POS
✅ pos.products          - View Products in POS
✅ pos.reports           - View POS Reports
```

### **Booking System Permissions**

```
✅ booking.access        - Access Booking System
✅ booking.create        - Create Bookings
✅ booking.edit          - Edit Bookings
✅ booking.delete        - Delete Bookings
✅ booking.view          - View Bookings
```

### **General Permissions**

```
✅ profile.edit          - Edit Own Profile
✅ profile.view          - View Own Profile
```

---

## 👤 User Management

### **Creating Users**

#### **Via Admin Panel**

1. **Navigate**: Admin Panel → Users
2. **Click**: "Create User"
3. **Fill Form**:
    - **Name**: Full name
    - **Email**: Unique email address
    - **Password**: Minimum 8 characters
    - **Confirm Password**: Must match
    - **Roles**: Select appropriate roles
4. **Save**: User is created

#### **Via Command Line**

```bash
# Create user with specific role
php artisan tinker

$user = App\Models\User::create([
    'name' => 'John Doe',
    'email' => 'john@mmg.mv',
    'password' => Hash::make('securepassword123'),
    'email_verified_at' => now(),
]);

$posManagerRole = App\Models\Role::where('slug', 'pos-manager')->first();
$user->roles()->attach($posManagerRole->id);
```

### **Editing Users**

1. **Navigate**: Admin Panel → Users
2. **Click**: Edit button (pencil icon)
3. **Modify**: Any user details
4. **Update Roles**: Add/remove roles
5. **Save**: Changes applied

### **Deleting Users**

1. **Navigate**: Admin Panel → Users
2. **Select**: Users to delete
3. **Click**: Delete button
4. **Confirm**: Deletion

---

## 🎭 Role Management

### **Creating Custom Roles**

#### **Via Admin Panel**

1. **Navigate**: Admin Panel → Roles
2. **Click**: "Create Role"
3. **Fill Form**:
    - **Name**: Display name
    - **Slug**: Unique identifier (lowercase, hyphens)
    - **Description**: Role purpose
    - **Permissions**: Select required permissions
    - **Active**: Enable/disable role
4. **Save**: Role is created

#### **Via Code**

```php
// Create custom role
$customRole = App\Models\Role::create([
    'name' => 'Service Technician',
    'slug' => 'service-technician',
    'description' => 'Can manage service bookings and view customer data',
    'permissions' => [
        'booking.access',
        'booking.create',
        'booking.edit',
        'booking.view',
        'admin.customers',
        'profile.edit',
        'profile.view'
    ],
    'is_active' => true
]);
```

### **Editing Roles**

1. **Navigate**: Admin Panel → Roles
2. **Click**: Edit button
3. **Modify**: Role details and permissions
4. **Save**: Changes applied

### **Role Permissions**

-   **View**: See assigned permissions
-   **Edit**: Modify permission list
-   **Search**: Find specific permissions
-   **Bulk**: Select multiple permissions

---

## 🔒 Security Features

### **Permission Checking**

#### **In Controllers**

```php
// Check single permission
if (auth()->user()->hasPermission('pos.sales')) {
    // Allow sales creation
}

// Check multiple permissions
if (auth()->user()->hasAnyPermission(['admin.users', 'admin.roles'])) {
    // Allow user/role management
}

// Check all permissions
if (auth()->user()->hasAllPermissions(['pos.access', 'pos.sales'])) {
    // Allow POS operations
}
```

#### **In Blade Templates**

```php
@if(auth()->user()->hasPermission('admin.reports'))
    <a href="/admin/reports">View Reports</a>
@endif

@if(auth()->user()->hasRole('pos-manager'))
    <div class="manager-only-content">
        <!-- Manager specific content -->
    </div>
@endif
```

#### **In Routes**

```php
// Protect routes with middleware
Route::middleware(['auth', 'permission:admin.users'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
});

Route::middleware(['auth', 'permission:pos.access'])->group(function () {
    Route::get('/pos', [POSController::class, 'index']);
});
```

### **Access Control**

#### **Filament Panel Access**

```php
// In User model
public function canAccessPanel(Panel $panel): bool
{
    return $this->hasPermission('admin.access');
}
```

#### **API Protection**

```php
// Protect API endpoints
Route::middleware(['auth:sanctum', 'permission:pos.sales'])->post('/api/sales', [SalesController::class, 'store']);
```

---

## 📊 User Analytics

### **User Statistics**

-   **Total Users**: Count of all users
-   **Active Users**: Users with active roles
-   **Role Distribution**: Users per role
-   **Recent Activity**: Last login times

### **Permission Analytics**

-   **Most Used Permissions**: Popular features
-   **Permission Gaps**: Missing permissions
-   **Role Efficiency**: Permission utilization

### **Security Monitoring**

-   **Login Attempts**: Failed login tracking
-   **Permission Denials**: Access control logs
-   **Role Changes**: Audit trail

---

## 🚨 Best Practices

### **User Management**

1. **Strong Passwords**: Minimum 8 characters, mixed case
2. **Email Verification**: Verify all user emails
3. **Role Assignment**: Assign minimal required roles
4. **Regular Review**: Audit user access monthly
5. **Account Lockout**: Implement failed login limits

### **Role Design**

1. **Principle of Least Privilege**: Minimum required permissions
2. **Role Hierarchy**: Clear permission progression
3. **Custom Roles**: Create specific business roles
4. **Permission Groups**: Logical permission organization
5. **Documentation**: Document role purposes

### **Security**

1. **Regular Audits**: Review permissions quarterly
2. **Access Reviews**: Validate user access needs
3. **Incident Response**: Plan for security incidents
4. **Backup Access**: Maintain emergency admin access
5. **Monitoring**: Track permission usage

---

## 🔧 Troubleshooting

### **Common Issues**

#### **User Can't Access Admin Panel**

```bash
# Check user permissions
php artisan tinker
$user = App\Models\User::where('email', 'user@mmg.mv')->first();
echo $user->hasPermission('admin.access') ? 'Has access' : 'No access';
```

#### **Role Not Working**

```bash
# Check role assignment
$user = App\Models\User::where('email', 'user@mmg.mv')->first();
echo "Roles: " . $user->roles->pluck('name')->implode(', ');
echo "Permissions: " . implode(', ', $user->getAllPermissions());
```

#### **Permission Denied**

```bash
# Check specific permission
$user = App\Models\User::where('email', 'user@mmg.mv')->first();
$permission = 'pos.sales';
echo $user->hasPermission($permission) ? "Has {$permission}" : "Missing {$permission}";
```

### **Debug Commands**

```bash
# List all users and roles
php artisan tinker --execute="
    App\Models\User::with('roles')->get()->each(function(\$user) {
        echo \$user->name . ' (' . \$user->email . '): ' . \$user->roles->pluck('name')->implode(', ') . PHP_EOL;
    });
"

# List all roles and permissions
php artisan tinker --execute="
    App\Models\Role::all()->each(function(\$role) {
        echo \$role->name . ': ' . count(\$role->permissions) . ' permissions' . PHP_EOL;
    });
"
```

---

## 📞 Quick Reference

### **Essential Commands**

```bash
# Create user
php artisan tinker
$user = App\Models\User::create([...]);

# Assign role
$user->roles()->attach($roleId);

# Check permissions
$user->hasPermission('admin.access');

# List user permissions
$user->getAllPermissions();
```

### **Role Slugs**

-   `super-admin` - Full access
-   `admin` - Administrative access
-   `pos-manager` - POS management
-   `pos-operator` - Basic POS operations
-   `booking-manager` - Booking management
-   `viewer` - Read-only access

### **Key Permissions**

-   `admin.access` - Admin panel access
-   `pos.access` - POS system access
-   `booking.access` - Booking system access
-   `admin.users` - User management
-   `admin.roles` - Role management

Your MMG User Management System is now **fully operational** with role-based access control! 👥🔐

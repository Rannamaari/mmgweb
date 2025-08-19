# Micro Moto Garage - Laravel + Filament MVP

A minimal, production-ready POS and admin system for motorcycle garage operations built with Laravel 12 and Filament 4.

## Features

- **Customer Management**: Store customer details, phone numbers, GST numbers, and addresses
- **Motorcycle Registry**: Track motorcycles by plate number, make, model, year, and VIN
- **Inventory Management**: 
  - Parts with stock tracking and automatic deduction on sales
  - Services with pricing (no stock tracking)
- **Invoice System**:
  - Draft → Unpaid → Paid workflow
  - Support for walk-in customers (no customer required)
  - Automatic inventory deduction on finalization
  - PDF generation for printing
- **Payment Tracking**: Cash and bank transfer support
- **Quick Sale POS**: Fast cash transactions for mechanics
- **Audit Trail**: Complete inventory movement history

## Installation

### Prerequisites
- PHP 8.2+
- PostgreSQL
- Composer

### Setup

1. **Database Configuration**:
Make sure your PostgreSQL database settings are configured in `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mmgweb
DB_USERNAME=munaad
DB_PASSWORD=nubunaanan
```

2. **Install Dependencies & Setup**:
```bash
composer install
php artisan key:generate
php artisan migrate --seed
```

3. **Start Development Server**:
```bash
php artisan serve
```

## Usage

### Admin Access
- **URL**: http://localhost:8000/admin
- **Login**: admin@mmg.local / password

**If login fails**, reset the admin user:
```bash
php artisan mmg:create-admin
```

### Main Features

1. **Homepage** (`/`)
   - Professional landing page for Micro Moto Garage
   - Service overview and contact information
   - Navigation to admin and POS systems

2. **Customer Management** (`/admin/customers`)
   - Add customers with GST numbers for business invoices
   - Track multiple motorcycles per customer
   - Organized under "People" navigation group

3. **Product & Service Catalog** (`/admin/products`)
   - **Parts**: Items with stock quantities that auto-deduct on sales
   - **Services**: Labour/service items with pricing (no stock tracking)
   - Dynamic forms that show/hide stock fields based on type
   - Filter by type (Parts/Services) and status
   - Organized under "Catalog" navigation group

4. **Invoice Management** (`/admin/invoices`)
   - Create draft invoices
   - Add line items (parts/services) with automatic pricing
   - "Finalize" to mark unpaid and deduct stock
   - Add payments and "Mark Paid" when fully paid
   - Print professional PDF invoices with company branding

5. **POS System** (`/pos`) - *Requires Admin Login*
   - Fast cash sale interface for mechanics
   - Real-time product search by name or SKU
   - Shopping cart with quantity management
   - Customer selection (optional for walk-in sales)
   - Cash and bank transfer payment methods
   - Immediate invoice generation and printing
   - Stock warnings for parts

6. **Inventory Audit** (`/admin/inventory-movements`)
   - Read-only view of all stock movements
   - Shows sale, purchase, adjustment reasons
   - Complete audit trail

### Invoice Workflow

1. **Draft**: Create invoice, add items, set customer/motorcycle
2. **Finalize**: Computes totals, marks unpaid, decrements part inventory
3. **Add Payments**: Record cash/bank transfer payments
4. **Mark Paid**: When payments equal total amount
5. **Print PDF**: Generate printable invoice

### Walk-in Sales
- Leave customer field blank for cash customers
- Invoice will show "Cash Sale" instead of customer details

## Demo Data

After seeding, you'll have:
- **Admin user**: admin@mmg.local / password
- **3 customers** with motorcycles and GST numbers
- **10 motorcycle parts** with stock (Engine Oil, Brake Pads, Tyres, etc.)
- **9 service types** (Basic Service, Engine Service, Brake Service, etc.)

## Technical Architecture

- **Laravel 12** with PostgreSQL
- **Filament 4** for admin interface
- **DomPDF** for invoice generation
- Clean service-based architecture
- Complete audit trail for inventory

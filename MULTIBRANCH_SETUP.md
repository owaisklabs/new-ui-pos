# Multi-Branch POS System Implementation

## Overview
This document outlines the multi-branch support implementation for the POS system, including role-based access control and comprehensive reporting features.

## Database Structure

### New Tables

#### 1. **branches**
Stores information about each branch.
- `id` - Primary key
- `name` - Branch name (unique)
- `code` - Branch code (unique)
- `address` - Branch address
- `phone` - Branch phone number
- `email` - Branch email
- `is_active` - Boolean flag for active/inactive status
- `created_at, updated_at` - Timestamps

#### 2. **roles**
Defines user roles in the system.
- `id` - Primary key
- `name` - Role name (admin, branch_admin, branch_user, cashier)
- `description` - Role description
- `created_at, updated_at` - Timestamps

#### 3. **user_branch_roles** (Pivot Table)
Associates users with branches and roles.
- `id` - Primary key
- `user_id` - Foreign key to users
- `branch_id` - Foreign key to branches
- `role_id` - Foreign key to roles
- `is_active` - Boolean flag
- `created_at, updated_at` - Timestamps
- **Unique Constraint**: `(user_id, branch_id, role_id)`

### Modified Tables

#### **users**
New column:
- `default_branch_id` - Foreign key to branches (nullable)

#### **sales**
New column:
- `branch_id` - Foreign key to branches (indexed)

#### **purchases**
New column:
- `branch_id` - Foreign key to branches (indexed)

#### **expenses** (Previously no table - now created)
New table with columns:
- `id` - Primary key
- `branch_id` - Foreign key to branches
- `title` - Expense title
- `expense_type` - Type of expense
- `amount` - Expense amount
- `date` - Expense date
- `created_by` - User who created it
- `created_at, updated_at` - Timestamps

#### **books**
New column:
- `branch_id` - Foreign key to branches (nullable - allows system-wide and branch-specific books)

#### **inventories**
New column:
- `branch_id` - Foreign key to branches
- **Unique Constraint**: `(book_id, branch_id)` - Each book can have inventory per branch

#### **customers**
New column:
- `branch_id` - Foreign key to branches (nullable - allows system-wide and branch-specific customers)

## Role Hierarchy

### 1. **Admin** (`admin`)
- System-wide access to all branches and data
- Can manage branches, users, and roles
- Can access all reports
- No branch restrictions

### 2. **Branch Admin** (`branch_admin`)
- Admin privileges for a specific branch
- Can manage branch users and roles (within their branch)
- Can access all reports for their branch
- Can view and modify transactions for their branch
- Assigned to one or more branches

### 3. **Branch User** (`branch_user`)
- Standard user access to one assigned branch
- Can create and view sales, purchases, expenses (for their branch)
- Can access inventory for their branch
- Cannot access other branches' data
- Assigned to a single branch

### 4. **Cashier** (`cashier`)
- Limited access for point-of-sale operations
- Can create sales transactions only
- Cannot access admin or reporting features
- Assigned to a single branch

## Models & Relationships

### User Model
```php
public function branches()              // Belongs to many branches
public function userBranchRoles()       // Has many user_branch_roles
public function defaultBranch()         // Belongs to one default branch
public function roles()                 // Belongs to many roles

// Helper methods:
public function hasRole($role)                    // Check if user has a role
public function hasRoleInBranch($role, $branchId) // Check role for specific branch
public function isAdmin()                        // Check if system admin
public function isBranchAdmin($branchId)         // Check if branch admin
public function canAccessBranch($branchId)       // Check if user can access branch
public function getAccessibleBranches()          // Get all accessible branches
```

### Branch Model
```php
public function users()            // Belongs to many users
public function sales()            // Has many sales
public function purchases()        // Has many purchases
public function expenses()         // Has many expenses
public function books()            // Has many books
public function inventories()      // Has many inventories
public function customers()        // Has many customers
public function userBranchRoles()  // Has many user_branch_roles
```

### Transaction Models (Sale, Purchase, Expense, etc.)
All transaction models include:
```php
public function branch()                    // Belongs to one branch
public function scopeByBranch($query, $id) // Filter by branch
public function scopeByDateRange()         // Filter by date range
```

## API/Query Usage

### Filter by Branch
```php
// Get sales for a specific branch
$sales = Sale::byBranch($branchId)->get();

// Get all expenses for a branch in a date range
$expenses = Expense::byBranch($branchId)
    ->byDateRange($startDate, $endDate)
    ->get();

// Get inventory for a branch
$inventory = Inventory::byBranch($branchId)->get();
```

### User Authorization
```php
// Check if user can access branch
if ($user->canAccessBranch($branchId)) {
    // Allow access
}

// Check if user is admin
if ($user->isAdmin()) {
    // System-wide access
}

// Check if user is branch admin
if ($user->isBranchAdmin($branchId)) {
    // Branch admin access
}

// Get all accessible branches
$branches = $user->getAccessibleBranches();
```

## Controllers

### BranchController
Manages branch CRUD operations and user-branch role assignments.
- `index()` - List all branches
- `create()` - Show create branch form
- `store()` - Create new branch
- `show()` - Show branch details and users
- `edit()` - Show edit branch form
- `update()` - Update branch
- `destroy()` - Delete branch
- `assignUser()` - Assign user to branch with role
- `removeUser()` - Remove user from branch

### ReportController
Comprehensive reporting functionality.
- `index()` - Report dashboard
- `salesReport()` - Sales by branch, date, status
- `inventoryReport()` - Inventory levels and low stock
- `stockReport()` - Stock details by book and branch
- `purchaseReport()` - Purchase transactions
- `expenseReport()` - Expense tracking
- `branchComparison()` - Cross-branch performance comparison

## Reports

### 1. **Sales Report**
- Filter by: Branch, Date Range, Status
- Shows: Invoice, Customer, Amount, Paid, Outstanding
- Summary: Total Sales, Total Paid, Outstanding, Count

### 2. **Inventory Report**
- Filter by: Branch, Low Stock
- Shows: Book, Quantity, Location, Value
- Summary: Total Items, Total Value, Low Stock Count

### 3. **Stock Report**
- Filter by: Branch
- Shows: Book details with inventory across branches
- Detailed stock-keeping information

### 4. **Purchase Report**
- Filter by: Branch, Date Range, Status
- Shows: Supplier, Amount, Status
- Summary: Total Purchases, Count

### 5. **Expense Report**
- Filter by: Branch, Date Range, Type
- Shows: Title, Type, Amount, Date
- Summary: Total Expenses, Count

### 6. **Branch Comparison**
- Admin only access
- Compare sales, purchases, and expenses across branches
- Date range filtering

## Database Migrations

Run migrations in order:
```bash
# Create branches table
php artisan migrate --path=database/migrations/2026_10_24_000001_create_branches_table.php

# Create roles table with default roles
php artisan migrate --path=database/migrations/2026_10_24_000002_create_roles_table.php

# Create user_branch_roles junction table
php artisan migrate --path=database/migrations/2026_10_24_000003_create_user_branch_roles_table.php

# Add branch_id to transaction tables
php artisan migrate --path=database/migrations/2026_10_24_000004_add_branch_id_to_sales_table.php
php artisan migrate --path=database/migrations/2026_10_24_000005_add_branch_id_to_purchases_table.php

# Create expenses table
php artisan migrate --path=database/migrations/2026_10_24_000006_create_expenses_table.php

# Add branch_id to books
php artisan migrate --path=database/migrations/2026_10_24_000007_add_branch_id_to_books_table.php

# Add branch_id to inventories
php artisan migrate --path=database/migrations/2026_10_24_000008_add_branch_id_to_inventories_table.php

# Add branch_id to customers
php artisan migrate --path=database/migrations/2026_10_24_000009_add_branch_id_to_customers_table.php

# Add default_branch_id to users
php artisan migrate --path=database/migrations/2026_10_24_000010_add_default_branch_id_to_users_table.php
```

Or run all together:
```bash
php artisan migrate
```

## Seeding Data (Optional)

Create default branches and assign users:

```php
// Create branches
$branch1 = Branch::create([
    'name' => 'Main Branch',
    'code' => 'BR001',
    'address' => '123 Main St',
    'phone' => '123-456-7890',
    'email' => 'main@example.com',
]);

$branch2 = Branch::create([
    'name' => 'Downtown Branch',
    'code' => 'BR002',
    'address' => '456 Downtown Ave',
    'phone' => '098-765-4321',
    'email' => 'downtown@example.com',
]);

// Assign admin user to all branches
$adminUser = User::find(1);
$adminUser->update(['default_branch_id' => $branch1->id]);

$adminRole = Role::where('name', 'admin')->first();
$adminUser->userBranchRoles()->create([
    'branch_id' => null, // Admin has no branch restriction
    'role_id' => $adminRole->id,
]);

// Assign branch user
$branchUser = User::find(2);
$branchUser->update(['default_branch_id' => $branch1->id]);

$branchUserRole = Role::where('name', 'branch_user')->first();
$branchUser->userBranchRoles()->create([
    'branch_id' => $branch1->id,
    'role_id' => $branchUserRole->id,
]);
```

## Implementation Checklist

- [x] Create migration files
- [x] Create Branch, Role, UserBranchRole models
- [x] Update User model with branch relationships
- [x] Update transaction models (Sale, Purchase, Expense, etc.)
- [x] Create BranchController
- [x] Create ReportController
- [x] Create middleware for branch access validation
- [x] Add routes for branches and reports
- [x] Create views for branches
- [x] Create views for reports
- [ ] Update existing controllers to include branch_id on create/update
- [ ] Add branch selector to dashboard
- [ ] Add branch context to all views
- [ ] Update authentication to set default branch
- [ ] Test authorization checks

## Data Migration from Old System

Since you're truncating data:

```bash
# Truncate existing data
php artisan tinker
> DB::table('sales')->truncate();
> DB::table('purchases')->truncate();
> DB::table('expenses')->truncate();
> DB::table('inventories')->truncate();
> DB::table('customers')->truncate();
> DB::table('books')->truncate();
> exit;

# Run fresh migrations
php artisan migrate:refresh
```

## Security Considerations

1. **Branch Access Control**: Always check `$user->canAccessBranch($branchId)` before returning data
2. **Query Filtering**: Use scopes like `byBranch()` to automatically filter results
3. **Role-based Authorization**: Check user roles before allowing operations
4. **Audit Trail**: Consider adding created_by and updated_by fields to track changes
5. **API Validation**: Always validate branch_id in requests matches user's accessible branches

## Future Enhancements

1. **Permissions System**: More granular permissions per role
2. **Department Management**: Sub-divisions within branches
3. **Stock Transfer**: Transfer inventory between branches
4. **Regional Reports**: Group branches by region
5. **Audit Log**: Complete audit trail of all transactions
6. **Advanced Analytics**: Business intelligence dashboards
7. **Multi-currency**: Support different currencies per branch
8. **User Settings**: Customize branch switching preferences

## Troubleshooting

### Migration Issues
```bash
# Check migration status
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback
```

### Authorization Issues
- Verify user has branch assigned in `user_branch_roles`
- Check role is active (`is_active = true`)
- Ensure default_branch_id is set on user record

### Data Not Showing
- Verify branch_id is set on records
- Check user can access that branch
- Use `->byBranch($branchId)` scope in queries

## Support

For issues or questions about the multi-branch implementation, refer to the model relationships and controller logic in the respective files.

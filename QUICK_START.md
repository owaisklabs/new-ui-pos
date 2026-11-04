# Multi-Branch POS System - Quick Start Guide

## Installation (5 minutes)

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Create Test Data
```bash
php artisan tinker
```

Copy and paste this code:

```php
use App\Models\Branch;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Create branches
$b1 = Branch::create([
    'name' => 'Main Branch',
    'code' => 'BR001',
    'address' => '123 Main Street',
    'phone' => '123-456-7890',
    'email' => 'main@shop.com',
    'is_active' => true
]);

$b2 = Branch::create([
    'name' => 'Downtown Store',
    'code' => 'BR002',
    'address' => '456 Downtown Ave',
    'phone' => '098-765-4321',
    'email' => 'downtown@shop.com',
    'is_active' => true
]);

// Create admin
$admin = User::create([
    'name' => 'System Admin',
    'email' => 'admin@shop.com',
    'password' => Hash::make('admin123'),
    'default_branch_id' => $b1->id
]);

$adminRole = Role::where('name', 'admin')->first();
$admin->userBranchRoles()->create([
    'branch_id' => null,
    'role_id' => $adminRole->id,
    'is_active' => true
]);

// Create branch user 1
$user1 = User::create([
    'name' => 'John Cashier',
    'email' => 'john@shop.com',
    'password' => Hash::make('user123'),
    'default_branch_id' => $b1->id
]);

$branchUserRole = Role::where('name', 'branch_user')->first();
$user1->userBranchRoles()->create([
    'branch_id' => $b1->id,
    'role_id' => $branchUserRole->id,
    'is_active' => true
]);

// Create branch user 2
$user2 = User::create([
    'name' => 'Jane Cashier',
    'email' => 'jane@shop.com',
    'password' => Hash::make('user123'),
    'default_branch_id' => $b2->id
]);

$user2->userBranchRoles()->create([
    'branch_id' => $b2->id,
    'role_id' => $branchUserRole->id,
    'is_active' => true
]);

echo "✓ Setup complete!";
exit;
```

## Test Logins

| Email | Password | Role | Branch |
|-------|----------|------|--------|
| admin@shop.com | admin123 | Admin | All |
| john@shop.com | user123 | Branch User | Main |
| jane@shop.com | user123 | Branch User | Downtown |

## Access URLs

- **Dashboard:** http://localhost/dashboard
- **Branches:** http://localhost/dashboard/branches
- **Reports:** http://localhost/dashboard/reports
- **Sales:** http://localhost/dashboard/sales
- **Purchases:** http://localhost/dashboard/purchase
- **Expenses:** http://localhost/dashboard/expenses

## Essential Controller Updates

### Update SaleController

```php
// In store() method, add:
$sale = Sale::create([
    'branch_id' => Auth::user()->default_branch_id,
    'customer_id' => $request->customer_id,
    'invoice_no' => $invoiceNo,
    'sale_date' => now(),
    'total_amount' => $total,
    'paid_amount' => $paid,
    'status' => 'open',
    'created_by' => Auth::id(),
]);

// In index() method, replace with:
public function index()
{
    $user = Auth::user();
    $sales = Sale::when(!$user->isAdmin(), function($q) use ($user) {
        $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
    })->with('customer', 'branch', 'user')
      ->latest()
      ->paginate(20);
    return view('sales.index', compact('sales'));
}
```

### Update PurchaseController

```php
// In store() method, add:
$purchase = Purchase::create([
    'branch_id' => Auth::user()->default_branch_id,
    'supplier_id' => $request->supplier_id,
    'invoice_no' => $invoiceNo,
    'purchase_date' => $request->purchase_date,
    'total_amount' => $total,
    'status' => 'pending',
    'created_by' => Auth::id(),
]);

// In index() method, replace with:
public function index()
{
    $user = Auth::user();
    $purchases = Purchase::when(!$user->isAdmin(), function($q) use ($user) {
        $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
    })->with('supplier', 'branch', 'user')
      ->latest()
      ->paginate(20);
    return view('purchase.index', compact('purchases'));
}
```

### Update ExpenseController

```php
// In store() method, add:
$expense = Expense::create([
    'branch_id' => Auth::user()->default_branch_id,
    'title' => $request->title,
    'expense_type' => $request->expense_type,
    'amount' => $request->amount,
    'date' => $request->date,
    'created_by' => Auth::id(),
]);

// In index() method, replace with:
public function index()
{
    $user = Auth::user();
    $expenses = Expense::when(!$user->isAdmin(), function($q) use ($user) {
        $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
    })->with('user', 'branch')
      ->latest()
      ->paginate(20);
    return view('expenses.index', compact('expenses'));
}
```

### Update InventoryController

```php
public function index()
{
    $user = Auth::user();
    $inventory = Inventory::when(!$user->isAdmin(), function($q) use ($user) {
        $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
    })->with('book', 'branch')
      ->paginate(50);
    return view('inventory.index', compact('inventory'));
}
```

## Create Missing Report Views

Create these files with similar structure to `reports/sales.blade.php`:

1. `resources/views/reports/inventory.blade.php`
2. `resources/views/reports/stock.blade.php`
3. `resources/views/reports/purchase.blade.php`
4. `resources/views/reports/expense.blade.php`
5. `resources/views/reports/branch-comparison.blade.php`

And branch show view:
- `resources/views/branches/show.blade.php`

## Verify Installation

### Check Database
```bash
php artisan tinker
> App\Models\Branch::count()   // Should show 2
> App\Models\Role::count()      // Should show 4
> App\Models\User::count()      // Should show 3
> App\Models\UserBranchRole::count()  // Should show 3
```

### Check Models
```bash
php artisan tinker
> $user = App\Models\User::find(1)
> $user->getAccessibleBranches()  // Should show branches
> $user->isAdmin()                 // Should show true
```

### Check Routes
```bash
php artisan route:list | grep "branches\|reports"
```

## Troubleshooting

### Migrations don't run
```bash
php artisan migrate:refresh
# Or check for syntax errors:
php artisan migrate:status
```

### Users can't log in
- Check: `users.default_branch_id` is set
- Check: `user_branch_roles` has entries
- Check: `user_branch_roles.is_active` is true

### Data not visible
- Verify `branch_id` is set on records
- Verify user has access to that branch
- Check authorization methods

### Reports return empty
- Check filters are correct
- Verify user can access selected branch
- Ensure transactions have `branch_id`

## Key Features

✅ **Multi-Branch Support** - Independent data per branch
✅ **Role-Based Access** - Admin, Branch Admin, User, Cashier
✅ **Automatic Filtering** - Query scopes for branch isolation
✅ **Reports** - Sales, Inventory, Stock, Purchase, Expense, Comparison
✅ **User Authorization** - Helper methods for permission checks
✅ **Branch Isolation** - Users see only their branch data

## Next Steps

1. ✅ Run migrations
2. ✅ Create test data
3. ⏳ Update controllers (use examples above)
4. ⏳ Create missing views
5. ⏳ Test with different users
6. ⏳ Customize as needed

## Command Reference

```bash
# Run migrations
php artisan migrate

# Check migration status
php artisan migrate:status

# Rollback last batch
php artisan migrate:rollback

# Fresh install (WARNING: deletes data)
php artisan migrate:refresh

# Clear cache
php artisan cache:clear

# Tinker console
php artisan tinker

# List routes
php artisan route:list
```

## File Reference

### Core Documentation
- `MULTIBRANCH_SETUP.md` - Complete technical documentation
- `IMPLEMENTATION_GUIDE.md` - Detailed step-by-step guide
- `MULTIBRANCH_SUMMARY.md` - Overview of all changes
- `QUICK_START.md` - This file

### Models
- `app/Models/Branch.php`
- `app/Models/Role.php`
- `app/Models/UserBranchRole.php`
- Plus updates to User, Sale, Purchase, Expense, Book, Inventory, Customer

### Controllers
- `app/Http/Controllers/BranchController.php`
- `app/Http/Controllers/ReportController.php`

### Views
- `resources/views/branches/*`
- `resources/views/reports/*`

## Support

For issues or questions:
1. Check troubleshooting section above
2. Review IMPLEMENTATION_GUIDE.md
3. Check model relationships in code
4. Verify database structure with `DESCRIBE` commands

---

**Ready?** Start with Step 1 above!

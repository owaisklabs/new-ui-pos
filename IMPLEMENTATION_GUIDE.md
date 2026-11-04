# Multi-Branch POS System - Implementation Guide

## Quick Start

### Step 1: Database Cleanup
```bash
# Backup your database first!
# Then truncate data (since this is a new system)

php artisan tinker
> DB::table('sales')->truncate();
> DB::table('purchases')->truncate();
> DB::table('sale_returns')->truncate();
> DB::table('purchase_returns')->truncate();
> DB::table('expenses')->truncate();
> DB::table('inventories')->truncate();
> DB::table('books')->truncate();
> DB::table('customers')->truncate();
> DB::table('users')->truncate();
> exit;
```

### Step 2: Run Migrations
```bash
php artisan migrate
```

This will:
- Create `branches` table
- Create `roles` table with default roles (admin, branch_admin, branch_user, cashier)
- Create `user_branch_roles` junction table
- Add `branch_id` columns to sales, purchases, books, inventories, customers
- Create expenses table with branch support
- Add `default_branch_id` to users table

### Step 3: Create Initial Branches
```bash
php artisan tinker

# Create branches
$b1 = App\Models\Branch::create([
    'name' => 'Main Branch',
    'code' => 'BR001',
    'address' => '123 Main Street',
    'phone' => '123-456-7890',
    'email' => 'main@shop.com',
    'is_active' => true
]);

$b2 = App\Models\Branch::create([
    'name' => 'Downtown Store',
    'code' => 'BR002',
    'address' => '456 Downtown Ave',
    'phone' => '098-765-4321',
    'email' => 'downtown@shop.com',
    'is_active' => true
]);

# Create a test admin user
$admin = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@shop.com',
    'password' => Hash::make('password123'),
    'default_branch_id' => $b1->id
]);

# Assign admin role
$adminRole = App\Models\Role::where('name', 'admin')->first();
$admin->userBranchRoles()->create([
    'branch_id' => null,
    'role_id' => $adminRole->id,
    'is_active' => true
]);

# Create a branch user for Main Branch
$user1 = App\Models\User::create([
    'name' => 'John Cashier',
    'email' => 'john@shop.com',
    'password' => Hash::make('password123'),
    'default_branch_id' => $b1->id
]);

# Assign as branch user
$branchUserRole = App\Models\Role::where('name', 'branch_user')->first();
$user1->userBranchRoles()->create([
    'branch_id' => $b1->id,
    'role_id' => $branchUserRole->id,
    'is_active' => true
]);

# Create a branch user for Downtown Branch
$user2 = App\Models\User::create([
    'name' => 'Jane Cashier',
    'email' => 'jane@shop.com',
    'password' => Hash::make('password123'),
    'default_branch_id' => $b2->id
]);

$user2->userBranchRoles()->create([
    'branch_id' => $b2->id,
    'role_id' => $branchUserRole->id,
    'is_active' => true
]);

exit;
```

### Step 4: Update Existing Controllers

For each controller that creates sales, purchases, or expenses, add branch_id:

**SaleController@store:**
```php
$sale = Sale::create([
    'branch_id' => Auth::user()->default_branch_id,
    'customer_id' => $request->customer_id,
    'invoice_no' => $invoiceNo,
    'sale_date' => now(),
    'total_amount' => $total,
    'paid_amount' => $paid,
    'status' => $status,
    'created_by' => Auth::id(),
]);
```

**PurchaseController@store:**
```php
$purchase = Purchase::create([
    'branch_id' => Auth::user()->default_branch_id,
    'supplier_id' => $request->supplier_id,
    'invoice_no' => $invoiceNo,
    'purchase_date' => $request->purchase_date,
    'total_amount' => $total,
    'status' => 'pending',
    'created_by' => Auth::id(),
]);
```

**ExpenseController@store:**
```php
$expense = Expense::create([
    'branch_id' => Auth::user()->default_branch_id,
    'title' => $request->title,
    'expense_type' => $request->expense_type,
    'amount' => $request->amount,
    'date' => $request->date,
    'created_by' => Auth::id(),
]);
```

### Step 5: Update Index Methods to Filter by Branch

**SaleController@index:**
```php
public function index()
{
    $user = Auth::user();
    
    $sales = Sale::when(!$user->isAdmin(), function($q) use ($user) {
        return $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
    })->with('customer', 'branch', 'user')
     ->latest()
     ->paginate(20);
     
    return view('sales.index', compact('sales'));
}
```

**PurchaseController@index:**
```php
public function index()
{
    $user = Auth::user();
    
    $purchases = Purchase::when(!$user->isAdmin(), function($q) use ($user) {
        return $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
    })->with('supplier', 'branch', 'user')
     ->latest()
     ->paginate(20);
     
    return view('purchase.index', compact('purchases'));
}
```

### Step 6: Add Branch Selector to Dashboard

Update your dashboard layout to show:
1. Current branch name
2. Branch switcher if user has multiple branches

**In your header/navbar:**
```blade
@if(Auth::check() && Auth::user()->branches()->count() > 1)
    <div class="branch-selector">
        <select id="branch-select" onchange="changeBranch(this.value)">
            @foreach(Auth::user()->getAccessibleBranches() as $branch)
                <option value="{{ $branch->id }}" @selected($branch->id == Auth::user()->default_branch_id)>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
    </div>
@elseif(Auth::check())
    <span class="branch-name">
        {{ Auth::user()->defaultBranch?->name ?? 'No Branch' }}
    </span>
@endif
```

### Step 7: Create Branch Selector Controller Method

```php
// In a new method or in UserController
public function changeBranch(Request $request)
{
    $user = Auth::user();
    $branchId = $request->get('branch_id');
    
    // Verify user can access this branch
    if (!$user->canAccessBranch($branchId)) {
        return redirect()->back()->with('error', 'Unauthorized branch access');
    }
    
    $user->update(['default_branch_id' => $branchId]);
    
    return redirect()->back();
}
```

### Step 8: Add Navigation Menu Items

Add to your menu/navigation:
```blade
<li class="menu-item">
    <a href="{{ route('branches.index') }}" class="menu-link">
        <i class="fas fa-sitemap"></i>
        <span>Branches</span>
    </a>
</li>

<li class="menu-item">
    <a href="{{ route('reports.index') }}" class="menu-link">
        <i class="fas fa-chart-line"></i>
        <span>Reports</span>
    </a>
</li>
```

### Step 9: Test the Implementation

1. **Login as Admin:**
   - Username: admin@shop.com
   - Password: password123
   - Verify you can see all branches
   - Verify you can access all reports

2. **Login as Branch User:**
   - Username: john@shop.com
   - Password: password123
   - Verify you only see Main Branch data
   - Verify you cannot access other branches

3. **Create Test Transaction:**
   - Create a sale
   - Verify branch_id is automatically set
   - Check that other branches cannot see it

## Model Update Examples

### InventoryController Update

```php
public function index()
{
    $user = Auth::user();
    
    $inventory = Inventory::when(!$user->isAdmin(), function($q) use ($user) {
        return $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
    })->with('book', 'branch')
     ->paginate(50);
     
    return view('inventory.index', compact('inventory'));
}
```

### BookController Update

```php
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|unique:books',
        'sku' => 'required|unique:books',
        // ... other validations
    ]);
    
    // If creating for a specific branch, set it
    // Otherwise null = system-wide book
    $book = Book::create(array_merge($request->all(), [
        'branch_id' => $request->has('branch_id') ? $request->branch_id : null,
    ]));
    
    return redirect()->route('book.index');
}
```

## Important Notes

### Branch Isolation
- **Sales/Purchases/Expenses**: Automatically filtered by branch via scope
- **Books**: Can be system-wide (branch_id = null) or branch-specific
- **Customers**: Can be system-wide or branch-specific
- **Inventory**: Always branch-specific (one inventory per book per branch)

### User Authorization
Always check before returning data:
```php
if (!Auth::user()->canAccessBranch($branchId)) {
    abort(403, 'Unauthorized access');
}
```

### Create Operations
Always include branch_id:
```php
$model->create([
    'branch_id' => Auth::user()->default_branch_id,
    // ... other fields
]);
```

## Troubleshooting

### Users Cannot See Data
- Check `users.default_branch_id` is set
- Check `user_branch_roles` has entries for user and branch
- Verify `user_branch_roles.is_active = true`

### Migrations Fail
```bash
# Check current status
php artisan migrate:status

# Rollback and retry
php artisan migrate:rollback
php artisan migrate
```

### Reports Show No Data
- Verify transactions have `branch_id` set
- Check user can access the selected branch
- Use `->byBranch($branchId)` scope in queries

## Next Steps

1. Update all controllers to include branch filtering
2. Add comprehensive authorization middleware
3. Create user management interface
4. Add branch-specific settings
5. Implement branch transfer functionality (for inventory)
6. Add audit logging per branch
7. Create more detailed reports with charts

## Files Modified/Created

**Created:**
- Models: Branch, Role, UserBranchRole
- Controllers: BranchController, ReportController
- Middleware: CheckBranchAccess
- Migrations: 10 migration files
- Views: branches/*, reports/*

**Modified:**
- Models: User, Sale, Purchase, Expense, Book, Inventory, Customer
- Routes: web.php
- Database: Added foreign keys and columns to existing tables

## Testing Checklist

- [ ] Migrations run successfully
- [ ] Can create branches
- [ ] Can assign users to branches
- [ ] Can create sales with branch_id
- [ ] Can create purchases with branch_id
- [ ] Can view branch-specific data
- [ ] Users see only their branch data
- [ ] Reports filter by branch correctly
- [ ] Admin can see all branches
- [ ] Branch users see only their branch

## Support Commands

```bash
# Check database structure
php artisan tinker
> \DB::select('DESCRIBE branches')
> \DB::select('DESCRIBE user_branch_roles')

# Check user branches
> $user = App\Models\User::find(1);
> $user->getAccessibleBranches()

# Check all roles
> App\Models\Role::all()

# Clear cache if needed
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

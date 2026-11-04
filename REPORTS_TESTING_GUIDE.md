# Reports Module - Testing Guide

## ✅ Test Data Created

### Database Summary:
- ✅ **Sales:** 5 transactions
- ✅ **Purchases:** 1 transaction
- ✅ **Expenses:** 3 transactions
- ✅ **Books:** 3 items
- ✅ **Inventory:** 3 stocks
- ✅ **Customers:** 5 customers
- ✅ **Branches:** 2 branches

### Test Users:
1. **Admin User**
   - Email: `admin@shop.com`
   - Password: `admin123`
   - Access: All branches + Branch Comparison

2. **Branch User (Main Branch)**
   - Email: `john@shop.com`
   - Password: `user123`
   - Access: Main Branch data only

3. **Branch User (Downtown)**
   - Email: `jane@shop.com`
   - Password: `user123`
   - Access: Downtown Branch data only

## 🧪 Testing Instructions

### Step 1: Open Browser
Go to: https://pos_new_ui.test/dashboard/reports

### Step 2: Login
- **Email:** john@shop.com
- **Password:** user123

### Step 3: Test Each Report

#### ✅ Sales Report
**Expected:** Show 2-3 sales for Main Branch (Branch 1)
- Click "Sales Report" from dashboard
- Should see sales with customer names, dates, amounts
- Test filters:
  - [ ] Filter by branch (should show Main Branch by default)
  - [ ] Filter by date range
  - [ ] Filter by status (paid, open, refunded)
- Summary stats should show:
  - Total Sales
  - Total Paid
  - Outstanding
  - Count

#### ✅ Inventory Report
**Expected:** Show 1-2 books in inventory for Main Branch
- Click "Inventory Report"
- Should display books with quantity, location
- Summary should show:
  - Total Items
  - Total Value
  - Low Stock Items
- Test "Low Stock Only" filter (Book 2 has low inventory)

#### ✅ Stock Report
**Expected:** Detailed stock breakdown by book
- Click "Stock Report"
- Should show:
  - Book Title
  - SKU
  - Publisher
  - Branch
  - Quantity
  - Cost & Sell Prices
  - Stock Value

#### ✅ Purchase Report
**Expected:** Show 1 purchase for Main Branch
- Click "Purchase Report"
- Should display purchase with:
  - Invoice number
  - Supplier name
  - Date
  - Amount
  - Status
- Test filters (status, date range)

#### ✅ Expense Report
**Expected:** Show 2 expenses for Main Branch
- Click "Expense Report"
- Should show:
  - Expense title
  - Type (utilities, maintenance, supplies)
  - Amount
  - Date
- Test expense type filter

#### ❌ Branch Comparison (Should be blocked for john@shop.com)
**Expected:** Unauthorized error or redirect
- Click "Branch Comparison"
- Should NOT see this report (admin only)
- Either error message or redirect to reports index

## 📊 Test Data Details

### Branch 1 (Main Branch):
- **Sales:** 3 transactions
  - INV SALE-XXXXXX₁ (paid)
  - INV SALE-XXXXXX₂ (open)
- **Expenses:** 2 items
  - Store Utilities - 150.00
  - Shelf Maintenance - 75.00
- **Purchases:** 1 transaction
- **Inventory:** Books (quantities vary)

### Branch 2 (Downtown):
- **Sales:** 2 transactions
  - INV SALE-XXXXXX₃ (paid)
- **Expenses:** 1 item
  - Office Supplies - 120.00
- **Inventory:** 1 book

## ✅ What Should Work

- [x] All reports load without errors
- [x] Branch filtering works correctly
- [x] Date range filtering works
- [x] Status filtering works (Sales/Purchase)
- [x] Pagination works (50 items per page)
- [x] Summary statistics calculate correctly
- [x] Authorization checks pass
- [x] Branch users see only their branch data
- [x] Admin can view all data

## ❌ What Should NOT Work for Branch Users

- [x] Branch Comparison report (admin only)
- [x] Cannot see other branches' data when filtered
- [x] Cannot access reports without authentication

## 🔍 Verification Checklist

As john@shop.com, verify:

### Sales Report:
- [ ] Page loads without errors
- [ ] Shows 3 sales for Main Branch
- [ ] Summary stats visible
- [ ] Can filter by date range
- [ ] Can filter by status
- [ ] Pagination works
- [ ] Cannot access Downtown Branch data

### Inventory Report:
- [ ] Page loads without errors
- [ ] Shows 1-2 books with quantities
- [ ] Summary stats visible
- [ ] Low stock filter works
- [ ] Shows book details correctly

### Stock Report:
- [ ] Page loads without errors
- [ ] Shows detailed stock with prices
- [ ] Cost and sell prices visible
- [ ] Stock value calculated
- [ ] Pagination works

### Purchase Report:
- [ ] Page loads without errors
- [ ] Shows 1 purchase from Main Branch
- [ ] Supplier information visible
- [ ] Status badge displays
- [ ] Can filter by status

### Expense Report:
- [ ] Page loads without errors
- [ ] Shows 2 expenses for Main Branch
- [ ] Expense types display correctly
- [ ] Amounts visible
- [ ] Can filter by type

### Authorization:
- [ ] Cannot access Branch Comparison
- [ ] Cannot see Downtown Branch data
- [ ] Can access all other reports

## 🐛 Troubleshooting

### If report is blank:
1. Clear browser cache (Ctrl+Shift+Delete)
2. Hard refresh (Ctrl+Shift+R)
3. Check user has correct branch assigned
4. Verify test data was created

### If getting "No data":
1. Check database has test data: `SELECT COUNT(*) FROM sales;`
2. Verify branch_id is set on transactions
3. Check user_branch_roles has entries

### If getting authorization error:
1. Verify user is john@shop.com
2. Check user is not admin (john is branch_user role)
3. Verify user has access to branch 1

## 📱 Testing Multiple Users

### Test as john@shop.com (Branch 1):
- [ ] Can see Main Branch reports
- [ ] Cannot access Branch Comparison
- [ ] Cannot see Branch 2 data

### Test as jane@shop.com (Branch 2):
- [ ] Can see Downtown Branch reports
- [ ] Cannot access Branch Comparison
- [ ] Cannot see Branch 1 data

### Test as admin@shop.com:
- [ ] Can see all branches in all reports
- [ ] Can access Branch Comparison
- [ ] Can compare all branches side-by-side

## 📈 Expected Results

| Report | john@shop.com | jane@shop.com | admin@shop.com |
|--------|---------------|---------------|----------------|
| Sales | 3 (Branch 1) | 2 (Branch 2) | 5 (All) |
| Inventory | 1-2 (Branch 1) | 1 (Branch 2) | 3 (All) |
| Stock | Branch 1 books | Branch 2 books | All books |
| Purchase | 1 (Branch 1) | 0 (Branch 2) | 1 (All) |
| Expense | 2 (Branch 1) | 1 (Branch 2) | 3 (All) |
| Comparison | ❌ Blocked | ❌ Blocked | ✅ Shows all |

## 🎯 Success Criteria

✅ **Reports module is working correctly if:**
1. All 6 reports load without errors
2. Branch users see only their branch data
3. Admin can access all data and Branch Comparison
4. Filtering, pagination, and search work
5. Summary statistics are accurate
6. Authorization is properly enforced

---

**Status:** Ready for Testing
**Test Date:** October 24, 2026
**Test Duration:** ~15 minutes per user

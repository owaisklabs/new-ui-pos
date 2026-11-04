# Dual-Unit (Roll + Feet) User Manual

For products you **buy in one unit (Roll)** and **sell in that unit, a smaller unit (Feet), or both on one line**
(for example *2 Rolls + 7 Feet*). Products without these settings behave exactly as before.

---

## 0. One-time setup (developer)

```bash
php artisan migrate
```

This adds the unit columns to `books`, `purchase_items`, `sale_items` and `user_cart`.
Without it the sales screen will error.

---

## 1. How it works in one minute

| Idea | What it means |
|---|---|
| **Stock is kept in Feet** | The smallest unit (the *base unit*). 5 Rolls of 40 = **200 Feet** in stock. |
| **Prices: typed per Roll, stored per Foot** | On the product form you type Cost and Sell price **per Roll**. The system divides by Feet-per-Roll and stores the price per Foot. |
| **Purchases are entered in Rolls** | You type Rolls and the price **per Roll**. The system converts to Feet. |
| **Sales are entered in Rolls and/or Feet** | Two boxes per product. Both optional, either or both. |
| **Invoices show what was typed** | "2 Rolls + 7 Feet", not "87 Feet". |
| **Each record keeps its own factor** | Changing Feet-per-Roll later does not change old purchases or sales. |

---

## 2. Step 1 - Set up the product

Menu: **Book -> Create Book** (new) or **Book -> Books -> pencil icon** (existing).

Fill the normal fields, then the **Dual Unit** section at the bottom:

| Field | Example | Meaning |
|---|---|---|
| Conversion factor | `40` | How many Feet in 1 Roll |
| Purchase Unit | `Roll` | Unit you buy in |
| Base (Sale / Stock) Unit | `Feet` | Unit stock is counted in |

**Rule: fill all three, or leave all three empty.** If only some are filled the form shows an error and does not save.
If a product has no conversion factor, the system treats it as a normal product - no Roll/Feet boxes will appear anywhere.

### Prices: type them per Roll

As soon as you type a conversion factor, the two price boxes change to **Cost Price per Roll** and
**Sell Price per Roll**, and a blue line under each shows the per-Foot price that will be saved.

```
Per-Foot price = price per Roll / conversion factor
```

Example with 40 Feet per Roll, cost Rs. 2,500 and sell Rs. 3,000 per Roll:

| | You type (per Roll) | System saves (per Foot) |
|---|---|---|
| Cost Price | 2,500 | 62.50 |
| Sell Price | 3,000 | 75.00 |

Editing the product later shows the per-Roll prices again (per-Foot x conversion factor).
The Books list shows both, for example `75.00 / Feet (3,000.00 / Roll)`.

If the Roll price does not divide evenly (for example 3,000 / 35 = 85.714) the per-Foot price is rounded to 2 decimals,
so a full Roll can come out a few paisa different. Choose a Roll price that divides evenly if that matters.

---

## 3. Step 2 - Purchase (in Rolls)

Menu: **Purchase -> Create Purchase**.

1. Fill invoice number, date, supplier, payment type, status, expenses.
2. In *Purchase Items* choose the product.
3. **Qty = number of Rolls.** A blue hint appears under it, for example
   `Rolls | 1 Roll = 40 Feet | Stock added: 200 Feet`.
4. The cost box now reads **Cost per Roll** - enter the price of one Roll (2,500).
5. Create.

| You entered | Saved |
|---|---|
| 5 Rolls @ Rs. 2,500 | Purchase total **Rs. 12,500** |
| | Stock **+200 Feet** |
| | Purchase record keeps `5 Roll`, `Rs. 2,500 per Roll`, `factor 40` |

The purchase detail page shows **5 Rolls (200 Feet)** and **2,500.00 / Roll**.
Normal products on the same purchase work as before (Qty x Unit Cost).

---

## 4. Step 3 - Sell (Rolls, Feet, or both)

Menu: **Sales -> Create Sale**.

Click the product. For a dual-unit product the cart row shows two boxes:

```
 Rolls [ 2 ]   Feet [ 7 ]      = 87 Feet
```

* A product is added with **1 Foot** by default - change the boxes as needed.
* Type whole numbers. Leave a box at 0 if not needed.
* The line total updates immediately: total Feet x price per Foot, less discount.
* Normal products keep their single quantity box.

| Rolls | Feet | Total Feet | Rate | Amount |
|---|---|---|---|---|
| 2 | 0 | 80 | 75 | 6,000 |
| 0 | 7 | 7 | 75 | 525 |
| 2 | 7 | 87 | 75 | 6,525 |

### Example: customer wants 4 Rolls + 5 Feet

Product: 40 Feet per Roll, sell price Rs. 3,000 per Roll (saved as 75.00 per Foot).

1. Click the product in the product list - it is added with 1 Foot.
2. Set **Rolls = 4** and **Feet = 5**.
3. The row shows `= 165 Feet` (4 x 40 + 5) and the line total **Rs. 12,375**
   (165 x 75, which is 4 x 3,000 + 5 x 75).
4. Checkout. Stock goes down by 165 Feet and the invoice prints **4 Rolls + 5 Feet**.

The price column shows the per-Foot price with the per-Roll price underneath, for example
`75.00 / Feet` and `3000.00 / Roll`.

Checkout as usual. The server **recalculates** the Feet from the Roll and Feet numbers and **checks stock**:

* Enough stock -> sale saved, stock reduced by the total Feet.
* Not enough -> nothing is saved and you see
  `Insufficient stock for "Glossy". Available: 113 Feet, requested: 120.`
* Both boxes 0 -> `Enter Roll or Feet quantity for "Glossy".`

### What the invoice shows

| Product | Qty | Price | Total |
|---|---|---|---|
| Glossy Wood Sheet | **2 Rolls + 7 Feet** | 75.00 | 6,525.00 |

This applies to the thermal receipt, the A5 receipt and the sale detail page.
(On the A5 receipt the "Total Quantity" row only counts normal products, so Feet are never added into it.)

---

## 5. Stock and reports

* **Books list**, **Inventory** and the **Stock / Inventory reports** show stock as
  `200 Feet (5 Rolls)` or `313 Feet (7 Rolls + 33 Feet)`.
  The number in front is the exact base-unit stock.
* Stock value = Feet in stock x Cost Price per Foot.
* Profit = Feet sold x (Sell price per Foot - Cost price per Foot).
  Example: 87 x (75 - 62.50) = **Rs. 1,087.50**.
* The Sales list "Qty" column totals base units (Feet) for dual-unit products - it is an internal figure, not the invoice quantity.

---

## 6. Returns

* Sale and purchase returns are entered in **base units (Feet)** and add back / remove exactly that many Feet.
* To return "1 Roll" enter 40.
* Returned amount uses the price actually charged per Foot.

---

## 7. Complete worked example

1. **Product:** Glossy Wood Sheet - factor 40, Roll, Feet, cost 2,500 and sell 3,000 per Roll (saved as 62.50 / 75.00 per Foot).
2. **Purchase:** 5 Rolls @ 2,500 -> total 12,500, stock **200 Feet**.
3. **Sale A:** 2 Rolls -> 80 Feet, Rs. 6,000, stock **120**.
4. **Sale B:** 0 Rolls + 7 Feet -> Rs. 525, stock **113**.
5. **Sale C:** 2 Rolls + 7 Feet -> 87 Feet, Rs. 6,525 - but only 113 Feet left, so allowed, stock **26**.
6. **Sale D:** 1 Roll (40 Feet) -> refused, only 26 Feet left.

---

## 8. Troubleshooting

| Problem | Cause / fix |
|---|---|
| **Cart shows price 3000.00 and one Qty box, no Rolls/Feet** | The product still has no saved conversion factor, so it is a normal product at the Roll price. Edit the product, enter 40 / Roll / Feet, press Update, check the Books list shows `75.00 / Feet (3,000.00 / Roll)`, then reload Create Sale (Ctrl+F5). |
| **Sales screen shows one Qty box, no Rolls/Feet** | The product has no conversion factor saved. Edit the product and fill all three unit fields. Then press **Cancel** on the sales screen to clear any old cart. |
| **Purchase screen shows no "1 Roll = 40 Feet" hint** | Same cause - product not configured. Reload the purchase page after fixing the product. |
| **Product saved with only "Roll" and nothing else** | Older saves allowed this. Edit it and complete all three fields (this is now blocked). |
| **Form error "Enter how many Feet are in one Roll..."** | You filled some unit fields but not all. Fill all three or clear all three. |
| **Cost Price rejects 62.5** | Fixed - the price boxes accept 2 decimals (and you now type per-Roll prices anyway). |
| **Stock looks wrong after configuring an existing product** | Stock bought or sold *before* the product was configured was counted per Roll, not per Foot. There is no stock edit screen: correct `inventories.quantity` for that product in the database to the true number of Feet (or delete the test purchases/sales and purchase again). Old invoices stay as plain quantities. |
| **Edit page demands publisher, author and a new cover image** | Changed - these are now optional when editing, so you can just fix the unit fields. |

---

## 9. Limits to know

* Quantities are **whole numbers** (the database `quantity` columns are integers).
* Insufficient-stock blocking applies to **dual-unit products only**; normal products behave as before.
* Costing uses the product's **Cost Price per Foot** (the app has no FIFO / average costing). It is set from the product form, not from purchase prices.
* `sale_items.total` is an integer column in the existing schema, so a discounted line total is stored in whole rupees.

# Supplier Currency Auto-Switching Implementation

## Overview
Successfully implemented automatic currency handling based on supplier country:
- ✅ Supplier form auto-suggests currency from country (already working)
- ✅ Purchase form displays supplier's currency in a badge
- ✅ Product search prices shown in supplier's currency
- ✅ Amounts displayed on screen in supplier's currency
- ✅ Amounts stored in database in base currency (INR)
- ✅ Exchange rate tracking for historical purchases

## What Was Changed

### 1. Database Schema
**New Migration:** `2026_07_28_000001_add_currency_fields_to_purchases_table.php`
- Added `currency_id` (FK to currencies) on purchases table
- Added `exchange_rate` (decimal 15,4) on purchases table
- Stores the supplier's currency and exchange rate at time of purchase

### 2. Purchase Model (`app/Models/Purchase.php`)
- Added `currency_id` and `exchange_rate` to fillable fields
- Added `currency()` belongsTo relationship

### 3. Supplier Form (`resources/views/suppliers/create.blade.php` + `edit.blade.php`)
- Already had country → currency auto-suggest JS (working)
- Suppliers can have a `currency_id` assigned based on their country

### 4. Purchase Form (`resources/views/purchases/form.blade.php`)
**Key Changes:**
- Added hidden fields: `exchange_rate` and `currency_id` synced via JS
- Enhanced supplier `<option>` to include `data-currency-id`
- `loadSupplierCurrency()` now loads supplier's currency and applies exchange rate
- `applyNewCurrency()` re-prices existing product rows when supplier changes
- `searchProducts` AJAX now passes `currency_rate` parameter
- Product prices shown in supplier's currency symbol
- All totals displayed in supplier's currency on screen

**Exchange Rate Logic:**
- Convention: `exchange_rate` = how many BASE currency units = 1 supplier currency unit
  - Example: If supplier uses USD and 1 USD = 83.5 INR, store 83.5
  - To convert INR → USD: `usd = inr / 83.5`
  - To convert USD → INR: `inr = usd * 83.5`

### 5. Purchase Controller (`app/Http/Controllers/PurchaseController.php`)
**`searchProducts()` method:**
- Now accepts optional `currency_rate` parameter from client
- Converts product prices from base currency → supplier currency for display

**`store()` method:**
- Accepts `exchange_rate` and `currency_id` from form
- Converts all submitted amounts (prices, tax, discount, shipping) from supplier currency → base currency before saving
- Stores exchange rate on the purchase record

**`update()` method:**
- Same conversion logic as store
- Falls back to purchase's stored exchange rate if not provided in request

## How It Works (User Flow)

1. **Create/Edit Supplier:**
   - User enters country name (e.g., "United States")
   - JS auto-selects currency (USD) from the dropdown
   - Supplier is saved with `currency_id`

2. **Create Purchase Order:**
   - User selects a supplier
   - JS loads supplier's currency (e.g., USD, $, rate = 83.5)
   - Currency badge shows: "$ United States Dollar (USD)" + rate info
   - Product search dropdown shows prices in USD
   - User enters quantities, prices in USD
   - All displayed totals are in USD
   - On submit: form sends `exchange_rate=83.5` + `currency_id=X`
   - Controller converts all USD amounts → INR before saving to DB
   - Purchase stored with `grand_total` in INR, `exchange_rate=83.5`

3. **Edit Purchase Order:**
   - Form loads with stored exchange rate from purchase
   - Product prices shown in original supplier currency
   - If user changes supplier → currency auto-switches → existing rows re-priced
   - On submit: amounts converted back to base currency for storage

4. **View/Report:**
   - Stored amounts are in base currency (INR) → consistent reporting
   - Can display using purchase's stored `exchange_rate` to show original supplier currency

## Testing Checklist

### Scenario 1: Create Purchase with Foreign Supplier
1. Create a supplier with country = "United States" (should auto-select USD)
2. Create a new purchase order
3. Select that supplier → verify badge shows "$ United States Dollar (USD)"
4. Search for products → verify prices shown in $ (divided by ~83.5 if default is INR)
5. Add products, enter quantity
6. Verify grand total displays in $
7. Save purchase
8. Check database: `grand_total` should be in INR ($ amount * 83.5)

### Scenario 2: Edit Purchase & Change Supplier
1. Open an existing purchase (e.g., created with USD supplier)
2. Verify products show prices in USD
3. Change supplier to one with a different currency (e.g., EUR)
4. Verify all product prices re-calculate to EUR
5. Save → verify amounts stored correctly in base currency

### Scenario 3: Supplier Without Currency
1. Create a supplier without selecting currency
2. Create a purchase with that supplier
3. Verify system falls back to default currency (INR)
4. Everything works normally

## Files Modified

```
database/migrations/
  └─ 2026_07_28_000001_add_currency_fields_to_purchases_table.php (NEW)

app/
  ├─ Models/
  │  └─ Purchase.php
  └─ Http/
     └─ Controllers/
        └─ PurchaseController.php

resources/views/
  └─ purchases/
     └─ form.blade.php
```

## Notes

- All monetary amounts in the database are stored in the BASE currency (default INR)
- Exchange rates are stored at time of purchase for historical accuracy
- Display logic uses the stored exchange rate to show original currency
- Stock adjustments happen based on quantity only (currency doesn't affect stock)
- Payment status calculations work on base-currency amounts
- Reports can aggregate all purchases in base currency regardless of original supplier currency

## Future Enhancements

- [ ] Add multi-currency report filters
- [ ] Currency conversion history chart
- [ ] Bulk currency updates for suppliers
- [ ] Real-time exchange rate API integration (currently manual entry)


# Razorpay Payment Gateway Integration — Purchase Module

## ✅ Implementation Complete

Razorpay has been integrated into your IMS Purchase module in **two places**:

### 1. **Purchase Create Form** (purchases/create)
When creating a new purchase, users can select **"Razorpay (Online Payment)"** as payment method. Upon clicking **"Pay via Razorpay"**, the Razorpay checkout modal opens and processes the payment securely.

### 2. **Purchase Index — Payment Update Modal** (purchases/index → Payment button)
When updating payment for an existing purchase via the "Manage Payment" modal, users can select **"Razorpay (Online Payment)"** and click **"Pay via Razorpay"** to complete the payment.

---

## 🔧 Setup Instructions

### Step 1: Get Razorpay Credentials

1. Sign up at: https://dashboard.razorpay.com/signup
2. Complete business verification (for live mode)
3. Navigate to **Settings → API Keys** → **Generate Keys**
4. Copy both:
   - **Key ID** (starts with `rzp_test_` or `rzp_live_`)
   - **Key Secret**

### Step 2: Configure Credentials

Open `.env` file and update the Razorpay keys:

```env
RAZORPAY_KEY_ID=rzp_test_xxxxxxxxxxxxxxxx
RAZORPAY_KEY_SECRET=xxxxxxxxxxxxxxxxxxxxxxxx
```

**For Production:**
Replace `rzp_test_` keys with `rzp_live_` keys from your live Razorpay dashboard.

### Step 3: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🧪 Testing Instructions

### Test Mode (Using Test Keys)

Razorpay provides test credentials for sandbox testing:

#### Test Card Details:
- **Card Number:** 4111 1111 1111 1111
- **CVV:** Any 3 digits (e.g., 123)
- **Expiry:** Any future date (e.g., 12/25)
- **Name:** Any name

#### Test UPI:
- **UPI ID:** success@razorpay
- Status: Success
- (Use `failure@razorpay` to test failed payments)

#### Test Netbanking:
- Select any bank
- Click **Success** button on the test screen

---

## 📋 Feature Testing Checklist

### A. Purchase Create Form (`/purchases/create`)

- [ ] **1.** Go to Purchases → Add Purchase Order
- [ ] **2.** Fill in all required fields (supplier, date, status, products)
- [ ] **3.** Select Payment Method: **"Razorpay (Online Payment)"**
- [ ] **4.** Verify:
  - ✅ Info box appears: "Click Pay via Razorpay to open secure payment gateway"
  - ✅ Paid Amount field auto-fills with Grand Total (and becomes readonly)
  - ✅ **"Save Purchase Order"** button is hidden
  - ✅ **"Pay via Razorpay"** green button appears
- [ ] **5.** Click **"Pay via Razorpay"**
- [ ] **6.** Verify Razorpay checkout modal opens
- [ ] **7.** Complete payment using test card (4111 1111 1111 1111)
- [ ] **8.** Verify:
  - ✅ Modal closes automatically
  - ✅ Purchase is saved with status "Received" (or selected status)
  - ✅ Payment status shows as **"Paid"**
  - ✅ Payment method shows **"Razorpay"**
  - ✅ Reference No contains the Razorpay Payment ID (e.g., `pay_xxxxx`)
  - ✅ Stock is incremented (if status = Received)
  - ✅ Success message: "Payment of ₹xxx received via Razorpay"

### B. Payment Update Modal (`/purchases` → Payment Button)

- [ ] **1.** Go to Purchases list (`/purchases`)
- [ ] **2.** Click the **💳 Payment** button on any purchase with due amount
- [ ] **3.** "Manage Payment" modal opens
- [ ] **4.** Select Payment Method: **"Razorpay (Online Payment)"**
- [ ] **5.** Verify:
  - ✅ Info box appears below payment method dropdown
  - ✅ Paid Amount field auto-fills with Grand Total (becomes readonly)
  - ✅ **"Update Payment"** button is hidden
  - ✅ **"Pay via Razorpay"** green button appears
- [ ] **6.** Click **"Pay via Razorpay"**
- [ ] **7.** Razorpay checkout modal opens
- [ ] **8.** Complete payment using test credentials
- [ ] **9.** Verify:
  - ✅ Modal closes
  - ✅ Purchase payment is updated
  - ✅ Payment Status badge changes to **"Paid"**
  - ✅ Due Amount becomes ₹0.00
  - ✅ Payment Method shows **"Razorpay"**
  - ✅ Reference No updated with Razorpay Payment ID
  - ✅ Success message appears

### C. Error Scenarios

- [ ] **Payment Cancelled:** Click "X" on Razorpay modal → Shows "Payment cancelled" toast
- [ ] **Payment Failed:** Use `failure@razorpay` UPI → Shows error message
- [ ] **Invalid Credentials:** Wrong KEY_ID/SECRET in `.env` → Shows error toast

### D. Activity Logs

- [ ] **1.** Go to Activity Logs page
- [ ] **2.** Verify entries show:
  - ✅ "Purchase Created (Razorpay)" with Payment ID
  - ✅ "Purchase Payment Updated" with Razorpay details

---

## 🔒 Security Features

✅ **Signature Verification:** All payments are verified server-side using Razorpay's signature mechanism  
✅ **HTTPS Required:** Razorpay checkout only works on HTTPS in production  
✅ **CSRF Protection:** All AJAX requests include Laravel CSRF token  
✅ **Authorization:** Routes are protected with `Gate::authorize()` for permission checks

---

## 📁 Modified Files

### New Files:
- `app/Http/Controllers/RazorpayController.php`

### Updated Files:
- `composer.json` (added `razorpay/razorpay` dependency)
- `config/services.php` (added Razorpay config)
- `.env` & `.env.example` (added Razorpay keys)
- `routes/web.php` (added 2 Razorpay routes)
- `app/Http/Controllers/PurchaseController.php` (updated `updatePayment` method for signature verification)
- `resources/views/purchases/form.blade.php` (added Razorpay option + JS logic)
- `resources/views/purchases/index.blade.php` (added Razorpay to payment modal + JS)

---

## 🚀 Going Live

### Production Checklist:

1. **Switch to Live Keys:**
   - Get live keys from: https://dashboard.razorpay.com/app/keys
   - Update `.env` with `rzp_live_` keys
   - Run: `php artisan config:clear`

2. **Enable Webhook (Optional but Recommended):**
   - Go to: https://dashboard.razorpay.com/app/webhooks
   - Add webhook URL: `https://yourdomain.com/razorpay/webhook`
   - Select events: `payment.captured`, `payment.failed`
   - Save the webhook secret

3. **SSL Certificate:**
   - Ensure your site runs on HTTPS
   - Razorpay checkout **requires HTTPS** in production

4. **Test Real Payment:**
   - Use a small amount (₹1.00)
   - Complete payment with real card
   - Verify stock, payment status, and activity logs

5. **Compliance:**
   - Complete KYC verification on Razorpay dashboard
   - Ensure business details are accurate

---

## 🆘 Troubleshooting

### Issue: "Could not create Razorpay order"
**Fix:** Check if credentials in `.env` are correct. Run `php artisan config:clear`.

### Issue: Razorpay modal doesn't open
**Fix:** Check browser console for JavaScript errors. Ensure jQuery and Bootstrap are loaded.

### Issue: "Payment verification failed"
**Fix:** Check if KEY_SECRET matches the KEY_ID. Clear config cache.

### Issue: Purchase saved but payment not recorded
**Fix:** Check Activity Logs for error details. Verify webhook configuration.

---

## 📞 Support

- **Razorpay Docs:** https://razorpay.com/docs/payments/
- **Test Cards:** https://razorpay.com/docs/payments/payments/test-card-upi-details/
- **Support:** https://razorpay.com/support/

---

## 🎉 Summary

Razorpay integration is **complete and ready for testing**. Update the credentials in `.env`, test using the checklist above, and you're good to go live! 🚀

# PayMongo Webhook Setup Guide

## Problem
Naka-live na pero walang webhook na tumatawag from PayMongo. Payment status stays at `payment_processing` instead of completing.

## Root Cause
PayMongo webhooks need to be manually configured in the PayMongo Dashboard. Hindi automatic yan.

## Solution: Configure Webhook in PayMongo Dashboard

### Step 1: Access PayMongo Dashboard
1. Go to https://dashboard.paymongo.com/
2. Login with your account
3. **IMPORTANT:** Switch to **Live Mode** (top right corner)

### Step 2: Create Webhook
1. Click **Developers** > **Webhooks** (left sidebar)
2. Click **Create Webhook** button
3. Fill in the form:

**Webhook URL:**
```
https://yourdomain.com/paymongo/webhook
```
Replace `yourdomain.com` with your actual production domain.

**Events to Listen (Select ALL):**
- ✅ `checkout_session.payment.paid` ← MOST IMPORTANT
- ✅ `payment.paid`
- ✅ `payment.failed`
- ✅ `source.chargeable`
- ✅ `refund.succeeded`
- ✅ `refund.failed`

4. Click **Create Webhook**
5. Copy the **Webhook Secret** (you'll need this for verification - optional)

### Step 3: Verify Webhook is Accessible

Test if your webhook URL is publicly accessible:

```bash
# From your local machine or another server
curl -X POST https://yourdomain.com/paymongo/webhook \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'
```

Expected response:
```json
{"error": "Invalid payload"}
```

This means the endpoint is accessible (even though it rejects invalid data).

### Step 4: Test with Real Payment

1. Make a test payment in **Live Mode**
2. Complete the payment
3. Check your logs:

```bash
# SSH to your server
tail -f storage/logs/laravel.log | grep "PayMongo Webhook"
```

You should see:
```
[2026-03-10 XX:XX:XX] production.INFO: PayMongo Webhook Received
[2026-03-10 XX:XX:XX] production.INFO: Consultation payment processed via webhook
```

### Step 5: Check Webhook Delivery in PayMongo Dashboard

1. Go to **Developers** > **Webhooks**
2. Click on your webhook
3. Click **Logs** tab
4. You'll see all webhook delivery attempts with:
   - Status (Success/Failed)
   - Response code
   - Response body
   - Retry attempts

## Common Issues

### Issue 1: Webhook URL Returns 404
**Cause:** Route not found or wrong URL
**Solution:** 
- Verify route exists: `php artisan route:list | grep webhook`
- Check your domain is correct
- Make sure you're using HTTPS (not HTTP)

### Issue 2: Webhook Returns 419 (CSRF Token Mismatch)
**Cause:** CSRF protection blocking webhook
**Solution:** Already fixed in `bootstrap/app.php` line 27:
```php
$middleware->validateCsrfTokens(except: [
    'paymongo/webhook',
]);
```

### Issue 3: Webhook Returns 500 (Server Error)
**Cause:** Error in webhook handler code
**Solution:** Check `storage/logs/laravel.log` for error details

### Issue 4: Webhook Not Being Called At All
**Cause:** Webhook not configured in PayMongo Dashboard
**Solution:** Follow Step 2 above to create webhook

### Issue 5: SSL Certificate Error
**Cause:** Invalid or self-signed SSL certificate
**Solution:** 
- Use valid SSL certificate (Let's Encrypt is free)
- PayMongo requires HTTPS with valid certificate

## Verification Checklist

- [ ] Webhook created in PayMongo Dashboard (Live Mode)
- [ ] Webhook URL is correct (https://yourdomain.com/paymongo/webhook)
- [ ] All events are selected (especially `checkout_session.payment.paid`)
- [ ] Webhook URL is publicly accessible (test with curl)
- [ ] SSL certificate is valid
- [ ] Route exists in `routes/web.php`
- [ ] Route is excluded from CSRF in `bootstrap/app.php`
- [ ] Logs show webhook is being received

## Testing

After setup, test the complete flow:

1. Book a consultation as client
2. Accept as lawyer (if not auto-accept)
3. Pay via PayMongo
4. Complete payment
5. Check logs for webhook:
   ```bash
   tail -f storage/logs/laravel.log | grep -A 5 "PayMongo Webhook"
   ```
6. Verify consultation status changed to `scheduled`
7. Verify transaction has `payment_method` and `paymongo_payment_method_id`

## Expected Log Flow

```
[INFO] Creating PayMongo checkout session
[INFO] Checkout session created successfully
[INFO] Transaction created with payment_intent_id
[INFO] Payment success callback received
[INFO] Transaction status updated to processing
[INFO] Consultation status updated to payment_processing
[INFO] PayMongo Webhook Received ← WEBHOOK ARRIVES
[INFO] Retrieved checkout session from PayMongo API
[INFO] Extracted payment details from API
[INFO] Transaction updated with PayMongo IDs
[INFO] Consultation updated with status: scheduled
[INFO] Consultation payment processed via webhook
```

## Support

If webhook still not working after following all steps:
1. Check PayMongo Dashboard > Webhooks > Logs for delivery errors
2. Check `storage/logs/laravel.log` for errors
3. Contact PayMongo support with webhook logs
4. Verify your server's firewall allows incoming connections from PayMongo IPs

## PayMongo Webhook IPs (for firewall whitelist)
Check PayMongo documentation for current webhook IP addresses if you need to whitelist them.

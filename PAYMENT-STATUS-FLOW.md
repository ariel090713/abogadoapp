# Payment Status Flow - Consultation & Transaction

## Status Flow Summary

### Chat & Video Consultations

**When Payment Succeeds:**
- Transaction: `pending` → `processing` → `completed`
- Consultation: `payment_pending` → `payment_processing` → `scheduled`

**When Payment Fails:**
- Transaction: `pending` → `processing` → `failed`
- Consultation: `payment_pending` → `payment_processing` → `payment_failed`

### Document Review Consultations

**When Payment Succeeds:**
- Transaction: `pending` → `processing` → `completed`
- Consultation: `payment_pending` → `payment_processing` → `in_progress`
- Additional: Sets `started_at` and calculates `review_completion_deadline`

**When Payment Fails:**
- Transaction: `pending` → `processing` → `failed`
- Consultation: `payment_pending` → `payment_processing` → `payment_failed`

## Detailed Flow

### Step 1: User Books Consultation
```php
// BookConsultation.php - submitRequest()
Consultation::create([
    'status' => $lawyer->auto_accept_bookings ? 'payment_pending' : 'pending',
    // ... other fields
]);

Transaction::create([
    'status' => 'pending',
    'payment_method' => null,
    'paymongo_payment_intent_id' => $checkoutSessionId,
]);
```

**Result:**
- Consultation: `payment_pending` (if auto-accept) or `pending` (if manual)
- Transaction: `pending`

### Step 2: User Redirected from PayMongo
```php
// PaymentController.php - success()
$transaction->update(['status' => 'processing']);
$consultation->update(['status' => 'payment_processing']);
```

**Result:**
- Consultation: `payment_processing`
- Transaction: `processing`
- User sees: "Payment is being processed..."

### Step 3A: Webhook Confirms Payment (SUCCESS)
```php
// PaymentController.php - handleConsultationPayment() or handlePaymentPaid()
DB::transaction(function () {
    $transaction->update([
        'status' => 'completed',
        'paymongo_payment_id' => $paymentId,
        'paymongo_payment_method_id' => $paymentMethodId,
        'payment_method' => $paymentMethod,
        'processed_at' => now(),
    ]);
    
    // Chat/Video: scheduled
    // Document Review: in_progress
    $status = $consultation->consultation_type === 'document_review' 
        ? 'in_progress' 
        : 'scheduled';
    
    $consultation->update(['status' => $status]);
    
    // For document review only
    if ($consultation->consultation_type === 'document_review') {
        $consultation->update(['started_at' => now()]);
        // Calculate review_completion_deadline
    }
});
```

**Result for Chat/Video:**
- Consultation: `scheduled`
- Transaction: `completed`
- Notifications sent to client and lawyer

**Result for Document Review:**
- Consultation: `in_progress`
- Transaction: `completed`
- Sets `started_at` timestamp
- Calculates `review_completion_deadline`
- Notifications sent to client and lawyer

### Step 3B: Webhook Reports Payment Failed (FAILURE)
```php
// PaymentController.php - handlePaymentFailed()
DB::transaction(function () {
    $transaction->update(['status' => 'failed']);
    $consultation->update(['status' => 'payment_failed']);
});
```

**Result:**
- Consultation: `payment_failed`
- Transaction: `failed`
- Notification sent to client with error message

## Status Definitions

### Transaction Status
- `pending` - Initial state, waiting for payment
- `processing` - User returned from PayMongo, waiting for webhook
- `completed` - Payment confirmed by webhook
- `failed` - Payment failed
- `refunded` - Payment refunded

### Consultation Status (Payment-Related)
- `payment_pending` - Waiting for payment
- `payment_processing` - Payment being processed (user returned, waiting for webhook)
- `payment_failed` - Payment failed
- `scheduled` - Paid and scheduled (chat/video)
- `in_progress` - Paid and started (document review)

## Key Points

1. **Transaction is Source of Truth for Payment Status**
   - Consultation status is derived from transaction status
   - Always update transaction first, then consultation

2. **Consultation Type Determines Final Status**
   - Chat/Video → `scheduled` (needs scheduled_at time)
   - Document Review → `in_progress` (starts immediately)

3. **Webhook is Authoritative**
   - User redirect only sets `processing` status
   - Webhook confirms and sets final status (`completed` or `failed`)

4. **Database Transactions for Atomicity**
   - Always use `DB::transaction()` when updating both tables
   - Ensures data consistency

5. **Notifications Outside DB Transaction**
   - Send notifications after DB transaction commits
   - Prevents notification failures from rolling back payment updates

## Code Locations

- **Booking Creation**: `app/Livewire/BookConsultation.php::submitRequest()`
- **User Return Handler**: `app/Http/Controllers/PaymentController.php::success()`
- **Webhook Success**: `app/Http/Controllers/PaymentController.php::handleConsultationPayment()`
- **Webhook Success (Alt)**: `app/Http/Controllers/PaymentController.php::handlePaymentPaid()`
- **Webhook Failure**: `app/Http/Controllers/PaymentController.php::handlePaymentFailed()`

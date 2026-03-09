# Database Schema Audit - Transactions & Consultations

## Transactions Table

### Columns:
- `id` - Primary key
- `consultation_id` - Foreign key to consultations
- `user_id` - Foreign key to users (payer)
- `lawyer_id` - Foreign key to users (lawyer) - **ADDED LATER**
- `document_request_id` - Foreign key - **ADDED LATER**
- `type` - ENUM: 'consultation_payment', 'refund', 'payout', 'document_drafting' - **document_drafting ADDED LATER**
- `amount` - Decimal(10,2)
- `platform_fee` - Decimal(10,2)
- `lawyer_payout` - Decimal(10,2)
- `status` - ENUM: 'pending', 'processing', 'completed', 'held', 'captured', 'refunded', 'failed'
- `payment_method` - String (nullable): 'card', 'gcash', 'grabpay', 'paymaya'
- `paymongo_payment_id` - String (nullable)
- `paymongo_payment_intent_id` - String (nullable)
- `paymongo_payment_method_id` - String (nullable) - **ADDED LATER**
- `payment_details` - Text (nullable) - JSON
- `failure_reason` - Text (nullable)
- `processed_at` - DateTime (nullable)
- `refund_id` - Foreign key (nullable) - **ADDED LATER**
- `timestamps`

### Status Values:
- `pending` - Initial state when transaction is created
- `processing` - User returned from PayMongo, waiting for webhook
- `completed` - Payment confirmed by webhook
- `held` - Payment held (not used currently)
- `captured` - Payment captured (not used currently)
- `refunded` - Payment refunded
- `failed` - Payment failed

## Consultations Table

### Columns:
- `id` - Primary key
- `client_id` - Foreign key to users
- `lawyer_id` - Foreign key to users
- `parent_consultation_id` - Foreign key (nullable) - **ADDED LATER**
- `consultation_type` - String: 'chat', 'video', 'document_review'
- `duration` - Integer (nullable) - in minutes
- `rate` - Decimal(10,2)
- `platform_fee` - Decimal(10,2)
- `total_amount` - Decimal(10,2)
- `status` - ENUM (see below)
- `scheduled_at` - DateTime (nullable)
- `started_at` - DateTime (nullable)
- `ended_at` - DateTime (nullable)
- `client_notes` - Text (nullable)
- `lawyer_notes` - Text (nullable)
- `decline_reason` - Text (nullable)
- `cancel_reason` - Text (nullable)
- `video_room_sid` - String (nullable) - Twilio room SID
- `recording_enabled` - Boolean
- `title` - String - **ADDED LATER**
- `document_path` - String (nullable) - **ADDED LATER**
- `quoted_price` - Decimal(10,2) (nullable) - **ADDED LATER**
- `estimated_turnaround_days` - Integer (nullable) - **ADDED LATER**
- `accepted_at` - DateTime (nullable) - **ADDED LATER**
- `payment_deadline` - DateTime (nullable) - **ADDED LATER**
- `payment_deadline_calculated` - DateTime (nullable) - **ADDED LATER**
- `lawyer_response_deadline` - DateTime (nullable) - **ADDED LATER**
- `review_completion_deadline` - DateTime (nullable) - **ADDED LATER**
- `case_status` - ENUM (nullable) - **ADDED LATER**
- `reschedule_count` - Integer - **ADDED LATER**
- `reschedule_requested_by` - Foreign key (nullable) - **ADDED LATER**
- `reschedule_requested_at` - DateTime (nullable) - **ADDED LATER**
- `reschedule_new_date` - DateTime (nullable) - **ADDED LATER**
- `reschedule_reason` - Text (nullable) - **ADDED LATER**
- `reschedule_status` - ENUM (nullable) - **ADDED LATER**
- `timestamps`

### Status Values (Current):
- `pending` - Waiting for lawyer approval
- `awaiting_quote_approval` - Lawyer sent quote, waiting for client approval
- `accepted` - Lawyer accepted, waiting for payment
- `declined` - Lawyer declined
- `scheduled` - Paid and scheduled
- `in_progress` - Currently ongoing
- `completed` - Finished
- `cancelled` - Cancelled by client/lawyer
- `ended` - Session ended (for chat/video)
- `payment_pending` - Waiting for payment
- `payment_processing` - Payment being processed (user returned from PayMongo)
- `payment_failed` - Payment failed
- `expired` - Expired (deadlines passed)
- `pending_client_acceptance` - Waiting for client to accept lawyer's terms

## CRITICAL ISSUES FOUND

### ❌ Issue 1: Removed Columns Still Referenced
The following columns were REMOVED from consultations table but may still be referenced in code:
- `payment_status` - REMOVED (now accessed via transaction relationship)
- `payment_intent_id` - REMOVED (now accessed via transaction relationship)

### ❌ Issue 2: Wrong Column Names in PaymentController
Found instances using wrong column names:
- ✅ FIXED: Line 544 - `payment_intent_id` → `paymongo_payment_intent_id`
- ✅ FIXED: Line 625 - `payment_intent_id` → `paymongo_payment_intent_id`

### ⚠️ Issue 3: Consultation Status Not in Transactions Status
The `handlePaymentPaid()` method updates transaction status to 'completed', but this is correct.
The consultation status is updated separately based on consultation_type.

## Correct Usage Patterns

### ✅ Accessing Payment Status:
```php
// CORRECT - Via transaction relationship
$consultation->transaction->status; // 'pending', 'processing', 'completed', etc.

// CORRECT - Via accessor (if implemented)
$consultation->payment_status; // Returns transaction->status

// WRONG - Direct column (doesn't exist anymore)
$consultation->payment_status; // ERROR if not using accessor
```

### ✅ Accessing Payment Intent ID:
```php
// CORRECT - Via transaction relationship
$consultation->transaction->paymongo_payment_intent_id;

// CORRECT - Via accessor (if implemented)
$consultation->payment_intent_id; // Returns transaction->paymongo_payment_intent_id

// WRONG - Direct column (doesn't exist anymore)
$consultation->payment_intent_id; // ERROR if not using accessor
```

### ✅ Webhook Flow:
1. User completes payment on PayMongo
2. User redirected to success URL → Sets consultation status to 'payment_processing' and transaction status to 'processing'
3. PayMongo sends webhook → Updates transaction status to 'completed' and consultation status to 'scheduled' (or 'in_progress' for document review)

## Recommendations

1. ✅ **DONE**: Fixed wrong column names in PaymentController
2. ⚠️ **TODO**: Verify all code uses `paymongo_payment_intent_id` not `payment_intent_id`
3. ⚠️ **TODO**: Ensure Consultation model has accessors for `payment_status` and `payment_intent_id`
4. ✅ **DONE**: Added 'payment_processing' status to consultations
5. ✅ **DONE**: Added 'processing' status to transactions
6. ✅ **DONE**: Updated AvailabilityService to check all active statuses including 'payment_processing'

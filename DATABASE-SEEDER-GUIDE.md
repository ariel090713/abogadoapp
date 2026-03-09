# Database Seeder Guide

## Quick Start

Run this command to reset database and seed all data:

```bash
php artisan migrate:fresh --seed
```

## What Gets Seeded

### Core Data (Always runs)
1. **AdminSeeder** - Admin user (admin@abogadomo.com / password)
2. **SpecializationSeeder** - Legal specializations (Family Law, Criminal Law, etc.)
3. **AISettingsSeeder** - AI configuration + knowledge base
4. **ContentCategorySeeder** - Content categories (News, Blogs, Legal Guides, Events)
5. **LawyerSeeder** - Sample lawyers with profiles
6. **ConsultationSeeder** - Sample consultations
7. **DocumentTemplateSeeder** - Legal document templates
8. **ContentSeeder** - Blog posts, news articles, legal guides

### Demo/Test Data (Included by default)
9. **AdminDashboardDataSeeder** - Creates:
   - 50+ clients
   - 20+ lawyers
   - 200+ consultations (over 6 months)
   - Transactions for completed consultations
   - 15 refund requests
   - 100 newsletter subscribers
   - Reviews
   - 5 pending lawyer verifications

10. **OngoingConsultationsSeeder** - Creates:
    - 1 ongoing chat consultation (2 hours, 50+ messages)
    - 1 ongoing video consultation (15 minutes, 10 messages)
    - 5 completed consultations with transactions

## Database Tables Created

After seeding, you'll have data in:
- users
- lawyer_profiles
- specializations
- consultations
- transactions
- messages
- reviews
- refunds
- newsletter_subscribers
- content (blogs, news, legal guides, events)
- content_categories
- document_templates
- ai_settings
- ai_knowledge_base

## Test Accounts

### Admin
- Email: admin@abogadomo.com
- Password: password

### Lawyers (from LawyerSeeder)
- Email: lawyer1@example.com to lawyer10@example.com
- Password: password

### Clients (from AdminDashboardDataSeeder)
- Email: client1@example.com to client50@example.com
- Password: password

## Optional Seeders (Run manually)

If you need specific test scenarios, run these individually:

```bash
# Active chat consultation
php artisan db:seed --class=ActiveChatSeeder

# Active video consultation
php artisan db:seed --class=ActiveVideoConsultationSeeder

# Payment test data
php artisan db:seed --class=PaymentTestSeeder

# Payout test data
php artisan db:seed --class=PayoutTestSeeder

# Fresh pending consultations
php artisan db:seed --class=FreshPendingSeeder

# Case management test data
php artisan db:seed --class=CaseManagementTestSeeder

# Document requests
php artisan db:seed --class=LawyerInitiatedServiceSeeder
```

## For Production

If deploying to production, comment out the demo seeders in `DatabaseSeeder.php`:

```php
// Demo/Test Data (Optional - comment out for production)
// $this->call([
//     AdminDashboardDataSeeder::class,
//     OngoingConsultationsSeeder::class,
// ]);
```

Then run:
```bash
php artisan migrate:fresh --seed
```

This will only seed core data without test/demo data.

## Troubleshooting

**Error: "Class not found"**
- Run: `composer dump-autoload`

**Error: "Table not found"**
- Make sure migrations ran: `php artisan migrate`

**Slow seeding**
- Normal! AdminDashboardDataSeeder creates 200+ consultations
- Takes 1-2 minutes to complete

**Want to reset everything?**
```bash
php artisan migrate:fresh --seed
```
This drops all tables, recreates them, and runs all seeders.

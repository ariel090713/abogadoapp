# AI Settings Setup Instructions

## Database Setup

Run these commands to create the AI tables and seed the data:

```bash
# Run the migration to create both tables
php artisan migrate

# Run the seeder to populate default AI settings and knowledge base
php artisan db:seed --class=AISettingsSeeder
```

## What Gets Created

### 1. `ai_settings` table
Stores AI configuration:
- `ai_name` - AI assistant name (default: "Legal Assistant")
- `ai_personality` - AI personality description
- `ai_rules` - Behavior rules for the AI
- `ai_greeting` - Initial greeting message
- `ai_enabled` - Enable/disable AI assistant

### 2. `ai_knowledge_base` table
Stores RAG (Retrieval-Augmented Generation) documents:
- About AbogadoMo App
- Available Consultation Types
- How to Choose a Lawyer
- Common Legal Concerns in the Philippines

## Admin Access

After seeding, you can manage AI settings at:
- URL: `/admin/settings`
- Two tabs:
  1. **Personality & Rules** - Configure AI behavior
  2. **Knowledge Base** - Add/edit RAG documents (supports PDF, DOC, DOCX, TXT uploads)

## Testing the AI Feature

1. Go to homepage
2. Click "Describe Your Legal Concern" button
3. You'll see a modal with two options:
   - "Ask AI to Help Me" - Opens AI chatbot
   - "Let Me Browse Lawyers" - Closes modal
4. If you choose AI, describe your legal concern
5. AI will ask questions and recommend lawyers based on specializations
6. Click "View Filtered Lawyers" to see filtered results

## Troubleshooting

If you see "ai_settings table not found":
1. Make sure you ran `php artisan migrate`
2. Check if both tables exist in your database
3. Run the seeder: `php artisan db:seed --class=AISettingsSeeder`

If AI doesn't respond:
1. Check your `.env` file has `GEMINI_API_KEY`
2. Check the `config/services.php` has the Gemini configuration
3. Check Laravel logs: `storage/logs/laravel.log`

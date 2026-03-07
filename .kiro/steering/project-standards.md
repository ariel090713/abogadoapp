---
inclusion: always
---

# AbogadoMo App - Project Standards & Guidelines

## Project Overview
You are a professional web and mobile app developer, Expert UI/UX designer, and a PH Lawyer with 20 years experience. Your task is to create a SaaS web application for Lawyer Online Consultations.

## Technology Stack (TALL Stack)
- **T**ailwind CSS - Utility-first CSS framework
- **A**lpine.js - Lightweight JavaScript framework
- **L**aravel - PHP framework (backend)
- **L**ivewire - Full-stack framework for Laravel
- **Flux UI** - Component library (already installed)

## Core Principles

### 1. Code Quality & Best Practices
- Follow PSR-12 coding standards for PHP
- Use Laravel best practices (Service classes, Actions, Form Requests)
- Implement Repository pattern for complex data operations
- Use Eloquent relationships efficiently
- Apply SOLID principles
- Write clean, self-documenting code with meaningful variable names
- Use type hints and return types in all methods
- Implement proper error handling with try-catch blocks

### 1.1. Soft Delete Handling (CRITICAL)
**ALWAYS handle soft deletes properly to prevent null reference errors**

The User model uses SoftDeletes, which means deleted users are not actually removed from the database but marked as deleted. This causes issues when accessing relationships.

**Required Practices:**

1. **Always use `whereHas()` when querying related models:**
```php
// GOOD: Excludes soft-deleted users
LawyerProfile::whereHas('user')->get();

// BAD: May include profiles with deleted users
LawyerProfile::with('user')->get();
```

2. **Always check for null in Blade templates:**
```blade
{{-- GOOD: Safe null check --}}
@if($lawyer->user)
    {{ $lawyer->user->name }}
@endif

{{-- BAD: Will error if user is soft deleted --}}
{{ $lawyer->user->name }}
```

3. **Use null-safe operators in PHP:**
```php
// GOOD: Null-safe
$name = $lawyer->user?->name ?? 'Unknown';

// BAD: Will error if user is null
$name = $lawyer->user->name;
```

4. **Common patterns to remember:**
```php
// Consultations - always check both client and lawyer exist
Consultation::whereHas('client')->whereHas('lawyer')->get();

// Reviews - check if reviewer exists
Review::whereHas('client')->get();

// Any user relationship - always validate
Model::whereHas('user')->get();
```

**Why this matters:**
- Soft-deleted users remain in database with `deleted_at` timestamp
- Relationships return `null` when accessing soft-deleted records
- Accessing properties on `null` causes "Attempt to read property on null" errors
- This affects: LawyerProfile, Consultation, Review, Transaction, and any model with user relationships

### 2. Component Architecture
- **Always use Livewire components** for interactive features
- **Create reusable components** - avoid code duplication
- Component structure:
  - `app/Livewire/` - Livewire component classes
  - `resources/views/livewire/` - Livewire component views
  - `resources/views/components/` - Blade components
  - `resources/views/flux/` - Custom Flux UI components

### 3. User Interface & Experience

#### UI Components (Use Flux UI)
- **Buttons**: Use `<flux:button>` with consistent variants (primary, secondary, danger)
- **Forms**: Use `<flux:input>`, `<flux:textarea>`, `<flux:select>`, `<flux:checkbox>`
- **Modals**: Use `<flux:modal>` for all confirmations and dialogs
- **Notifications**: Use `<flux:toast>` or flash notifications, NEVER use JavaScript alerts
- **Cards**: Use `<flux:card>` for content containers
- **Tables**: Use `<flux:table>` for data display

#### Loading States (Prevent Double Clicks)
**CRITICAL**: Always add loading states to form buttons to prevent double submissions

```blade
<!-- Good: Button with loading state -->
<flux:button 
    wire:click="submit" 
    variant="primary" 
    wire:loading.attr="disabled" 
    wire:loading.class="opacity-50 cursor-not-allowed"
>
    <span wire:loading.remove wire:target="submit">Submit</span>
    <span wire:loading wire:target="submit" class="flex items-center gap-2">
        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Processing...
    </span>
</flux:button>

<!-- Bad: Button without loading state -->
<flux:button wire:click="submit" variant="primary">
    Submit
</flux:button>
```

**Loading State Guidelines:**
- Use `wire:loading.attr="disabled"` to disable button during processing
- Use `wire:loading.class="opacity-50 cursor-not-allowed"` for visual feedback
- Show spinner icon with "Processing...", "Submitting...", or "Loading..." text
- Target specific actions with `wire:target="methodName"` for multiple buttons
- Apply to ALL form submission buttons (save, update, delete, submit, etc.)

#### Confirmation Patterns
```php
// NEVER use: confirm(), alert(), prompt()
// ALWAYS use: Flux modals with Livewire actions

// Example:
<flux:modal name="delete-confirmation" class="space-y-6">
    <div>
        <flux:heading size="lg">Confirm Deletion</flux:heading>
        <flux:subheading>Are you sure you want to delete this item?</flux:subheading>
    </div>
    <div class="flex gap-2 justify-end">
        <flux:button variant="ghost" x-on:click="$flux.close('delete-confirmation')">Cancel</flux:button>
        <flux:button variant="danger" wire:click="delete">Delete</flux:button>
    </div>
</flux:modal>
```

#### Notification Patterns
```php
// Flash notifications in Livewire components
session()->flash('success', 'Operation completed successfully');
session()->flash('error', 'An error occurred');
session()->flash('warning', 'Please review this information');
session()->flash('info', 'Here is some information');

// Display in layout
@if (session('success'))
    <flux:toast variant="success">{{ session('success') }}</flux:toast>
@endif
```

### 4. Design System

#### Theme: Modern Filipino Legal (Red-Blue, Elegant)
**Light Theme** - Default for all pages

**CRITICAL: All page backgrounds must be white (#FFFFFF)**
- Use `bg-white` for all main page backgrounds
- Use `bg-gray-50` (#F8FAFC) only for subtle section backgrounds within pages
- Never use gray backgrounds as the main page background

#### Color Palette
```css
/* Primary Colors */
--color-primary-blue: #1E3A8A;      /* Deep navy - trust, authority */
--color-accent-red: #B91C1C;        /* Deep legal red - strength, justice */

/* Backgrounds */
--color-bg-light: #F8FAFC;          /* Clean light gray background */
--color-bg-white: #FFFFFF;          /* Pure white for cards */

/* Text */
--color-text-dark: #0F172A;         /* Almost black - primary text */
--color-text-gray: #64748B;         /* Gray - secondary text */

/* Semantic Colors */
--color-success: #16A34A;           /* Green - success states */
--color-warning: #EA580C;           /* Orange - warnings */
--color-danger: #B91C1C;            /* Red - errors/destructive */
--color-info: #1E3A8A;              /* Blue - informational */
```

#### Tailwind Configuration
Add to `tailwind.config.js`:
```javascript
colors: {
  primary: {
    DEFAULT: '#1E3A8A',
    50: '#EFF6FF',
    100: '#DBEAFE',
    500: '#1E3A8A',
    600: '#1E40AF',
    700: '#1E3A8A',
    900: '#0F172A',
  },
  accent: {
    DEFAULT: '#B91C1C',
    50: '#FEF2F2',
    100: '#FEE2E2',
    500: '#B91C1C',
    600: '#991B1B',
    700: '#7F1D1D',
  },
}
```

#### Color Usage Guidelines
- **Primary Blue (#1E3A8A)**: Main CTAs, navigation, links, primary buttons
- **Accent Red (#B91C1C)**: Important actions, alerts, lawyer badges, destructive actions
- **Light Background (#F8FAFC)**: Subtle section backgrounds within pages
- **White Background (#FFFFFF)**: Main page backgrounds
- **Text Dark (#0F172A)**: All body text, headings

#### Logo Guidelines
**CRITICAL**: All logos must follow this standard format:

```blade
<!-- Logo Pattern: Square with rounded corners, red background, white icon -->
<div class="bg-accent-500 rounded-lg p-2 flex items-center justify-center">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
    </svg>
</div>
```

**Logo Specifications:**
- Background: `bg-accent-500` (#B91C1C - red)
- Shape: Square with `rounded-lg`
- Padding: `p-2` for icon spacing
- Icon Color: `text-white`
- Icon: Justice scales SVG
- Sizes:
  - Small: `w-8 h-8` container, `w-4 h-4` icon
  - Medium: `w-10 h-10` container, `w-6 h-6` icon (RECOMMENDED)
  - Large: `w-12 h-12` container, `w-8 h-8` icon

**Usage:**
- Use in navigation headers
- Use in authentication pages (login, register)
- Use in email templates
- Use in footer
- Always maintain square aspect ratio with red background

#### Typography
- **Headings**: Use Flux heading components with consistent sizing
- **Body**: Inter font family (modern, professional, readable)
- **Legal Documents**: Use serif fonts (Georgia, Times New Roman) for readability
- **Font Weights**: 
  - Regular (400) for body text
  - Medium (500) for emphasis
  - Semibold (600) for subheadings
  - Bold (700) for headings

#### Typography Scale
```css
/* Font Sizes */
text-xs: 0.75rem (12px)      /* Small labels, captions */
text-sm: 0.875rem (14px)     /* Secondary text, form labels */
text-base: 1rem (16px)       /* Body text (default) */
text-lg: 1.125rem (18px)     /* Large body text */
text-xl: 1.25rem (20px)      /* Small headings */
text-2xl: 1.5rem (24px)      /* Section headings */
text-3xl: 1.875rem (30px)    /* Page headings */
text-4xl: 2.25rem (36px)     /* Hero headings */
text-5xl: 3rem (48px)        /* Large hero headings */
text-6xl: 3.75rem (60px)     /* Extra large hero */

/* Line Heights */
leading-tight: 1.25          /* Headings */
leading-normal: 1.5          /* Body text */
leading-relaxed: 1.625       /* Comfortable reading */
```

#### Spacing System
**CRITICAL**: Use consistent spacing throughout the application

```css
/* Tailwind Spacing Scale (use these values consistently) */
0: 0px
0.5: 0.125rem (2px)
1: 0.25rem (4px)
1.5: 0.375rem (6px)
2: 0.5rem (8px)
2.5: 0.625rem (10px)
3: 0.75rem (12px)
4: 1rem (16px)
5: 1.25rem (20px)
6: 1.5rem (24px)
8: 2rem (32px)
10: 2.5rem (40px)
12: 3rem (48px)
16: 4rem (64px)
20: 5rem (80px)
24: 6rem (96px)
```

#### Spacing Guidelines

**Component Spacing:**
```css
/* Cards & Containers */
p-4: Small cards, compact sections
p-6: Standard cards, most components (RECOMMENDED)
p-8: Large cards, feature sections
p-10: Hero sections, major containers

/* Sections */
py-8: Small sections
py-12: Standard sections
py-16: Large sections (RECOMMENDED)
py-20: Hero sections
py-24: Major sections, landing page blocks

/* Gaps & Spacing */
gap-2: Tight spacing (buttons, tags)
gap-3: Compact spacing (form fields)
gap-4: Standard spacing (RECOMMENDED)
gap-6: Comfortable spacing (cards, sections)
gap-8: Generous spacing (major sections)

/* Margins */
mb-2: Tight bottom margin
mb-4: Standard bottom margin (RECOMMENDED)
mb-6: Comfortable bottom margin
mb-8: Section bottom margin
```

**Consistent Patterns:**
```blade
<!-- Card Pattern -->
<div class="bg-white rounded-2xl shadow-lg p-6 space-y-4">
    <h3 class="text-xl font-bold mb-4">Title</h3>
    <div class="space-y-3">
        <!-- Content with consistent spacing -->
    </div>
</div>

<!-- Section Pattern -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">Section Title</h2>
            <p class="text-xl text-gray-600">Description</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Grid items -->
        </div>
    </div>
</section>

<!-- Form Pattern -->
<form class="space-y-6">
    <div class="space-y-2">
        <label class="block text-sm font-medium">Label</label>
        <input class="w-full px-4 py-2 rounded-lg border">
    </div>
</form>
```

#### Border Radius
```css
rounded-sm: 0.25rem (4px)    /* Small elements */
rounded-md: 0.375rem (6px)   /* Form inputs */
rounded-lg: 0.5rem (8px)     /* Buttons, small cards */
rounded-xl: 0.75rem (12px)   /* Standard cards (RECOMMENDED) */
rounded-2xl: 1rem (16px)     /* Large cards, modals */
rounded-3xl: 1.5rem (24px)   /* Hero sections */
rounded-full: 9999px         /* Pills, avatars */
```

#### Shadow System
```css
shadow-sm: Subtle elevation
shadow-md: Default cards
shadow-lg: Elevated cards (RECOMMENDED)
shadow-xl: Prominent elements
shadow-2xl: Modals, popovers
```

### 5. Mobile Responsive Design
**CRITICAL**: All components must be mobile-first and responsive

#### Breakpoints
```css
/* Tailwind Breakpoints */
sm: 640px   /* Small tablets */
md: 768px   /* Tablets */
lg: 1024px  /* Laptops */
xl: 1280px  /* Desktops */
2xl: 1536px /* Large desktops */
```

#### Mobile-First Approach
Always design for mobile first, then enhance for larger screens:

```blade
<!-- GOOD: Mobile-first -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Starts with 1 column, then 2 on tablets, 3 on desktop -->
</div>

<!-- BAD: Desktop-first -->
<div class="grid grid-cols-3 md:grid-cols-2 sm:grid-cols-1">
    <!-- Don't do this -->
</div>
```

#### Responsive Patterns

**Container Widths:**
```blade
<!-- Standard Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Content -->
</div>

<!-- Narrow Container (forms, articles) -->
<div class="max-w-2xl mx-auto px-4 sm:px-6">
    <!-- Content -->
</div>

<!-- Wide Container (dashboards) -->
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Content -->
</div>
```

**Grid Layouts:**
```blade
<!-- 2-Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
    <!-- Mobile: 1 column, Desktop: 2 columns -->
</div>

<!-- 3-Column Layout -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
    <!-- Mobile: 1, Tablet: 2, Desktop: 3 -->
</div>

<!-- 4-Column Layout -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <!-- Mobile: 2, Tablet: 3, Desktop: 4 -->
</div>

<!-- Sidebar Layout -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <aside class="lg:col-span-1"><!-- Sidebar --></aside>
    <main class="lg:col-span-3"><!-- Main content --></main>
</div>
```

**Typography Responsive:**
```blade
<!-- Hero Heading -->
<h1 class="text-4xl md:text-5xl lg:text-6xl font-bold">
    <!-- Scales from 36px to 48px to 60px -->
</h1>

<!-- Section Heading -->
<h2 class="text-3xl md:text-4xl lg:text-5xl font-bold">
    <!-- Scales from 30px to 36px to 48px -->
</h2>

<!-- Body Text -->
<p class="text-base md:text-lg">
    <!-- 16px on mobile, 18px on tablet+ -->
</p>
```

**Spacing Responsive:**
```blade
<!-- Section Padding -->
<section class="py-12 md:py-16 lg:py-24">
    <!-- Increases padding on larger screens -->
</section>

<!-- Card Padding -->
<div class="p-4 md:p-6 lg:p-8">
    <!-- Increases padding on larger screens -->
</div>

<!-- Gap Spacing -->
<div class="space-y-4 md:space-y-6 lg:space-y-8">
    <!-- Increases gap on larger screens -->
</div>
```

**Navigation Responsive:**
```blade
<!-- Mobile Menu Toggle -->
<button class="lg:hidden">
    <!-- Show on mobile, hide on desktop -->
</button>

<!-- Desktop Navigation -->
<nav class="hidden lg:flex items-center gap-6">
    <!-- Hide on mobile, show on desktop -->
</nav>

<!-- Mobile Navigation -->
<nav class="lg:hidden">
    <!-- Show on mobile, hide on desktop -->
</nav>
```

**Images Responsive:**
```blade
<!-- Responsive Image -->
<img src="..." 
     class="w-full h-auto object-cover rounded-lg"
     alt="...">

<!-- Aspect Ratio -->
<div class="aspect-video rounded-lg overflow-hidden">
    <img src="..." class="w-full h-full object-cover" alt="...">
</div>

<!-- Different sizes -->
<img src="..." 
     class="w-full md:w-1/2 lg:w-1/3"
     alt="...">
```

**Buttons Responsive:**
```blade
<!-- Full width on mobile, auto on desktop -->
<button class="w-full md:w-auto px-6 py-3 rounded-lg">
    Button Text
</button>

<!-- Stack on mobile, inline on desktop -->
<div class="flex flex-col md:flex-row gap-4">
    <button>Button 1</button>
    <button>Button 2</button>
</div>
```

**Forms Responsive:**
```blade
<!-- Form Layout -->
<form class="space-y-6">
    <!-- Single column on mobile, two columns on desktop -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label>First Name</label>
            <input type="text">
        </div>
        <div>
            <label>Last Name</label>
            <input type="text">
        </div>
    </div>
</form>
```

**Cards Responsive:**
```blade
<!-- Card Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
    <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
        <!-- Card content -->
    </div>
</div>
```

#### Mobile-Specific Considerations

**Touch Targets:**
- Minimum 44x44px for touch elements
- Use `p-3` or larger for buttons
- Add spacing between clickable elements

**Text Readability:**
- Minimum 16px font size for body text
- Use `text-base` or larger
- Adequate line height (`leading-normal` or `leading-relaxed`)

**Viewport:**
```html
<!-- Always include in <head> -->
<meta name="viewport" content="width=device-width, initial-scale=1">
```

**Scrolling:**
```blade
<!-- Horizontal scroll for tables on mobile -->
<div class="overflow-x-auto">
    <table class="min-w-full">
        <!-- Table content -->
    </table>
</div>

<!-- Vertical scroll with max height -->
<div class="max-h-96 overflow-y-auto">
    <!-- Scrollable content -->
</div>
```

**Hidden Elements:**
```blade
<!-- Hide on mobile -->
<div class="hidden md:block">Desktop only</div>

<!-- Hide on desktop -->
<div class="md:hidden">Mobile only</div>

<!-- Show different content -->
<div class="block md:hidden">Mobile content</div>
<div class="hidden md:block">Desktop content</div>
```

#### Testing Checklist
- [ ] Test on mobile (320px - 480px)
- [ ] Test on tablet (768px - 1024px)
- [ ] Test on desktop (1280px+)
- [ ] Test touch interactions
- [ ] Test landscape orientation
- [ ] Test with Chrome DevTools device emulation
- [ ] Test on actual devices when possible

#### Common Responsive Patterns

**Hero Section:**
```blade
<section class="py-12 md:py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
            <div class="space-y-6 lg:space-y-8">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold">
                    Hero Title
                </h1>
                <p class="text-lg md:text-xl text-gray-600">
                    Description text
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button class="w-full sm:w-auto">CTA 1</button>
                    <button class="w-full sm:w-auto">CTA 2</button>
                </div>
            </div>
            <div class="hidden lg:block">
                <!-- Image or illustration -->
            </div>
        </div>
    </div>
</section>
```

**Feature Grid:**
```blade
<section class="py-16 md:py-20 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 md:mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
                Features
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <!-- Feature cards -->
        </div>
    </div>
</section>
```

**Sidebar Layout:**
```blade
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
        <!-- Sidebar: Full width on mobile, 1/4 on desktop -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-4">
                <!-- Filters -->
            </div>
        </aside>
        
        <!-- Main: Full width on mobile, 3/4 on desktop -->
        <main class="lg:col-span-3">
            <div class="space-y-6">
                <!-- Content -->
            </div>
        </main>
    </div>
</div>
```

### 5. File Upload & Storage

#### AWS S3 Configuration
```php
// Two separate S3 buckets configured:
// 1. s3-public: For profile photos, public documents (world-readable)
// 2. s3-private: For sensitive documents like IBP cards, legal documents (authenticated access only)

// Configuration in .env:
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_PUBLIC_BUCKET=
AWS_PRIVATE_BUCKET=
AWS_URL=
AWS_USE_PATH_STYLE_ENDPOINT=false

// In config/filesystems.php - both 's3-public' and 's3-private' disks are configured
```

#### File Upload Service Usage
```php
use App\Services\FileUploadService;

class ProfileController extends Controller
{
    public function __construct(
        private FileUploadService $fileService
    ) {}
    
    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120', // 5MB max
        ]);
        
        // Upload to public bucket
        $fileData = $this->fileService->uploadPublic(
            $request->file('photo'),
            'profile-photos'
        );
        
        // Save to database
        auth()->user()->update([
            'profile_photo' => $fileData['path'],
            'profile_photo_url' => $fileData['url'],
        ]);
        
        return back()->with('success', 'Profile photo updated');
    }
    
    public function uploadIBPCard(Request $request)
    {
        $request->validate([
            'ibp_card' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png',
        ]);
        
        // Upload to private bucket
        $fileData = $this->fileService->uploadPrivate(
            $request->file('ibp_card'),
            'ibp-cards'
        );
        
        // Save to database
        auth()->user()->lawyerProfile->update([
            'ibp_card_path' => $fileData['path'],
            'ibp_card_original_name' => $fileData['original_name'],
        ]);
        
        return back()->with('success', 'IBP card uploaded');
    }
    
    public function viewIBPCard()
    {
        $path = auth()->user()->lawyerProfile->ibp_card_path;
        
        // Generate temporary signed URL (expires in 1 hour)
        $url = $this->fileService->getPrivateUrl($path, 60);
        
        return redirect($url);
    }
}
```

#### File Upload Component Pattern (Livewire)
```php
use Livewire\WithFileUploads;
use App\Services\FileUploadService;

class DocumentUpload extends Component
{
    use WithFileUploads;
    
    public $document;
    
    public function save(FileUploadService $fileService)
    {
        $this->validate([
            'document' => 'required|file|max:10240|mimes:pdf,doc,docx',
        ]);
        
        try {
            // Upload to private bucket
            $fileData = $fileService->uploadPrivate(
                $this->document,
                'legal-documents'
            );
            
            // Save to database
            auth()->user()->documents()->create([
                'path' => $fileData['path'],
                'original_filename' => $fileData['original_name'],
                'encrypted_filename' => $fileData['encrypted_name'],
                'size' => $fileData['size'],
                'mime_type' => $fileData['mime_type'],
            ]);
            
            session()->flash('success', 'Document uploaded successfully');
            $this->reset('document');
            
        } catch (\Exception $e) {
            Log::error('Document upload failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            session()->flash('error', 'Upload failed. Please try again.');
        }
    }
}
```

**File Naming Convention for S3:**
- Format: `{32-char-random-string}_{timestamp}.{extension}`
- Example: `a7f3k9m2p5q8r1t4v6w9x2y5z8b3c6d9_20260224183045.pdf`
- Always store original filename in database for display purposes
- Use encrypted filename for S3 storage to prevent guessing/enumeration

**File Types by Bucket:**

Public Bucket (s3-public):
- Profile photos (profile-photos/)
- Lawyer public documents (public-documents/)
- Platform assets (assets/)

Private Bucket (s3-private):
- IBP cards (ibp-cards/)
- Legal documents (legal-documents/)
- Consultation documents (consultation-documents/)
- Bank verification documents (bank-documents/)
- ID verification (id-verification/)

### 6. Error Logging & Monitoring

#### Laravel Log Channels
```php
// Use appropriate log levels
Log::emergency('System is down');
Log::alert('Action must be taken immediately');
Log::critical('Critical conditions');
Log::error('Runtime errors');
Log::warning('Warning messages');
Log::notice('Normal but significant');
Log::info('Informational messages');
Log::debug('Debug-level messages');

// Context logging
Log::error('Payment failed', [
    'user_id' => $user->id,
    'amount' => $amount,
    'error' => $exception->getMessage(),
]);
```

#### Error Handling Pattern
```php
try {
    // Operation
    $result = $this->performOperation();
    
    Log::info('Operation successful', ['result' => $result]);
    session()->flash('success', 'Operation completed');
    
} catch (\Exception $e) {
    Log::error('Operation failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
    
    session()->flash('error', 'An error occurred. Please try again.');
}
```

### 7. Performance & Scalability

#### Database Optimization
- Use database indexes on frequently queried columns
- Implement eager loading to avoid N+1 queries
- Use database transactions for multi-step operations
- Implement soft deletes for important data
- Use database queues for background jobs

```php
// Good: Eager loading
$consultations = Consultation::with(['lawyer', 'client', 'documents'])->get();

// Bad: N+1 problem
$consultations = Consultation::all();
foreach ($consultations as $consultation) {
    echo $consultation->lawyer->name; // Triggers additional query
}
```

#### Caching Strategy
```php
// Cache expensive queries
$lawyers = Cache::remember('active-lawyers', 3600, function () {
    return Lawyer::where('status', 'active')
        ->with('specializations')
        ->get();
});

// Clear cache when data changes
Cache::forget('active-lawyers');
```

#### Queue Jobs
```php
// Use queues for time-consuming tasks
dispatch(new SendConsultationReminder($consultation));
dispatch(new GenerateInvoicePDF($invoice));
dispatch(new ProcessDocumentUpload($document));
```

### 8. Security Best Practices

#### Authentication & Authorization
- Use Laravel Fortify for authentication (already configured)
- Implement two-factor authentication for lawyers
- Use Laravel Policies for authorization
- Validate all user inputs
- Use CSRF protection (enabled by default)

```php
// Policy example
class ConsultationPolicy
{
    public function view(User $user, Consultation $consultation)
    {
        return $user->id === $consultation->client_id 
            || $user->id === $consultation->lawyer_id;
    }
}

// In controller/Livewire
$this->authorize('view', $consultation);
```

#### Data Validation
```php
// Always validate in Livewire components
protected function rules()
{
    return [
        'email' => 'required|email|unique:users',
        'phone' => 'required|regex:/^09[0-9]{9}$/', // PH mobile format
        'consultation_date' => 'required|date|after:now',
    ];
}
```

### 9. Legal Compliance (Philippine Context)

#### Data Privacy (DPA 2012)
- Implement consent mechanisms for data collection
- Provide privacy policy and terms of service
- Allow users to request data deletion
- Encrypt sensitive data
- Log access to sensitive information

#### Professional Standards
- Implement lawyer verification system
- Store IBP (Integrated Bar of the Philippines) credentials
- Maintain consultation confidentiality
- Implement secure messaging for lawyer-client communication

### 10. Environment Configuration

#### Required .env Variables
```bash
# Application
APP_NAME="AbogadoMo App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=AbogadoMo_app
DB_USERNAME=
DB_PASSWORD=

# AWS S3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Mail
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

# Queue
QUEUE_CONNECTION=database

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=debug

# Payment Gateway (for future)
PAYMONGO_PUBLIC_KEY=
PAYMONGO_SECRET_KEY=
```

## Development Workflow

### Before Starting Any Feature
1. Check this steering file for standards
2. Review existing components for reusability
3. Plan database schema changes
4. Consider security implications
5. Think about error handling and logging

### Code Review Checklist
- [ ] Uses Livewire components appropriately
- [ ] Implements proper validation
- [ ] Uses Flux UI components consistently
- [ ] Has error logging
- [ ] Uses modals instead of alerts
- [ ] Implements proper authorization
- [ ] Optimized database queries
- [ ] Follows naming conventions
- [ ] Has proper type hints
- [ ] Uses environment variables for configuration

## Naming Conventions

### Files & Classes
- **Livewire Components**: PascalCase (e.g., `CreateConsultation.php`)
- **Blade Views**: kebab-case (e.g., `create-consultation.blade.php`)
- **Database Tables**: snake_case, plural (e.g., `consultations`, `lawyer_specializations`)
- **Models**: PascalCase, singular (e.g., `Consultation`, `Lawyer`)

### Variables & Methods
- **Variables**: camelCase (e.g., `$consultationDate`)
- **Methods**: camelCase (e.g., `createConsultation()`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MAX_UPLOAD_SIZE`)

## Testing Standards
- Write feature tests for critical user flows
- Use Pest PHP for testing (already configured)
- Test authentication and authorization
- Test file uploads
- Test payment processing
- Mock external services (S3, payment gateways)

---

**Remember**: Consistency is key. Always refer to this guide when implementing new features or refactoring existing code.


## Documentation Standards

### Summary Documents
- **CRITICAL**: Do NOT create markdown summary documents (like TASK-COMPLETED.md, IMPLEMENTATION-SUMMARY.md, etc.) unless explicitly requested by the user
- This includes: completion summaries, implementation logs, change logs, or any documentation of work done
- Exception: Only create documentation files when the user specifically asks for them
- Keep responses concise - just state what was accomplished in a few sentences

### Code Comments
- Write clear, concise comments for complex logic
- Document public methods and classes with PHPDoc
- Avoid obvious comments that just repeat the code
- Focus on explaining "why" not "what"

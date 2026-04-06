# PAULETTE CULTURE KIDS — Complete System Specification v2.0

**Production-Ready System Documentation**  
Laravel 11 · MySQL 8 · Expo React Native · Offline-First · Multi-Tenant SaaS

---

## TABLE OF CONTENTS

1. [System Architecture Overview](#1-system-architecture-overview)
2. [Backend — Laravel 11](#2-backend--laravel-11)
3. [Database Design](#3-database-design)
4. [Authentication & RBAC](#4-authentication--rbac)
5. [API Design](#5-api-design)
6. [CMS & Content Pipeline](#6-cms--content-pipeline)
7. [Offline-First System](#7-offline-first-system)
8. [Expo React Native App](#8-expo-react-native-app)
9. [Blade Frontend](#9-blade-frontend)
10. [Multi-Tenancy Design](#10-multi-tenancy-design)
11. [Analytics System](#11-analytics-system)
12. [Deployment Architecture](#12-deployment-architecture)
13. [Step-by-Step Build Plan](#13-step-by-step-build-plan)

---

## 1. SYSTEM ARCHITECTURE OVERVIEW

### High-Level Data Flow

```
┌─────────────────────────────────────────────────────────────┐
│                   PAULETTE CULTURE KIDS                      │
│         Tribe-Aware Cultural Learning Platform               │
└─────────────────────────────────────────────────────────────┘

┌─────────────────┐
│  EXPO APP       │  Age 2–6 Child Interface
│  (React Native) │  Offline-First SQLite Local DB
│  📱 Mobile      │  Parent/Teacher/Kiosk Modes
└────────┬────────┘
         │ Sanctum Token Auth
         │ POST /api/v1/sync (progress events)
         │ GET /api/v1/tribes, /content/manifest
         ↓
┌─────────────────────────────────────────────────────────────┐
│             LARAVEL 11 REST API (Sanctum)                   │
│  ┌─────────────┐  ┌──────────────┐  ┌───────────────────┐  │
│  │  Auth Guard │  │ Org Scoping  │  │  Spatie RBAC      │  │
│  │  + Tokens   │  │  (Multi-Ten) │  │  (9 Roles)        │  │
│  └─────────────┘  └──────────────┘  └───────────────────┘  │
│                                                              │
│  Controllers: Auth, Tribe, Content, Progress, Sync, Admin   │
│  Policies: ComicPolicy, ChildProfilePolicy, OrgPolicy       │
│  Middleware: SuperAdminMiddleware, OrgScopingMiddleware      │
└─────────┬──────────────────────────────────────────────────┘
          │
          ├─→ Queue: ProcessComicPDF, BuildOfflineBundle
          │   (Redis + Laravel Horizon)
          │
          ├─→ Cache: Redis (1hr TTL for analytics)
          │
          └─→ Storage: S3 (signed URLs for assets)
              │
              ├─ comics/raw/ (uploaded PDFs)
              ├─ comics/panels/ (extracted images)
              ├─ bundles/ (signed .ckb files)
              └─ audio/ (pronunciation + songs)

┌──────────────────────────────────────────────────────────────┐
│                    MYSQL 8 (InnoDB, UTF8MB4)                 │
│  • organisations (multi-tenant scoping)                       │
│  • users (super_admin, org_admin, cms_editor, teacher, etc) │
│  • child_profiles (age_profile_id determines UI mode)        │
│  • tribes (65+ Ugandan tribes with language packs)           │
│  • comics (content library, versioned by bundle_hash)        │
│  • progress_events (idempotent sync events)                  │
│  • sync_events (offline queue state)                         │
│  • audit_logs (all super_admin actions tracked)             │
│  • theme_configs (org branding override)                     │
│  • age_profiles (2–3, 3–4, 4–5, 5–6 UI modes)              │
│  • lesson_plans (teacher-scheduled content)                  │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│            BLADE FRONTEND (Session-Based Auth)               │
│  ├─ Public: Landing page, tribe explorer                     │
│  ├─ Auth: Login, register, password reset                    │
│  ├─ Parent Dashboard: Child profiles, progress charts        │
│  ├─ Teacher Dashboard: Class roster, lesson planner, reports │
│  ├─ CMS Panel: Comic upload, panel editor, review workflow   │
│  └─ Super Admin: Org management, user impersonation, analytics
│                                                              │
│  Tech: Livewire 3, Alpine.js, Tailwind CSS, Chart.js        │
└──────────────────────────────────────────────────────────────┘
```

### Key Design Decisions

1. **Offline-First Architecture**: Child experience fully functional without connection. SQLite local cache + sync queue. On reconnect, batch sync via Sanctum API.

2. **Multi-Tenant with God-Mode**: All data org-scoped by `org_id`. Super Admin can bypass scoping and impersonate users for support/debugging.

3. **RBAC with Token Abilities**: 9 roles (super_admin, org_admin, cms_editor, teacher, parent, child, etc). Sanctum tokens encode abilities for API. Spatie permissions for Blade UI.

4. **Age-Adaptive UI**: Child see age-appropriate content (2–3: simple/audio-only, 5–6: full text/quizzes). Age profile fetched at startup, cached locally.

5. **Content Pipeline with Jobs**: PDF upload → Laravel Job extracts panels → CMS Editor tags vocab → Admin publishes → Background Job builds signed .ckb bundle for offline.

6. **Idempotent Sync**: Progress events use `idempotency_key` UUID. Server deduplicates. Last-write-wins conflict resolution. Badges calculated server-authoritative.

---

## 2. BACKEND — LARAVEL 11

### Technology Stack

- **Framework**: Laravel 11 (PHP 8.3+)
- **API Auth**: Laravel Sanctum (Personal Access Tokens + Abilities)
- **RBAC**: Spatie/laravel-permission v6.25+
- **ORM**: Eloquent (Global Scopes for org_id)
- **Queue**: Redis + Laravel Horizon (job monitoring)
- **Cache**: Redis (1hr TTL)
- **File Storage**: Laravel Storage (S3 + local fallback)
- **PDF Processing**: ImageMagick/Imagick + ZipArchive
- **Task Scheduling**: Laravel Scheduler (cleanup old bundles, sync reconciliation)

### Project Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php (login, register, logout)
│   │   │   │   ├── TribeController.php (tribes CRUD)
│   │   │   │   ├── ContentController.php (manifest, panels, bundles)
│   │   │   │   ├── SyncController.php (offline sync drain)
│   │   │   │   └── ProgressController.php (events, analytics)
│   │   │   └── Admin/
│   │   │       ├── SuperAdminController.php (god mode)
│   │   │       └── ComicCMSController.php (upload, publish)
│   │   ├── Middleware/
│   │   │   ├── SuperAdminMiddleware.php
│   │   │   └── OrgScopingMiddleware.php
│   │   └── Requests/
│   │       ├── LoginRequest.php
│   │       ├── CreateComicRequest.php
│   │       └── SyncRequest.php
│   ├── Models/
│   │   ├── Organisation.php
│   │   ├── User.php (HasApiTokens, HasRoles)
│   │   ├── ChildProfile.php
│   │   ├── Tribe.php
│   │   ├── Comic.php
│   │   ├── ComicPanel.php
│   │   ├── ProgressEvent.php
│   │   ├── SyncEvent.php
│   │   ├── AgeProfile.php
│   │   ├── ThemeConfig.php
│   │   ├── AuditLog.php
│   │   └── LessonPlan.php
│   ├── Jobs/
│   │   ├── ProcessComicPDF.php (extract panels from PDF)
│   │   └── BuildOfflineBundle.php (create signed .ckb zip)
│   ├── Policies/
│   │   ├── ComicPolicy.php (org-scoped)
│   │   └── ChildProfilePolicy.php (parent/teacher scoped)
│   ├── Services/
│   │   ├── AnalyticsService.php
│   │   └── ThemeService.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── AuthServiceProvider.php
├── database/
│   ├── migrations/
│   │   ├── 2025_04_01_000001_create_organisations_table.php
│   │   ├── 2025_04_01_000002_create_tribes_table.php
│   │   ├── 2025_04_01_000003_create_age_profiles_table.php
│   │   ├── 2025_04_01_000004_modify_users_table.php
│   │   ├── 2025_04_01_000005_create_child_profiles_table.php
│   │   ├── 2025_04_01_000006_create_comics_table.php
│   │   ├── 2025_04_01_000007_create_comic_panels_table.php
│   │   ├── 2025_04_01_000008_create_progress_events_table.php
│   │   ├── 2025_04_01_000009_create_sync_events_table.php
│   │   ├── 2025_04_01_000010_create_audit_logs_table.php
│   │   ├── 2025_04_01_000011_create_theme_configs_table.php
│   │   └── 2025_04_01_000012_create_lesson_plans_table.php
│   └── seeders/
│       └── DatabaseSeeder.php (demo orgs, users, tribes)
├── routes/
│   ├── api.php (versioned v1 endpoints)
│   └── web.php (Blade routes)
├── config/
│   ├── sanctum.php
│   ├── filesystems.php
│   ├── queue.php
│   ├── cache.php
│   └── mail.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── admin/dashboard.blade.php
│   ├── teacher/dashboard.blade.php
│   ├── parent/dashboard.blade.php
│   ├── livewire/
│   ├── auth/
│   └── components/
└── .env.example (config template)
```

### Installation & Setup

```bash
# Clone repository
git clone <repo> culturekids-project/backend
cd culturekids-project/backend

# Install dependencies
composer install

# Copy environment
cp .env.example .env

# Generate app key
php artisan key:generate

# Create database
mysql -u root -p -e "CREATE DATABASE culturekids CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate --seed

# Setup Sanctum (if not auto-discovered)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Setup Spatie Permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan cache:clear

# Create storage symlink
php artisan storage:link

# Start queue worker (background)
php artisan queue:listen

# Start development server
php artisan serve
```

### Required Packages

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.3",
    "laravel/tinker": "^2.10.1",
    "livewire/livewire": "^3.0",
    "spatie/laravel-permission": "^6.25",
    "intervention/image": "^3.0",
    "barryvdh/laravel-dompdf": "^2.0",
    "maatwebsite/excel": "^3.1"
  }
}
```

---

## 3. DATABASE DESIGN

### Complete Schema with Relationships

```sql
-- ORGANISATIONS (Multi-tenant scoping)
CREATE TABLE organisations (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(100) UNIQUE NOT NULL,
  plan ENUM('free', 'school', 'enterprise') DEFAULT 'free',
  modules JSON DEFAULT '["comics", "songs", "vocab", "offline"]',
  theme_config JSON NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  INDEX idx_slug (slug),
  INDEX idx_plan (plan)
);

-- USERS (Super Admin, Org Admin, CMS Editor, Teacher, Parent, Child)
ALTER TABLE users ADD COLUMN (
  org_id BIGINT UNSIGNED NOT NULL DEFAULT 1,
  role ENUM('super_admin', 'org_admin', 'cms_editor', 'teacher', 'parent', 'child') DEFAULT 'parent',
  FOREIGN KEY (org_id) REFERENCES organisations(id) ON DELETE CASCADE,
  INDEX idx_org_id (org_id),
  INDEX idx_role (role)
);

-- TRIBES (65+ Ugandan tribes)
CREATE TABLE tribes (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(100) UNIQUE NOT NULL,
  language VARCHAR(100) NOT NULL,
  region VARCHAR(150),
  greeting VARCHAR(100),
  phonetic VARCHAR(150),
  color_hex CHAR(7),
  emoji_symbol VARCHAR(10),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  INDEX idx_slug (slug),
  INDEX idx_language (language)
);

-- AGE PROFILES (2–3, 3–4, 4–5, 5–6)
CREATE TABLE age_profiles (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  age_min TINYINT UNSIGNED,
  age_max TINYINT UNSIGNED,
  stage VARCHAR(50),
  ui_mode ENUM('simple', 'guided', 'advanced', 'full'),
  difficulty_ceiling TINYINT DEFAULT 3,
  rules JSON NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE KEY unique_age_range (age_min, age_max)
);

-- CHILD PROFILES (Per parent/child relationship)
CREATE TABLE child_profiles (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  parent_user_id BIGINT UNSIGNED NOT NULL,
  org_id BIGINT UNSIGNED NOT NULL,
  age_profile_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  date_of_birth DATE NOT NULL,
  avatar VARCHAR(255) NULL,
  preferred_tribe_ids JSON NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (parent_user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (org_id) REFERENCES organisations(id) ON DELETE CASCADE,
  FOREIGN KEY (age_profile_id) REFERENCES age_profiles(id),
  INDEX idx_parent (parent_user_id),
  INDEX idx_org (org_id),
  INDEX idx_age_profile (age_profile_id)
);

-- COMICS (Content library)
CREATE TABLE comics (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  org_id BIGINT UNSIGNED NOT NULL,
  tribe_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  age_min TINYINT UNSIGNED DEFAULT 2,
  age_max TINYINT UNSIGNED DEFAULT 6,
  status ENUM('draft', 'review', 'published', 'archived') DEFAULT 'draft',
  cover_image_path VARCHAR(500) NULL,
  bundle_path VARCHAR(500) NULL (S3 path),
  bundle_hash CHAR(64) NULL (sha256),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (org_id) REFERENCES organisations(id) ON DELETE CASCADE,
  FOREIGN KEY (tribe_id) REFERENCES tribes(id),
  INDEX idx_org_status (org_id, status),
  INDEX idx_tribe (tribe_id)
);

-- COMIC PANELS (Extracted from PDF, ordered)
CREATE TABLE comic_panels (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  comic_id BIGINT UNSIGNED NOT NULL,
  order_index TINYINT UNSIGNED,
  image_path VARCHAR(500) NOT NULL (S3),
  vocab_tags JSON NULL,
  audio_path VARCHAR(500) NULL (S3),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (comic_id) REFERENCES comics(id) ON DELETE CASCADE,
  UNIQUE KEY unique_panel_order (comic_id, order_index),
  INDEX idx_comic (comic_id)
);

-- PROGRESS EVENTS (Idempotent sync events)
CREATE TABLE progress_events (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  child_id BIGINT UNSIGNED NOT NULL,
  comic_id BIGINT UNSIGNED NULL,
  event_type ENUM('story_start', 'story_complete', 'badge_earned', 'vocab_seen', 'activity_complete'),
  idempotency_key VARCHAR(64) UNIQUE (prevents duplicates),
  payload JSON NULL,
  synced_at TIMESTAMP NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (child_id) REFERENCES child_profiles(id) ON DELETE CASCADE,
  FOREIGN KEY (comic_id) REFERENCES comics(id) ON DELETE SET NULL,
  INDEX idx_child_event (child_id, event_type),
  INDEX idx_event_type (event_type)
);

-- SYNC EVENTS (Offline queue state)
CREATE TABLE sync_events (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  child_id BIGINT UNSIGNED NOT NULL,
  event_type VARCHAR(100),
  payload JSON NOT NULL,
  idempotency_key VARCHAR(64) UNIQUE,
  processed BOOLEAN DEFAULT FALSE,
  processed_at TIMESTAMP NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (child_id) REFERENCES child_profiles(id) ON DELETE CASCADE,
  INDEX idx_child_processed (child_id, processed),
  INDEX idx_created_at (created_at)
);

-- AUDIT LOGS (All Super Admin actions)
CREATE TABLE audit_logs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  impersonator_id BIGINT UNSIGNED NULL,
  action VARCHAR(100),
  model_type VARCHAR(255) NULL,
  model_id BIGINT UNSIGNED NULL,
  old_values JSON NULL,
  new_values JSON NULL,
  ip_address VARCHAR(45),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (impersonator_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_user_action (user_id, action),
  INDEX idx_model (model_type, model_id)
);

-- THEME CONFIGS (Org branding)
CREATE TABLE theme_configs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  org_id BIGINT UNSIGNED NOT NULL UNIQUE,
  colors JSON NULL,
  typography JSON NULL,
  logo_url VARCHAR(500) NULL,
  custom_properties JSON NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (org_id) REFERENCES organisations(id) ON DELETE CASCADE
);

-- LESSON PLANS (Teacher scheduling)
CREATE TABLE lesson_plans (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  org_id BIGINT UNSIGNED NOT NULL,
  teacher_id BIGINT UNSIGNED NOT NULL,
  classroom_id VARCHAR(100),
  title VARCHAR(255),
  assigned_comic_ids JSON NULL,
  assigned_tribe_ids JSON NULL,
  scheduled_at DATETIME,
  status ENUM('draft', 'scheduled', 'completed', 'cancelled') DEFAULT 'draft',
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (org_id) REFERENCES organisations(id) ON DELETE CASCADE,
  FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_org_teacher (org_id, teacher_id),
  INDEX idx_scheduled_at (scheduled_at)
);
```

### Indexing Strategy

- **Composite indexes** on org_id + status (fast org-scoped queries)
- **Unique index** on idempotency_key (prevent duplicate sync events)
- **Timestamp indexes** for analytics queries (DATE grouping)
- **Foreign key indexes** (Eloquent relationships)

### Eloquent Model Example: Comic

```php
class Comic extends Model {
  protected $fillable = ['org_id', 'tribe_id', 'title', 'age_min', 'age_max', 'status', ...];
  
  public function organisation() { return $this->belongsTo(Organisation::class); }
  public function tribe() { return $this->belongsTo(Tribe::class); }
  public function panels() { return $this->hasMany(ComicPanel::class)->orderBy('order_index'); }
  public function progressEvents() { return $this->hasMany(ProgressEvent::class); }
  
  // Global scope: org-scoped by default (Super Admin bypasses)
  protected static function booted() {
    static::addGlobalScope('org', function (Builder $builder) {
      if (auth()->check() && auth()->user()->role !== 'super_admin') {
        $builder->where('org_id', auth()->user()->org_id);
      }
    });
  }
}
```

---

## 4. AUTHENTICATION & RBAC

### Sanctum Token Flow

1. **POST /api/v1/auth/login**
   - Email + password validated
   - Sanctum issues Personal Access Token with `getSanctumAbilities()`
   - Token includes abilities: `["progress:record", "content:read"]` for parent
   - Token stored in Expo SecureStore

2. **Token Abilities by Role**
   ```php
   'super_admin' => ['*'],
   'org_admin' => ['org:manage', 'content:edit', 'users:manage'],
   'cms_editor' => ['content:edit', 'content:submit'],
   'teacher' => ['progress:view', 'progress:record', 'class:manage'],
   'parent' => ['child:manage', 'progress:view:own'],
   'child' => ['progress:record', 'content:read']
   ```

3. **Route Protection**
   ```php
   Route::middleware(['auth:sanctum'])->group(function() {
     Route::get('/tribes', [TribeController::class, 'index']);
     Route::post('/progress/events', [ProgressController::class, 'recordEvent'])->can('progress:record');
   });
   ```

### Spatie Permission Setup

```php
// CreateRolesAndPermissionsSeeder.php
$roles = ['super_admin', 'org_admin', 'cms_editor', 'teacher', 'parent', 'child'];

foreach ($roles as $role) {
  Role::firstOrCreate(['name' => $role]);
}

$role = Role::findByName('cms_editor');
$role->givePermissionTo(['edit_comics', 'view_comics']);

// In model
User::create([...])->assignRole('cms_editor');

// In Blade
@can('edit_comics')
  <button>Edit Comic</button>
@endcan

// In controller
$this->authorize('edit_comics');
```

### Policies (Org-Scoping Enforcement)

```php
// app/Policies/ComicPolicy.php
public function view(User $user, Comic $comic): bool {
  if ($user->role === 'super_admin') return true;
  return $user->org_id === $comic->org_id && $comic->status === 'published';
}

public function update(User $user, Comic $comic): bool {
  if ($user->role === 'super_admin') return true;
  return in_array($user->role, ['org_admin', 'cms_editor']) && $user->org_id === $comic->org_id;
}

// In controller
public function publish(Comic $comic) {
  $this->authorize('update', $comic);
  $comic->update(['status' => 'published']);
}
```

### Middleware: SuperAdminMiddleware

```php
// app/Http/Middleware/SuperAdminMiddleware.php
public function handle(Request $request, Closure $next) {
  if (auth()->check() && auth()->user()->role === 'super_admin') {
    return $next($request);
  }
  return response()->json(['error' => 'Forbidden'], 403);
}

// Use: Route::middleware(['super_admin'])->group(...)
```

### Session-Based Web Auth

For Blade dashboards, use Laravel's default session auth:

```php
Route::middleware(['auth'])->group(function() {
  Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard']);
});

// In middleware HTTP kernel
protected $middleware = [
  ...
  \App\Http\Middleware\EncryptCookies::class,
  \Illuminate\Session\Middleware\StartSession::class,
  \Illuminate\Session\Middleware\ShareErrorsFromSession::class,
];
```

---

## 5. API DESIGN

### Full Route List (Versioned — /api/v1)

#### Authentication (Public)
```
POST   /api/v1/auth/login          Public
POST   /api/v1/auth/register       Public
POST   /api/v1/auth/logout         Token
GET    /api/v1/auth/user           Token
```

#### Content & Tribes (Token)
```
GET    /api/v1/tribes              Token
GET    /api/v1/tribes/{id}         Token
GET    /api/v1/tribes/{id}/comics  Token
GET    /api/v1/age-profiles        Token
GET    /api/v1/content/manifest    Token
GET    /api/v1/comics/{id}/panels  Token
GET    /api/v1/bundles/{tribe_id}  Token
```

#### Progress & Sync (Token)
```
POST   /api/v1/sync                Token
POST   /api/v1/progress/events     Token
GET    /api/v1/progress/child/{id} Token
GET    /api/v1/child-profiles      Token
POST   /api/v1/child-profiles      Token
```

#### CMS (Token + Ability)
```
POST   /api/v1/cms/comics/upload   Token + content:edit
PUT    /api/v1/cms/comics/{id}/publish Token + content:edit
```

#### Super Admin (SuperAdminMiddleware)
```
GET    /admin/dashboard            Super Admin
GET    /admin/organisations        Super Admin
POST   /admin/organisations        Super Admin
PUT    /admin/organisations/{id}/modules Super Admin
PUT    /admin/age-profiles/{id}    Super Admin
PUT    /admin/themes/{org_id}      Super Admin
POST   /admin/users/{id}/impersonate Super Admin
```

### Request/Response Examples

#### Login Request
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "parent@culturekids.app",
  "password": "password"
}

Response 200:
{
  "token": "1|abc123xyz...",
  "user": {
    "id": 5,
    "name": "Parent",
    "email": "parent@culturekids.app",
    "org_id": 1,
    "role": "parent"
  }
}
```

#### Record Progress Event
```http
POST /api/v1/progress/events
Authorization: Bearer 1|abc123xyz...
Content-Type: application/json

{
  "child_id": 12,
  "event_type": "story_complete",
  "comic_id": 8,
  "payload": {
    "tribe_id": 1,
    "time_spent": 325
  }
}

Response 201:
{
  "id": 451,
  "child_id": 12,
  "comic_id": 8,
  "event_type": "story_complete",
  "idempotency_key": "progress_1711900000000_k3j2h4jk",
  "synced_at": "2025-04-01T14:00:00Z",
  "created_at": "2025-04-01T14:00:00Z"
}
```

#### Upload Comic
```http
POST /api/v1/cms/comics/upload
Authorization: Bearer 1|abc123xyz...
Content-Type: multipart/form-data

Form Data:
  - title: "The Clever Hare of Buganda"
  - tribe_id: 1
  - age_min: 3
  - age_max: 5
  - pdf_file: <file>

Response 201:
{
  "message": "Comic uploaded. Processing started.",
  "comic": {
    "id": 189,
    "org_id": 1,
    "tribe_id": 1,
    "title": "The Clever Hare of Buganda",
    "status": "draft",
    "created_at": "2025-04-01T14:00:00Z"
  }
}
```

#### Sync Offline Events
```http
POST /api/v1/sync
Authorization: Bearer 1|abc123xyz...
Content-Type: application/json

{
  "events": [
    {
      "event_type": "story_complete",
      "child_id": 12,
      "comic_id": 8,
      "idempotency_key": "story_complete_12_8_001",
      "payload": {
        "time_spent": 325
      }
    },
    {
      "event_type": "story_complete",
      "child_id": 12,
      "comic_id": 9,
      "idempotency_key": "story_complete_12_9_001",
      "payload": {
        "time_spent": 410
      }
    }
  ]
}

Response 200:
{
  "message": "Sync completed",
  "events_processed": 2,
  "events": [
    { "id": 451, "event_type": "story_complete", "synced_at": "..." },
    { "id": 452, "event_type": "story_complete", "synced_at": "..." }
  ]
}
```

---

## 6. CMS & CONTENT PIPELINE

### PDF Upload Flow

```
1. CMS Editor uploads PDF via Blade form
   ↓
2. Laravel saves to S3: s3://comics/raw/{org_id}/{filename}.pdf
   ↓
3. Laravel dispatches ProcessComicPDF job to Redis queue
   ↓
4. Job worker:
   - Downloads PDF from S3
   - Extracts pages using Imagick
   - Saves each page as JPEG to S3: s3://comics/panels/{comic_id}/panel_{n}.jpg
   - Creates ComicPanel records in MySQL
   - Sets comic status to 'review'
   ↓
5. CMS Editor opens Panel Editor (Alpine.js)
   - Click on image area to tag vocab words
   - Upload audio files per panel
   - Save tags to comic_panels.vocab_tags JSON
   ↓
6. Org Admin reviews in Review Panel
   ↓
7. On approval, Admin clicks "Publish"
   - Comic status → 'published'
   - BuildOfflineBundle job dispatched
   ↓
8. BuildOfflineBundle Job:
   - Collects all panel images + audio
   - Creates metadata.json with comic info
   - ZIPs into signed .ckb file
   - Uploads to S3: s3://bundles/{org_id}/{comic_id}_{timestamp}.ckb
   - Updates comics.bundle_path and bundle_hash
   ↓
9. Expo app detects new bundle
   - Fetches manifest via GET /api/v1/content/manifest
   - Downloads .ckb via FileSystem.downloadAsync
   - Extracts to local storage
   - Available offline for child
```

### ProcessComicPDF Job

```php
// app/Jobs/ProcessComicPDF.php
class ProcessComicPDF implements ShouldQueue {
  public function __construct(private string $pdfPath, private int $comicId, private int $userId) {}

  public function handle() {
    $comic = Comic::findOrFail($this->comicId);
    
    // Get PDF from S3
    $pdfContent = Storage::disk('s3')->get($this->pdfPath);
    $localPath = storage_path("app/temp/{$this->comicId}.pdf");
    file_put_contents($localPath, $pdfContent);

    // Extract pages with Imagick
    $imagick = new \Imagick();
    $imagick->readImage($localPath . '[*]');
    $imagick->setImageFormat('jpg');

    foreach ($imagick as $index => $image) {
      $image->resizeImage(800, 1200, \Imagick::FILTER_LANCZOS, 1);
      $panelPath = "comics/panels/{$comic->id}/panel_{$index}.jpg";
      Storage::disk('s3')->put($panelPath, $image->getImageBlob());
      
      ComicPanel::create([
        'comic_id' => $comic->id,
        'order_index' => $index,
        'image_path' => $panelPath,
      ]);
    }

    unlink($localPath);
    $imagick->clear();

    $comic->update(['status' => 'review']);
  }
}
```

### BuildOfflineBundle Job

```php
// app/Jobs/BuildOfflineBundle.php
class BuildOfflineBundle implements ShouldQueue {
  public function handle() {
    $comic = Comic::load('tribe', 'panels')->findOrFail($this->comicId);

    $bundleDir = storage_path("app/bundles");
    mkdir($bundleDir, 0755, true);

    $zip = new ZipArchive();
    $bundlePath = "{$bundleDir}/{$comic->id}_" . date('YmdHis') . '.ckb';
    $zip->open($bundlePath, ZipArchive::CREATE);

    // Metadata
    $metadata = [
      'id' => $comic->id,
      'title' => $comic->title,
      'tribe_id' => $comic->tribe_id,
      'age_min' => $comic->age_min,
      'age_max' => $comic->age_max,
      'panels_count' => $comic->panels->count(),
    ];
    $zip->addFromString('metadata.json', json_encode($metadata));

    // Panels
    foreach ($comic->panels as $panel) {
      if ($panel->image_path) {
        $content = Storage::disk('s3')->get($panel->image_path);
        $zip->addFromString("images/panel_{$panel->order_index}.jpg", $content);
      }
      if ($panel->audio_path) {
        $content = Storage::disk('s3')->get($panel->audio_path);
        $zip->addFromString("audio/panel_{$panel->order_index}.mp3", $content);
      }
    }

    $zip->close();

    // Upload to S3
    $s3Path = "bundles/{$comic->org_id}/{$comic->id}_" . date('YmdHis') . '.ckb';
    Storage::disk('s3')->put($s3Path, file_get_contents($bundlePath));

    // Update comic
    $bundleHash = hash_file('sha256', $bundlePath);
    $comic->update(['bundle_path' => $s3Path, 'bundle_hash' => $bundleHash]);

    unlink($bundlePath);
  }
}
```

---

## 7. OFFLINE-FIRST SYSTEM

### SQLite Local Schema (Expo App)

```sql
-- Offline content cache
CREATE TABLE content_manifest (
  id INTEGER PRIMARY KEY,
  comic_id INTEGER,
  tribe_id INTEGER,
  title TEXT,
  bundle_hash TEXT,
  downloaded INTEGER DEFAULT 0,
  bundle_path TEXT (local Expo FileSystem path),
  updated_at TEXT
);

-- Sync queue (events to send on reconnect)
CREATE TABLE sync_queue (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  event_type TEXT NOT NULL,
  payload TEXT NOT NULL (JSON string),
  idempotency_key TEXT UNIQUE,
  synced INTEGER DEFAULT 0,
  created_at INTEGER (Unix timestamp)
);

-- Child progress cache
CREATE TABLE child_progress_cache (
  child_id INTEGER,
  comic_id INTEGER,
  completed INTEGER DEFAULT 0,
  panels_seen TEXT (JSON array),
  PRIMARY KEY (child_id, comic_id)
);

-- Age profile rules cache
CREATE TABLE age_profiles_cache (
  id INTEGER PRIMARY KEY,
  age_min INTEGER,
  age_max INTEGER,
  ui_mode TEXT,
  rules TEXT (JSON),
  cached_at INTEGER
);

-- Tribes cache
CREATE TABLE tribes_cache (
  id INTEGER PRIMARY KEY,
  name TEXT,
  slug TEXT UNIQUE,
  language TEXT,
  region TEXT,
  greeting TEXT,
  color_hex TEXT,
  emoji_symbol TEXT,
  cached_at INTEGER
);
```

### Offline Mode: Full Functionality

1. App launches → SQLiteService initializes
2. Check NetInfo.isInternetReachable() → false
3. Load age profiles from age_profiles_cache → render age-adaptive UI
4. Load cached content_manifest → show available comics
5. Load bundle from Expo FileSystem → render panels, audio
6. User reads story → write progress to sync_queue
7. User completes story → INSERT into sync_queue with idempotency_key
8. No server calls attempted

### Online Mode: Real-Time Sync

1. NetInfo detects isInternetReachable = true
2. SyncService.drainSyncQueue() triggered
3. Read all unsynced events from sync_queue
4. Batch POST to /api/v1/sync with events
5. Server validates, deduplicates (idempotency_key), writes to progress_events
6. Mark sync_queue rows as synced = 1
7. Fetch updated content manifest → sync cache
8. Show badges/achievements (server-calculated)

### Conflict Resolution

- **Idempotency**: Each event has UUID idempotency_key
- **Deduplication**: Server checks if idempotency_key already exists
- **Last-Write-Wins**: If duplicate detected, use original server timestamp
- **Server Authority**: Badge awards calculated server-side, pushed to app
- **No Client Merge**: Client never merges conflicting progress; server is source of truth

### SyncService Implementation

```typescript
// Mobile app
class SyncService {
  async recordProgressEvent(childId: number, eventType: string, payload: any) {
    const isOnline = useOfflineStore.getState().isOnline;
    const idempotencyKey = `progress_${Date.now()}_${uuid()}`;

    if (isOnline) {
      try {
        await sanctumAPI.recordProgressEvent(childId, eventType, null, payload);
      } catch {
        // Fallback to queue on network error
        await sqliteService.addToSyncQueue(eventType, payload, idempotencyKey);
      }
    } else {
      // Queue offline
      await sqliteService.addToSyncQueue(eventType, payload, idempotencyKey);
    }
  }

  async drainSyncQueue() {
    const events = await sqliteService.getPendingSyncEvents();
    const response = await sanctumAPI.syncOfflineEvents(events);
    
    for (const event of events) {
      await sqliteService.markSyncEventAsProcessed(event.id);
    }
  }
}
```

---

## 8. EXPO REACT NATIVE APP

### Technology Stack

- **Framework**: Expo SDK 51+
- **Navigation**: Expo Router (file-based routing)
- **Language**: TypeScript
- **State**: Zustand (lightweight store)
- **Database**: expo-sqlite
- **Storage**: expo-secure-store (auth), expo-file-system (bundles)
- **Audio**: expo-av (read-aloud)
- **Network**: expo-network (NetInfo)
- **Styling**: Tailwind (NativeWind) or React Native Paper

### App Structure (Expo Router)

```
mobile/
├── app/
│   ├── (auth)/
│   │   ├── login.tsx
│   │   ├── register.tsx
│   │   └── password-reset.tsx
│   │
│   ├── (child)/
│   │   ├── _layout.tsx (bottom tab navigation)
│   │   ├── home.tsx (tribe picker)
│   │   ├── tribes/
│   │   │   ├── _layout.tsx
│   │   │   ├── [id].tsx (tribe detail + comics)
│   │   │   └── [id]/comic-[comicId].tsx (panel viewer)
│   │   ├── badges.tsx (achievement badge grid)
│   │   ├── profile.tsx (child profile / parent selection in kiosk)
│   │   └── offline.tsx (offline indicator + cached content)
│   │
│   ├── (parent)/
│   │   ├── _layout.tsx (parent tab bar)
│   │   ├── dashboard.tsx (children overview)
│   │   ├── child/[id]/progress.tsx (child progress detail)
│   │   ├── child/[id]/edit.tsx (edit child profile)
│   │   ├── downloads.tsx (manage bundle downloads)
│   │   └── settings.tsx (parent settings)
│   │
│   ├── (teacher)/
│   │   ├── _layout.tsx
│   │   ├── kiosk.tsx (kiosk mode launcher)
│   │   ├── kiosk/[classId].tsx (classroom roster)
│   │   └── roster.tsx (class management)
│   │
│   ├── _layout.tsx (root navigator)
│   └── index.tsx (splash / auth check)
│
├── services/
│   ├── SanctumAPI.ts
│   ├── SQLiteService.ts
│   ├── SyncService.ts
│   ├── BundleService.ts
│   └── AudioService.ts
│
├── store/
│   ├── authStore.ts
│   ├── childStore.ts
│   ├── offlineStore.ts
│   └── tribeStore.ts
│
├── components/
│   ├── TribeCard.tsx (age-adaptive card)
│   ├── ComicViewer.tsx (full-screen panel viewer)
│   ├── FlashCard.tsx (vocab card flip)
│   ├── AgeAdaptiveLayout.tsx (ui_mode wrapper)
│   ├── SyncIndicator.tsx (online/offline status)
│   ├── BadgeDisplay.tsx (celebration animation)
│   └── KioskMode.tsx (locked UI wrapper)
│
├── db/
│   ├── schema.ts (SQLite table definitions)
│   └── migrations.ts (schema versioning)
│
├── app.json (Expo config)
├── package.json
├── tsconfig.json
└── eas.json (EAS Build config)
```

### Key Screens & Flow

#### (1) Child Home — Tribe Picker
```tsx
// app/(child)/home.tsx
export default function TribesHome() {
  const [tribes, setTribes] = useState([]);
  const { ageProfile } = useChildStore();
  
  useEffect(() => {
    // Fetch from API if online, fallback to SQLite
    const loadTribes = async () => {
      if (useOfflineStore.getState().isOnline) {
        const res = await sanctumAPI.getTribes();
        setTribes(res.data);
        await sqliteService.cacheTribes(res.data);
      } else {
        const cached = await sqliteService.getTribesCache();
        setTribes(cached);
      }
    };
    loadTribes();
  }, []);

  const columns = ageProfile?.ui_mode === 'simple' ? 1 : (ageProfile?.ui_mode === 'guided' ? 2 : 3);

  return (
    <FlatList
      data={tribes}
      numColumns={columns}
      renderItem={({ item }) => <TribeCard tribe={item} />}
      keyExtractor={(t) => t.id.toString()}
    />
  );
}
```

#### (2) Comic Panel Viewer
```tsx
// Navigate to: router.push(`/tribes/${tribe.id}/comic-${comic.id}`)
export default function ComicViewer({ params }) {
  const [comic, setComic] = useState(null);
  const [currentPanel, setCurrentPanel] = useState(0);
  const [showAudio, setShowAudio] = useState(false);

  useEffect(() => {
    const loadComic = async () => {
      const comic = await sanctumAPI.getComicPanels(params.comicId);
      setComic(comic.data);
      
      // Record story_start
      await syncService.recordProgressEvent(
        childId,
        'story_start',
        params.comicId,
        { tribe_id: params.id }
      );
    };
    loadComic();
  }, []);

  const onPanelComplete = async () => {
    if (currentPanel === comic.panels.length - 1) {
      await syncService.recordProgressEvent(
        childId,
        'story_complete',
        params.comicId
      );
      // Show badge celebration
      showBadgeCelebration();
    } else {
      setCurrentPanel(c => c + 1);
    }
  };

  return (
    <ScrollView>
      <Image source={{ uri: comic.panels[currentPanel].image_path }} />
      <Button onPress={onPanelComplete} title="Next ➜" />
      {comic.panels[currentPanel].audio_path && (
        <Button onPress={() => playAudio(comic.panels[currentPanel].audio_path)} title="🔊 Read" />
      )}
    </ScrollView>
  );
}
```

#### (3) Parent Dashboard
```tsx
// app/(parent)/dashboard.tsx
export default function ParentDashboard() {
  const [children, setChildren] = useState([]);

  useEffect(() => {
    const loadChildren = async () => {
      const res = await sanctumAPI.getChildProfiles();
      setChildren(res.data);
    };
    loadChildren();
  }, []);

  return (
    <ScrollView>
      {children.map((child) => (
        <ChildProgressCard key={child.id} child={child} />
      ))}
      <Button title="➕ Add Child" onPress={() => router.push('/child/new')} />
    </ScrollView>
  );
}
```

#### (4) Kiosk Mode
```tsx
// app/(teacher)/kiosk/[classId].tsx
export default function KioskMode({ params }) {
  const [pupils, setPupils] = useState([]);
  const [locked, setLocked] = useState(true);
  const [pinEntry, setPinEntry] = useState('');

  const handlePupilSelect = (pupil) => {
    // Set AsyncStorage child context, navigate to home
    AsyncStorage.setItem('child_id', pupil.id.toString());
    router.push('/(child)/home');
  };

  const handleExit = (pin) => {
    if (pin === KIOSK_PIN) {
      // Exit kiosk, return to teacher interface
      router.push('/(teacher)/dashboard');
      setLocked(true);
    }
  };

  return (
    <KioskMode locked={locked}>
      <Grid>
        {pupils.map((p) => (
          <Pressable key={p.id} onPress={() => handlePupilSelect(p)}>
            <Avatar user={p} size="lg" />
            <Text>{p.name}</Text>
          </Pressable>
        ))}
      </Grid>
      
      {!locked && (
        <PinPad onSubmit={handleExit} />
      )}
    </KioskMode>
  );
}
```

### Zustand Stores

#### Auth Store
```typescript
export const useAuthStore = create<AuthState>((set) => ({
  token: null,
  user: null,
  isLoading: true,
  
  login: async (email: string, password: string) => {
    const { token, user } = await sanctumAPI.login(email, password);
    await SecureStore.setItemAsync('auth_token', token);
    set({ token, user, isLoading: false });
  },
  
  logout: async () => {
    await SecureStore.deleteItemAsync('auth_token');
    set({ token: null, user: null });
  },
  
  restoreToken: async () => {
    const token = await SecureStore.getItemAsync('auth_token');
    set({ token, isLoading: false });
  },
}));
```

#### Child Store (Runtime context)
```typescript
export const useChildStore = create((set) => ({
  childId: null,
  childName: '',
  ageProfile: null,
  
  setChild: async (childId: number) => {
    const profile = await sqliteService.getChildProfile(childId);
    const ageProfile = await sqliteService.getAgeProfile(profile.age_profile_id);
    set({ childId, childName: profile.name, ageProfile });
  },
}));
```

---

## 9. BLADE FRONTEND

### Key Blade Views

#### Layout (Base template with navigation)
```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>
  <title>@yield('title')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body>
  <nav class="navbar">
    <a href="/"><h1>🌍 Culture Kids</h1></a>
    
    @auth
      <span>{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
      <form method="POST" action="/logout">
        @csrf
        <button>Logout</button>
      </form>
    @endauth
  </nav>

  <main class="container">
    @if ($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @yield('content')
  </main>

  @livewireScripts
</body>
</html>
```

#### Super Admin Dashboard
```blade
{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Super Admin Dashboard')

@section('content')
<h1>⚡ Super Admin Dashboard — God Mode Active</h1>

<div class="metrics">
  <div class="card">
    <h3>Active Children</h3>
    <p class="metric">2,847</p>
    <span class="growth">+12% this week</span>
  </div>
  <div class="card">
    <h3>Organisations</h3>
    <p class="metric">34</p>
    <span class="growth">+3 this month</span>
  </div>
  <div class="card">
    <h3>Published Stories</h3>
    <p class="metric">183</p>
    <span class="caption">65 tribes</span>
  </div>
</div>

<div class="section">
  <h2>Organisations</h2>
  <table>
    <thead>
      <tr>
        <th>Name</th><th>Plan</th><th>Children</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($organisations as $org)
        <tr>
          <td>{{ $org->name }}</td>
          <td><span class="badge badge-{{ $org->plan }}">{{ ucfirst($org->plan) }}</span></td>
          <td>{{ $org->childProfiles->count() }}</td>
          <td><span class="status {{ $org->is_active ? 'active' : 'inactive' }}">{{ $org->is_active ? '●' : '○' }}</span></td>
          <td><a href="/admin/organisations/{{ $org->id }}/edit">Edit</a></td>
        </tr>
      @empty
        <tr><td colspan="5">No organisations.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="section">
  <h2>Module Control — Global Toggles</h2>
  <form>
    <label><input type="checkbox" checked /> 📖 Comics</label>
    <label><input type="checkbox" checked /> 🎵 Songs & Audio</label>
    <label><input type="checkbox" checked /> 🃏 Flashcards</label>
    <label><input type="checkbox" checked /> 📦 Offline Bundles</label>
    <label><input type="checkbox" checked /> 🎨 Theme Engine</label>
    <label><input type="checkbox" checked /> 🖥️ Kiosk Mode</label>
  </form>
</div>
@endsection
```

#### Teacher Dashboard
```blade
{{-- resources/views/teacher/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Teacher Dashboard')

@section('content')
<h1>Teacher Dashboard</h1>
<p>Manage classroom, schedule lessons, view progress</p>

<div class="actions">
  <a href="/teacher/lesson-plan/new" class="btn btn-primary">📅 Create Lesson Plan</a>
  <a href="/teacher/class/roster" class="btn btn-secondary">👥 View Class</a>
  <a href="/teacher/reports/export" class="btn btn-success">📊 Export Report</a>
  <a href="/teacher/kiosk" class="btn btn-warning">🖥️ Launch Kiosk</a>
</div>

<div class="metrics">
  <div class="card">
    <h3>Active Pupils Today</h3>
    <p class="metric">24</p>
  </div>
  <div class="card">
    <h3>Completion Rate</h3>
    <p class="metric">78%</p>
  </div>
  <div class="card">
    <h3>Badges This Week</h3>
    <p class="metric">56</p>
  </div>
</div>

<div class="section">
  <h2>Class Progress This Week</h2>
  <table>
    <thead>
      <tr>
        <th>Pupil</th><th>Stories</th><th>Badges</th><th>Time (min)</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($pupils as $pupil)
        <tr>
          <td>{{ $pupil->name }}</td>
          <td>{{ $pupil->progressEvents()->where('event_type', 'story_complete')->distinct('comic_id')->count() }}</td>
          <td>{{ $pupil->progressEvents()->where('event_type', 'badge_earned')->count() }}</td>
          <td>{{ $pupil->progressEvents()->where('created_at', '>=', now()->subDays(7))->sum('time_spent') ?? 0 }}</td>
          <td><span class="status {{ $pupil->onTrack() ? 'success' : 'warning' }}">{{ $pupil->onTrack() ? '✓ On track' : '⚠ Needs attention' }}</span></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="section">
  <h2>Weekly Chart</h2>
  <div id="chart"></div>
  <script>
    // Chart.js integration
    new Chart(document.getElementById('chart'), {
      type: 'bar',
      data: {
        labels: @json($weeklyLabels),
        datasets: [{
          label: 'Stories Completed',
          data: @json($weeklyCounts),
          backgroundColor: '#4f46e5'
        }]
      }
    });
  </script>
</div>
@endsection
```

#### Parent Dashboard
```blade
{{-- resources/views/parent/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Parent Dashboard')

@section('content')
<h1>Your Child's Learning Journey</h1>

<div class="sections">
  {{-- Child Profiles Grid --}}
  <div class="section">
    <div class="section-header">
      <h2>Your Children</h2>
      <a href="/parent/child-profile/new" class="btn btn-small">➕ Add Child</a>
    </div>

    <div class="grid grid-cols-2">
      @foreach ($childProfiles as $child)
        <div class="card child-card">
          <div class="card-header">
            <h3>{{ $child->name }}</h3>
            <p>Age {{ $child->getAge() }} · {{ $child->ageProfile->stage }}</p>
            <span class="avatar" style="font-size: 2em;">😄</span>
          </div>

          <div class="card-body">
            <div class="progress-item">
              <span>This Week: {{ $child->progressEvents()->where('event_type', 'story_complete')->whereDate('created_at', '>=', now()->subDays(7))->distinct('comic_id')->count() }} stories</span>
              <div class="progress-bar" style="width: 80%;"></div>
            </div>

            <div class="progress-item">
              <span>Badges: {{ $child->progressEvents()->where('event_type', 'badge_earned')->count() }}</span>
            </div>
          </div>

          <div class="card-footer">
            <a href="/parent/child/{{ $child->id }}/progress">View Progress →</a>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Content Downloads --}}
  <div class="section">
    <h2>Download Content for Offline Use</h2>
    @foreach ($availableBundles as $bundle)
      <div class="download-item">
        <div>
          <h4>📖 {{ $bundle->tribe->name }} Pack</h4>
          <p>{{ $bundle->comics->count() }} stories · {{ $bundle->size_mb }}MB</p>
        </div>
        @if ($bundle->isDownloaded)
          <span class="badge badge-success">✓ Downloaded</span>
        @else
          <button class="btn btn-small" onclick="downloadBundle({{ $bundle->id }})">↓ Download</button>
        @endif
      </div>
    @endforeach
  </div>
</div>
@endsection
```

### Livewire Components

```blade
{{-- resources/views/livewire/comic-upload.blade.php --}}
<div>
  <h2>Upload Comic</h2>

  <form wire:submit="save">
    <div class="form-group">
      <label>Title</label>
      <input wire:model="title" type="text" />
    </div>

    <div class="form-group">
      <label>Tribe</label>
      <select wire:model="tribe_id">
        @foreach ($tribes as $tribe)
          <option value="{{ $tribe->id }}">{{ $tribe->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>PDF File</label>
      <input wire:model="file" type="file" accept=".pdf" />
      @error('file') <span class="error">{{ $message }}</span> @enderror
    </div>

    <button type="submit" wire:loading.attr="disabled">
      <span wire:loading.remove>Upload</span>
      <span wire:loading>Processing...</span>
    </button>

    @if ($uploadSuccess)
      <div class="alert alert-success">
        Comic uploaded! Processing will extract panels in background.
      </div>
    @endif
  </form>

  {{-- PHP --}}
  // app/Livewire/ComicUpload.php
  public function save() {
    $this->validate([
      'title' => 'required|string|max:255',
      'tribe_id' => 'required|exists:tribes,id',
      'file' => 'required|file|mimes:pdf|max:50000',
    ]);

    $path = $this->file->store('comics/raw', 's3');

    $comic = Comic::create([
      'org_id' => auth()->user()->org_id,
      'tribe_id' => $this->tribe_id,
      'title' => $this->title,
      'status' => 'draft',
    ]);

    ProcessComicPDF::dispatch($path, $comic->id, auth()->user()->id);

    $this->uploadSuccess = true;
  }
}
```

---

## 10. MULTI-TENANCY DESIGN

### Org-ID Scoping

Every model fetches org-scoped by default using Eloquent Global Scopes:

```php
// In Model boot() method
protected static function booted() {
  if (auth()->check() && auth()->user()->role !== 'super_admin') {
    static::addGlobalScope('org', function (Builder $query) {
      $query->where('org_id', auth()->user()->org_id);
    });
  }
}
```

### Super Admin Bypass

For Super Admin, scoping is disabled:

```php
// In controller or middleware
if (auth()->user()->role === 'super_admin') {
  config(['scoping.org_id' => null]); // Disable org_id global scope
  Comic::withoutGlobalScope('org')->get(); // Explicit bypass
}
```

### User Impersonation (Audited)

Super Admin can impersonate any user for support:

```php
Route::post('/admin/users/{user}/impersonate', [SuperAdminController::class, 'impersonate'])
  ->middleware('role:super_admin');

// SuperAdminController
public function impersonate(User $user): JsonResponse {
  $token = $user->createToken('impersonation', $user->getSanctumAbilities());
  
  AuditLog::record(
    auth()->id(),
    'impersonate',
    null,
    $user->id,
    'User',
    auth()->id() // impersonator_id
  );

  return response()->json(['token' => $token->plainTextToken]);
}
```

### Module Access Control

Per-org module toggles:

```php
// organisations.modules = ["comics", "songs", "vocab", "offline"]

// Check in policy
public function view(User $user, Comic $comic): bool {
  $modules = $user->organisation->modules;
  if (!in_array('comics', $modules)) {
    return false;
  }
  return $user->org_id === $comic->org_id;
}

// Or in Blade
@if (in_array('comics', auth()->user()->organisation->modules))
  {{ render comic UI }}
@endif
```

---

## 11. ANALYTICS SYSTEM

### Data Collection Points

1. **Progress Events**: Every story start/complete/badge is idempotent and timestamped
2. **Sync Events**: Track offline-to-online reconciliations
3. **Audit Logs**: Record all administrative actions

### Report Queries

#### Organization Dashboard

```php
// Total active children (7 days)
SELECT COUNT(DISTINCT child_id) 
FROM progress_events 
WHERE created_at >= NOW() - INTERVAL 7 DAY;

// Stories completed per tribe
SELECT tribe_id, COUNT(*) as count 
FROM progress_events 
JOIN comics ON progress_events.comic_id = comics.id 
WHERE event_type = 'story_complete' 
GROUP BY tribe_id 
ORDER BY count DESC;

// Average time-on-task per child
SELECT child_id, AVG(TIMESTAMPDIFF(SECOND, created_at, synced_at)) / 60 as avg_minutes 
FROM progress_events 
WHERE event_type = 'story_complete' 
GROUP BY child_id;

// Badge awards trend
SELECT DATE(created_at) as date, COUNT(*) as badge_count 
FROM progress_events 
WHERE event_type = 'badge_earned' 
GROUP BY DATE(created_at) 
ORDER BY date DESC;
```

#### Teacher Dashboard

```php
// Class completion last 7 days
SELECT child_id, COUNT(DISTINCT comic_id) as stories_completed 
FROM progress_events 
WHERE event_type = 'story_complete' 
  AND child_id IN (SELECT id FROM child_profiles WHERE age_profile_id IN (SELECT id FROM age_profiles))
  AND created_at >= NOW() - INTERVAL 7 DAY 
GROUP BY child_id;

// Per-pupil badge count
SELECT child_id, COUNT(*) as badge_count 
FROM progress_events 
WHERE event_type = 'badge_earned' 
GROUP BY child_id 
ORDER BY badge_count DESC;
```

### Blade Dashboard Visualization

```blade
<div class="chart-container">
  <h3>Weekly Story Completions</h3>
  <div id="weekly-chart"></div>
  <script>
    const chartData = {!! json_encode($weeklyChartData) !!};
    new Chart(document.getElementById('weekly-chart'), {
      type: 'bar',
      data: chartData,
      options: { responsive: true }
    });
  </script>
</div>

<div class="analytics-grid">
  <div class="card">
    <h4>Active Children (7d)</h4>
    <p class="metric">{{ $activeChildren }}</p>
  </div>
  <div class="card">
    <h4>Avg Stories/Child</h4>
    <p class="metric">{{ number_format($avgStoriesPerChild, 1) }}</p>
  </div>
  <div class="card">
    <h4>Badges Earned</h4>
    <p class="metric">{{ $badgesEarned }}</p>
  </div>
</div>
```

### Caching Strategy

- Analytics queries cached in Redis (1hr TTL)
- Dashboard refreshes trigger cache invalidation
- Background job recalculates aggregates nightly

---

## 12. DEPLOYMENT ARCHITECTURE

### Backend (Laravel 11 on Ubuntu 22.04)

```bash
# VPS / Cloud Server (AWS EC2, Heroku, Railway, etc.)

# Install dependencies
apt update && apt install -y php8.3-fpm php8.3-cli php8.3-mysql \
  php8.3-gd php8.3-imagick redis-server nginx supervisor mysql-server

# Clone repository
git clone <repo> /var/www/culturekids

# Install PHP dependencies
cd /var/www/culturekids
composer install --optimize-autoloader --no-dev

# Setup .env
cp .env.example .env
php artisan key:generate

# Database setup
mysql -u root -p < database.sql  # Or run migrations
php artisan migrate --force

# Permissions
chown -R www-data:www-data /var/www/culturekids
chmod -R 775 storage bootstrap/cache

# Nginx config
# Copy culturekids.conf to /etc/nginx/sites-available/
# Enable: ln -s /etc/nginx/sites-available/culturekids /etc/nginx/sites-enabled/
# Test: nginx -t && systemctl restart nginx

# Supervisor (queue worker)
# Copy culturekids-worker.conf to /etc/supervisor/conf.d/
# Enable: supervisorctl reread && supervisorctl update

# SSL (Let's Encrypt)
apt install certbot python3-certbot-nginx
certbot certonly --nginx -d api.culturekids.app
# Auto-renew: systemctl enable certbot.timer
```

### Nginx Config

```nginx
# /etc/nginx/sites-available/culturekids
server {
  listen 443 ssl http2;
  server_name api.culturekids.app;
  ssl_certificate /etc/letsencrypt/live/api.culturekids.app/fullchain.pem;
  ssl_certificate_key /etc/letsencrypt/live/api.culturekids.app/privkey.pem;

  root /var/www/culturekids/public;
  index index.php;

  access_log /var/log/nginx/culturekids.access.log;
  error_log /var/log/nginx/culturekids.error.log;

  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
  }

  location ~ /\.(?!well-known).* {
    deny all;
  }
}

server {
  listen 80;
  server_name api.culturekids.app;
  return 301 https://$server_name$request_uri;
}
```

### Supervisor (Queue Worker)

```ini
# /etc/supervisor/conf.d/culturekids-worker.conf
[program:culturekids-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/culturekids/artisan queue:work redis --sleep=3 --tries=3 --timeout=0
autostart=true
autorestart=true
numprocs=2
stderr_logfile=/var/log/culturekids-worker.err.log
stdout_logfile=/var/log/culturekids-worker.out.log
user=www-data
```

### Mobile Build (Expo EAS)

```json
{
  "eas": {
    "build": {
      "production": {
        "android": {
          "buildType": "apk"
        },
        "ios": {
          "buildType": "archive"
        }
      }
    },
    "submit": {
      "production": {
        "android": {
          "serviceAccount": "google-services.json"
        },
        "ios": {
          "appleId": "..."
        }
      }
    }
  }
}
```

Build & deploy:
```bash
eas build --platform all --profile production
eas submit --platform all --latest
```

### S3 Storage (AWS)

```php
// config/filesystems.php
's3' => [
  'driver' => 's3',
  'key' => env('AWS_ACCESS_KEY_ID'),
  'secret' => env('AWS_SECRET_ACCESS_KEY'),
  'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
  'bucket' => env('AWS_BUCKET'),
  'url' => env('AWS_URL'),
  'endpoint' => env('AWS_ENDPOINT'),
],
```

### Monitoring & Logging

- **Logs**: Laravel Horizon (queue), Pail (real-time), CloudWatch (AWS)
- **Performance**: New Relic / DataDog APM
- **Uptime**: Uptime Robot monitoring
- **Alerts**: PagerDuty for critical issues

---

## 13. STEP-BY-STEP BUILD PLAN

### Timeline: 12 Weeks (3 Sprints × 4 Weeks)

---

### SPRINT 1: Weeks 1–4 — Laravel Foundation

#### Week 1: Setup & Database
- [ ] Initialize Laravel 11 project
- [ ] Run all migrations (organisations, users, tribes, comics, etc.)
- [ ] Create all Eloquent models
- [ ] Create DatabaseSeeder with demo data
- [ ] Setup Sanctum configuration

**Deliverable**: Clean database with 6+ tables, 5 demo users (super_admin, org_admin, cms_editor, teacher, parent)

#### Week 2: Authentication & RBAC
- [ ] Setup Spatie laravel-permission
- [ ] Create 9 roles + assignments
- [ ] Build AuthController (login, register, logout, user)
- [ ] Implement Sanctum token abilities
- [ ] Create SuperAdminMiddleware

**Deliverable**: Full auth flow. POST /api/v1/auth/login returns token + user.

#### Week 3: CRUD APIs
- [ ] TribeController (index, show, comics)
- [ ] ContentController (manifest, panels, bundles, age-profiles)
- [ ] ProgressController (recordEvent, childProgress, childProfiles)
- [ ] Write request validation classes
- [ ] Setup API versioning routes (/api/v1/...)

**Deliverable**: All content APIs functional. Expo can fetch tribes, content manifest, age profiles.

#### Week 4: Policies & Scoping
- [ ] ComicPolicy (view, update, delete with org-scoping)
- [ ] ChildProfilePolicy (parent/teacher scoped access)
- [ ] Implement Global Scopes on models
- [ ] Test multi-tenant isolation (org A can't see org B content)
- [ ] Write 20+ API tests (PHPUnit)

**Deliverable**: Org-scoped data verified. Super Admin can bypass. Tests passing.

---

### SPRINT 2: Weeks 5–8 — Expo App + Offline

#### Week 5: Expo Setup & Navigation
- [ ] Initialize Expo project (SDK 51+)
- [ ] Setup Expo Router with file-based routing
- [ ] Create app shell: (auth), (child), (parent), (teacher) layout routes
- [ ] Setup TypeScript, Zustand stores
- [ ] Create (child)/home screen (tribe picker)

**Deliverable**: App boots, logs in via API, navigates to tribe picker.

#### Week 6: SQLite & Offline
- [ ] Create expo-sqlite schema (content_manifest, sync_queue, progress_cache)
- [ ] Implement SQLiteService (initialize, createTables, read/write)
- [ ] Setup expo-secure-store for token persistence
- [ ] Implement offline detection (NetInfo)
- [ ] Build cache-first loading logic

**Deliverable**: App pre-caches content when online. Survives offline mode.

#### Week 7: Sync Service & Queue
- [ ] Build SyncService (recordProgressEvent, drainSyncQueue)
- [ ] Implement idempotency_key generation (UUID)
- [ ] Offline event queueing to sync_queue table
- [ ] Batch sync POST /api/v1/sync on reconnect
- [ ] Handle sync failures (retry logic)

**Deliverable**: Offline progress events sync to server on reconnect.

#### Week 8: Comic Viewer & Audio
- [ ] Build ComicViewer screen (full-screen panel swiper)
- [ ] Implement expo-av audio playback (read-aloud)
- [ ] Swipe navigation + next/prev buttons
- [ ] Age-adaptive UI wrapper (simple, guided, advanced, full)
- [ ] Badge celebration animation (Lottie)

**Deliverable**: Child can read full comic offline, progress syncs online.

---

### SPRINT 3: Weeks 9–12 — Admin, CMS, Launch

#### Week 9: CMS & PDF Processing
- [ ] Build ComicCMSController (upload, publish)
- [ ] Implement ProcessComicPDF Job (Imagick extraction)
- [ ] Laravel Horizon job monitoring
- [ ] Panel editor form (Alpine.js tagging)
- [ ] Livewire ComicUpload component

**Deliverable**: CMS Editor can upload PDF → extract panels → edit tags → publish.

#### Week 10: Bundle Builder & Admin Panel
- [ ] Implement BuildOfflineBundle Job (ZIP creation)
- [ ] S3 signed URL generation
- [ ] SuperAdminController (org management, user impersonation, age profiles)
- [ ] Build Blade admin dashboard
- [ ] Theme engine (org branding override)

**Deliverable**: Published comics build .ckb bundles. Super Admin full control.

#### Week 11: Teacher & Parent Dashboards
- [ ] Teacher dashboard (class roster, progress charts, lesson planner)
- [ ] Teacher kiosk mode launcher
- [ ] Parent dashboard (child profiles, progress tracking, downloads)
- [ ] Export PDF/Excel class reports (DomPDF/Excel)
- [ ] Child profile management forms

**Deliverable**: All three roles have fully functional Blade dashboards.

#### Week 12: Analytics, Testing, Launch
- [ ] Analytics Service (queries, aggregations)
- [ ] Dashboard Chart.js visualizations
- [ ] Full end-to-end test suite (Cypress)
- [ ] Performance testing (load testing)
- [ ] Seed production-like data (100+ comics, 1000+ children)
- [ ] EAS build for iOS/Android
- [ ] Deploy to production server

**Deliverable**: Production-ready system. Submitted to app stores. Documentation complete.

---

### Developer Workflow

#### Local Development

```bash
# Terminal 1: Laravel + Queue + Logs
composer run dev

# Terminal 2: Expo App
cd mobile && npm start

# Terminal 3: Database
mysql -u root -p culturekids
# Monitor changes: SELECT * FROM progress_events WHERE created_at >= NOW() - INTERVAL 1 MINUTE;
```

#### Testing Offline Mode

```typescript
// Expo: Toggle offline in dev menu
// → Disable internet, trigger storageEvent in sync_queue
// → Record progress
// → Re-enable internet
// → POST /api/v1/sync succeeds
// → progress_events created with idempotency_key matched
```

#### Git Workflow

```bash
git checkout -b feature/comics-cms
# ... develop ...
git commit -m "feat: comic upload & panel extraction"
git push origin feature/comics-cms
# Open PR → code review → merge
```

---

## ENVIRONMENT VARIABLES

### Backend (.env)

```env
APP_NAME="Paulette Culture Kids"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.culturekids.app

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=culturekids
DB_USERNAME=root
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=...
MAIL_PASSWORD=...

AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=culturekids-content
AWS_URL=https://s3.amazonaws.com/culturekids-content

SANCTUM_STATEFUL_DOMAINS=culturekids.app,api.culturekids.app
SANCTUM_GUARD=api

APP_CIPHER=AES-256-CBC
APP_KEY=base64:...
```

### Mobile (Expo)

```bash
API_BASE_URL=https://api.culturekids.app/api/v1
ENABLE_KIOSK_MODE=true
KIOSK_PIN=1234
SENTRY_DSN=https://...@sentry.io/...
```

---

## SUMMARY

**Paulette Culture Kids** is a production-ready, offline-first cultural learning platform for African children ages 2–6.

### Key Features Implemented
✅ Laravel 11 REST API with Sanctum tokenization  
✅ Multi-tenant SaaS (org_id scoping, God-Mode Super Admin)  
✅ 9-role RBAC (super_admin, org_admin, cms_editor, teacher, parent, child, etc.)  
✅ Offline-first Expo React Native app (SQLite sync queue, 100% functional offline)  
✅ PDF-to-Comic pipeline (auto-extract panels, queue jobs, bundle builder)  
✅ Age-adaptive UI (4 UI modes: simple/guided/advanced/full)  
✅ Idempotent progress sync (no duplicates, server-authoritative badges)  
✅ Blade dashboards (admin, teacher, parent, public website)  
✅ Analytics (weekly charts, per-org/per-teacher/per-family views)  
✅ Kiosk mode (locked classroom tablets with PIN exit)  

### Technology Stack
- **Backend**: Laravel 11 + MySQL 8 + Redis
- **API**: Sanctum (token auth) + REST + Versioning (/api/v1)
- **Mobile**: Expo SDK 51 + React Native + TypeScript + Zustand + SQLite
- **Web**: Blade views + Livewire + Alpine.js + Tailwind
- **Queue**: Redis + Laravel Horizon (PDF processing, bundle building)
- **Storage**: AWS S3 (comics, audio, bundles)
- **Deployment**: Ubuntu + Nginx + PHP-FPM + Supervisor

### Next Steps
1. Clone the repository
2. Follow Sprint 1–3 build plan (12 weeks)
3. Deploy to production (Ubuntu VPS + AWS S3)
4. Submit to iOS App Store & Google Play
5. Monitor via Horizon, New Relic, CloudWatch

---

**Made with ❤️ for African children learning their heritage through stories.**

🌍 Paulette Culture Kids · Preserving Ugandan & African cultural heritage · Ages 2–6

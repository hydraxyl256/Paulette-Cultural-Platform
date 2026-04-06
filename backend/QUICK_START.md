# QUICK START — Paulette Culture Kids Backend

**Get the backend running in 10 minutes.**

---

## Prerequisites

- PHP 8.3+ (with extensions: fpm, cli, mysql, gd, imagick)
- MySQL 8.0+
- Redis server
- Composer 2.6+
- Git

---

## Installation

### 1. Clone & Setup

```bash
cd culturekids-project/backend
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Database

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE culturekids CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Migrate & seed
php artisan migrate --seed
```

### 3. Start Services

```bash
# Terminal 1: Laravel dev server
php artisan serve
# → http://localhost:8000

# Terminal 2: Queue worker
php artisan queue:listen

# Terminal 3: Redis (if not running as service)
redis-server
```

### 4. Verify API

```bash
# Login as parent
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"parent@culturekids.app","password":"password"}'

# Response includes token
# {
#   "token": "1|abc123xyz...",
#   "user": {"id": 5, "name": "Parent", "role": "parent", ...}
# }
```

---

## Key Directories

```
app/
├── Http/Controllers/Api/        ← API endpoint handlers
├── Http/Controllers/Admin/       ← Super Admin god mode
├── Http/Middleware/             ← Auth guards (SuperAdmin, OrgScoping)
├── Models/                       ← Eloquent ORM
├── Jobs/                         ← Queue jobs (PDF, Bundle)
└── Policies/                     ← Authorization rules

database/
├── migrations/                   ← 12 schema files
└── seeders/DatabaseSeeder.php   ← Demo data

routes/
├── api.php                       ← /api/v1 endpoints
└── web.php                       ← Blade routes (TODO)

resources/views/
├── layouts/app.blade.php        ← Base template
├── admin/dashboard.blade.php    ← Super Admin
├── teacher/dashboard.blade.php  ← Teacher
└── parent/dashboard.blade.php   ← Parent
```

---

## Demo Users

All have password: `password`

| Email | Role | Org | Use Case |
|-------|------|-----|----------|
| super-admin@culturekids.app | super_admin | Default | Full system control |
| org-admin@culturekids.app | org_admin | Org 2 | Manage org |
| cms-editor@culturekids.app | cms_editor | Org 2 | Upload/edit comics |
| teacher@culturekids.app | teacher | Org 3 | Class management |
| parent@culturekids.app | parent | Org 1 | Child tracking |

---

## API Endpoints (Sample)

### Auth
```bash
# Login (public)
POST /api/v1/auth/login
{ "email": "parent@culturekids.app", "password": "password" }

# Logout (token required)
POST /api/v1/auth/logout
Authorization: Bearer {token}

# Get current user
GET /api/v1/auth/user
Authorization: Bearer {token}
```

### Content
```bash
# Get all tribes
GET /api/v1/tribes
Authorization: Bearer {token}

# Get tribe detail with comics
GET /api/v1/tribes/1
Authorization: Bearer {token}

# Get age profiles (cached in mobile)
GET /api/v1/age-profiles
Authorization: Bearer {token}

# Get content manifest (org-scoped)
GET /api/v1/content/manifest
Authorization: Bearer {token}
```

### Progress & Sync
```bash
# Record single event
POST /api/v1/progress/events
Authorization: Bearer {token}
{
  "child_id": 12,
  "event_type": "story_complete",
  "comic_id": 8,
  "payload": { "time_spent": 325 }
}

# Batch sync offline events (idempotent)
POST /api/v1/sync
Authorization: Bearer {token}
{
  "events": [
    {
      "event_type": "story_complete",
      "child_id": 12,
      "comic_id": 8,
      "idempotency_key": "story_complete_12_8_001",
      "payload": { "time_spent": 325 }
    }
  ]
}
```

---

## Database Inspect

```bash
# Connect to MySQL
mysql -u root -p culturekids

# View tables
SHOW TABLES;

# View organisations
SELECT * FROM organisations;

# View users
SELECT id, name, email, org_id, role FROM users;

# View child profiles
SELECT * FROM child_profiles;

# View progress events (latest 10)
SELECT * FROM progress_events ORDER BY created_at DESC LIMIT 10;

# View sync state
SELECT * FROM sync_events WHERE processed = 0;

# View audit logs (Super Admin actions)
SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 10;
```

---

## Queue Jobs

### Process Comic PDF

Triggered when CMS editor uploads PDF → extracts pages via Imagick.

```bash
# Monitor queue
php artisan horizon

# Or in logs
tail -f storage/logs/laravel.log | grep "ProcessComicPDF"
```

### Build Offline Bundle

Triggered when comic published → creates signed .ckb ZIP.

```bash
# Manually dispatch (for testing)
php artisan tinker
>>> ProcessComicPDF::dispatch('comics/raw/test.pdf', 1, 1);
>>> BuildOfflineBundle::dispatch(1);
>>> exit
```

---

## Common Tasks

### Create Test Child Profile

```php
php artisan tinker

// Get or create parent
$parent = User::where('email', 'parent@culturekids.app')->firstOrFail();

// Get age profile (3-4 years)
$ageProfile = AgeProfile::where('stage', 'Growing Learner')->firstOrFail();

// Create child
$child = ChildProfile::create([
  'parent_user_id' => $parent->id,
  'org_id' => $parent->org_id,
  'age_profile_id' => $ageProfile->id,
  'name' => 'Test Child',
  'date_of_birth' => now()->subYears(3)->toDateString(),
  'avatar' => 'emoji',
]);

echo "Child ID: " . $child->id;
```

### Record Progress Manually

```php
php artisan tinker

$child = ChildProfile::find(12);
$comic = Comic::where('status', 'published')->first();

ProgressEvent::create([
  'child_id' => $child->id,
  'comic_id' => $comic->id,
  'event_type' => 'story_complete',
  'idempotency_key' => 'test_' . uuid(),
  'payload' => ['time_spent' => 325],
  'synced_at' => now(),
]);
```

### View Sanctum Abilities

```php
php artisan tinker

$user = User::find(5); // parent
dd($user->getSanctumAbilities());
// → ["progress:record", "content:read"]

$admin = User::find(1); // super_admin
dd($admin->getSanctumAbilities());
// → ["*"]
```

---

## Troubleshooting

### "SQLSTATE: Access denied for user"
```bash
# Check MySQL is running
mysql -u root -p -e "SELECT 1"

# Verify .env DB credentials
cat .env | grep DB_
```

### Queue job not processing
```bash
# Check Redis
redis-cli ping
# → PONG

# Restart queue worker
pkill php # Kill all PHP
php artisan queue:listen
```

### 419 Token Mismatch (Session)
```bash
# Clear session cache
php artisan cache:clear
php artisan session:clear
```

### Imagick not found
```bash
# Install ImageMagick
apt-get install imagemagick php8.3-imagick

# Or for macOS
brew install imagemagick
```

---

## Development Checklist

- [ ] Database migrated & seeded
- [ ] Dev server running on :8000
- [ ] Queue worker active
- [ ] Redis connected
- [ ] All 5 demo users created
- [ ] Able to login via /api/v1/auth/login
- [ ] Tribes & comics visible via API
- [ ] Child profiles readable
- [ ] Queue jobs processing (horizon/logs)
- [ ] S3 storage configured (or local disk)

---

## Next Steps

1. **Blade Dashboards**: Finish routes/web.php + controller page serving
2. **Livewire Components**: Build ComicUpload, PanelEditor, ProgressChart
3. **Expo App**: Start mobile app integration with this API
4. **Testing**: Write 100+ PHPUnit tests for API reliability
5. **Deployment**: Setup AWS S3, Nginx, Supervisor, Let's Encrypt

See `COMPLETE_SYSTEM_SPECIFICATION.md` for full architecture & build plan.

---

**Happy coding! 🚀**

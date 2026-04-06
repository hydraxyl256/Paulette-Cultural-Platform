# Paulette Culture Kids — API Testing Guide

## Database Status

✅ **All migrations passed successfully**

- 12 custom migrations (organisations, tribes, comics, progress_events, etc.)
- Spatie permission tables (roles, permissions, model_has_roles)
- Sanctum personal_access_tokens table

✅ **Seeding complete with 5 demo users**

- Super Admin: `admin@culturekids.app` / `password`
- Org Admin: `org-admin@culturekids.app` / `password`
- CMS Editor: `cms-editor@culturekids.app` / `password`
- Teacher: `teacher@culturekids.app` / `password`
- Parent: `parent@culturekids.app` / `password`

---

## Startup

```bash
# Terminal 1: Start Laravel dev server
cd backend
php artisan serve --port=8000
# → http://localhost:8000

# Terminal 2: Start queue worker (for PDF/Bundle jobs)
php artisan queue:listen
```

---

## API Endpoints

All endpoints prefixed with: `http://localhost:8000/api/v1`

### Authentication (Public)

#### 1. Login
```bash
POST /auth/login
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
    "role": "parent",
    "created_at": "2026-04-01T18:03:05Z",
    "updated_at": "2026-04-01T18:03:05Z"
  }
}
```

Save the token for subsequent requests:
```bash
TOKEN="1|abc123xyz..."
```

#### 2. Register
```bash
POST /auth/register
Content-Type: application/json

{
  "name": "New Parent",
  "email": "newparent@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response 201:
{
  "message": "User registered successfully",
  "token": "2|def456uvw...",
  "user": {...}
}
```

---

### Protected Endpoints (Require Token)

Use header: `Authorization: Bearer {TOKEN}`

#### 3. Get Current User
```bash
GET /auth/user
Authorization: Bearer 1|abc123xyz...

Response 200:
{
  "user": {...},
  "abilities": ["child:manage", "progress:view:own"]
}
```

#### 4. Logout
```bash
POST /auth/logout
Authorization: Bearer 1|abc123xyz...

Response 200:
{
  "message": "Logged out successfully"
}
```

---

### Content & Tribes

#### 5. Get All Tribes
```bash
GET /tribes
Authorization: Bearer 1|abc123xyz...

Response 200:
[
  {
    "id": 1,
    "name": "Buganda",
    "slug": "buganda",
    "language": "Luganda",
    "region": "Central Uganda",
    "greeting": "Ssalaam",
    "phonetic": "sah-LAHM",
    "color_hex": "#FF6B35",
    "emoji_symbol": "🥁",
    "is_active": true,
    "created_at": "2026-04-01T...",
    "updated_at": "2026-04-01T..."
  },
  {...more tribes...}
]
```

#### 6. Get Specific Tribe
```bash
GET /tribes/1
Authorization: Bearer 1|abc123xyz...

Response 200:
{
  "id": 1,
  "name": "Buganda",
  "slug": "buganda",
  ...
}
```

#### 7. Get Tribe's Comics
```bash
GET /tribes/1/comics
Authorization: Bearer 1|abc123xyz...

Query params (optional):
  - age_profile_id=2  (filter by age profile)

Response 200:
[
  {
    "id": 1,
    "org_id": 1,
    "tribe_id": 1,
    "title": "The Clever Hare of Buganda",
    "age_min": 3,
    "age_max": 5,
    "status": "published",
    "cover_image_path": "s3://...",
    "bundle_path": "s3://bundles/...",
    "bundle_hash": "abc123def...",
    "created_at": "2026-04-01T...",
    "updated_at": "2026-04-01T...",
    "panels": [
      {
        "id": 1,
        "order_index": 0,
        "image_path": "s3://comics/panels/1/panel_0.jpg",
        "vocab_tags": [...],
        "audio_path": "s3://audio/panel_0.mp3"
      }
    ]
  }
]
```

#### 8. Get Age Profiles
```bash
GET /age-profiles
Authorization: Bearer 1|abc123xyz...

Response 200:
[
  {
    "id": 1,
    "age_min": 2,
    "age_max": 3,
    "stage": "Early Explorer",
    "ui_mode": "simple",
    "difficulty_ceiling": 1,
    "rules": {
      "max_cards": 3,
      "audio_only": true,
      "no_text": true
    },
    "created_at": "2026-04-01T...",
    "updated_at": "2026-04-01T..."
  },
  {...more age profiles...}
]
```

#### 9. Get Content Manifest (Org-Scoped)
```bash
GET /content/manifest
Authorization: Bearer 1|abc123xyz...

Response 200:
[
  {
    "id": 1,
    "org_id": 1,
    "title": "The Clever Hare of Buganda",
    "bundle_hash": "abc123def...",
    "bundle_path": "s3://bundles/1/1_20260401.ckb",
    "tribe_id": 1,
    "tribe_name": "Buganda"
  },
  {...more comics...}
]
```

#### 10. Get Comic Panels
```bash
GET /comics/1/panels
Authorization: Bearer 1|abc123xyz...

Response 200:
[
  {
    "id": 1,
    "comic_id": 1,
    "order_index": 0,
    "image_path": "s3://comics/panels/1/panel_0.jpg",
    "vocab_tags": {
      "luganda": ["word1", "word2"],
      "english": ["word1 translation", "word2 translation"]
    },
    "audio_path": "s3://audio/panel_0.mp3",
    "created_at": "2026-04-01T..."
  }
]
```

#### 11. Get Bundle Download URL
```bash
GET /bundles/1
Authorization: Bearer 1|abc123xyz...

Response 200:
{
  "url": "https://s3.amazonaws.com/culturekids-content/bundles/1/1_20260401.ckb?X-Amz-Signature=...",
  "size_mb": 45,
  "hash": "abc123def...",
  "expires_at": "2026-04-02T18:03:05Z"
}
```

---

### Progress & Sync

#### 12. Record Progress Event
```bash
POST /progress/events
Authorization: Bearer 1|abc123xyz...
Content-Type: application/json

{
  "child_id": 1,
  "event_type": "story_complete",
  "comic_id": 1,
  "payload": {
    "time_spent": 325
  }
}

Response 201:
{
  "id": 1,
  "child_id": 1,
  "comic_id": 1,
  "event_type": "story_complete",
  "idempotency_key": "progress_1711992185000_7a3b8c9d",
  "payload": {"time_spent": 325},
  "synced_at": "2026-04-01T18:05:00Z",
  "created_at": "2026-04-01T18:05:00Z"
}
```

#### 13. Batch Sync Offline Events
```bash
POST /sync
Authorization: Bearer 1|abc123xyz...
Content-Type: application/json

{
  "events": [
    {
      "event_type": "story_complete",
      "child_id": 1,
      "comic_id": 1,
      "idempotency_key": "story_complete_1_1_001",
      "payload": {"time_spent": 325}
    },
    {
      "event_type": "story_complete",
      "child_id": 1,
      "comic_id": 2,
      "idempotency_key": "story_complete_1_2_001",
      "payload": {"time_spent": 410}
    }
  ]
}

Response 200:
{
  "message": "Sync completed",
  "events_processed": 2,
  "events": [
    {
      "id": 2,
      "event_type": "story_complete",
      "synced_at": "2026-04-01T18:06:00Z"
    },
    {
      "id": 3,
      "event_type": "story_complete",
      "synced_at": "2026-04-01T18:06:00Z"
    }
  ]
}
```

#### 14. Get Child Progress
```bash
GET /progress/child/1
Authorization: Bearer 1|abc123xyz...

Response 200:
{
  "child": {
    "id": 1,
    "name": "Naluwooza",
    "parent_user_id": 5,
    "age_profile_id": 2
  },
  "stories_completed": 5,
  "badges_earned": 1,
  "recent_events": [
    {
      "id": 1,
      "event_type": "story_complete",
      "comic_id": 1,
      "created_at": "2026-04-01T18:05:00Z"
    }
  ]
}
```

#### 15. Get Child Profiles (Parent's Children)
```bash
GET /child-profiles
Authorization: Bearer 1|abc123xyz...

Response 200:
[
  {
    "id": 1,
    "parent_user_id": 5,
    "org_id": 1,
    "age_profile_id": 2,
    "name": "Naluwooza",
    "date_of_birth": "2022-10-01",
    "avatar": "avatar_1.png",
    "preferred_tribe_ids": [1, 2],
    "created_at": "2026-04-01T18:03:05Z"
  }
]
```

#### 16. Create Child Profile
```bash
POST /child-profiles
Authorization: Bearer 1|abc123xyz...
Content-Type: application/json

{
  "name": "New Child",
  "date_of_birth": "2023-06-15",
  "avatar": "avatar_3.png",
  "preferred_tribe_ids": [1, 3]
}

Response 201:
{
  "id": 3,
  "parent_user_id": 5,
  "org_id": 1,
  "age_profile_id": 1,  // Auto-calculated from date_of_birth
  "name": "New Child",
  "date_of_birth": "2023-06-15",
  "avatar": "avatar_3.png",
  "preferred_tribe_ids": [1, 3],
  "created_at": "2026-04-01T18:07:00Z"
}
```

---

### CMS Endpoints (org_admin + cms_editor)

Login as cms_editor@culturekids.app to access these.

#### 17. Upload Comic PDF
```bash
POST /cms/comics/upload
Authorization: Bearer {cms_editor_token}
Content-Type: multipart/form-data

Form fields:
  - title: "The Clever Hare"
  - tribe_id: "1"
  - age_min: "3"
  - age_max: "5"
  - pdf_file: <binary PDF file>

Response 201:
{
  "message": "Comic uploaded. Processing started.",
  "comic": {
    "id": 2,
    "org_id": 2,
    "tribe_id": 1,
    "title": "The Clever Hare",
    "status": "draft",
    "created_at": "2026-04-01T18:10:00Z"
  }
}
```

Background: ProcessComicPDF job will extract PDF pages.

#### 18. Publish Comic
```bash
PUT /cms/comics/1/publish
Authorization: Bearer {cms_editor_token}
Content-Type: application/json

{
  "status": "published"
}

Response 200:
{
  "message": "Comic published. Bundle building started.",
  "comic": {
    "id": 1,
    "status": "published",
    "bundle_path": "s3://bundles/2/1_20260401.ckb",
    "bundle_hash": "abc123def...",
    "updated_at": "2026-04-01T18:12:00Z"
  }
}
```

Background: BuildOfflineBundle job will create ZIP.

---

### Super Admin Endpoints

Login as admin@culturekids.app to access these.

#### 19. Get Super Admin Dashboard
```bash
GET /admin/dashboard
Authorization: Bearer {super_admin_token}

Response 200:
{
  "active_children": 2847,
  "organisations": 34,
  "published_comics": 183,
  "badges_earned": 9240,
  "weekly_growth": 12.5
}
```

#### 20. List All Organisations
```bash
GET /admin/organisations
Authorization: Bearer {super_admin_token}

Response 200:
[
  {
    "id": 1,
    "name": "Default Organisation",
    "slug": "default",
    "plan": "free",
    "modules": ["comics", "songs", "vocab", "offline"],
    "is_active": true,
    "users_count": 1,
    "children_count": 2,
    "stories_count": 0
  }
]
```

#### 21. Create Organisation
```bash
POST /admin/organisations
Authorization: Bearer {super_admin_token}
Content-Type: application/json

{
  "name": "New Org",
  "slug": "new-org",
  "plan": "school",
  "modules": ["comics", "kiosk", "offline"],
  "is_active": true
}

Response 201:
{
  "id": 4,
  "name": "New Org",
  ...
}
```

#### 22. Update Org Modules
```bash
PUT /admin/organisations/1/modules
Authorization: Bearer {super_admin_token}
Content-Type: application/json

{
  "modules": ["comics", "songs", "vocab", "offline", "kiosk", "theme"]
}

Response 200:
{
  "message": "Modules updated",
  "modules": [...]
}
```

#### 23. Update Age Profile
```bash
PUT /admin/age-profiles/1
Authorization: Bearer {super_admin_token}
Content-Type: application/json

{
  "stage": "Early Explorer v2",
  "ui_mode": "simple",
  "difficulty_ceiling": 2,
  "rules": {
    "max_cards": 4,
    "audio_only": true,
    "no_text": true
  }
}

Response 200:
{
  "message": "Age profile updated",
  "age_profile": {...}
}
```

#### 24. Update Theme
```bash
PUT /admin/themes/1
Authorization: Bearer {super_admin_token}
Content-Type: application/json

{
  "colors": {
    "primary": "#FF6B35",
    "secondary": "#004E89",
    "accent": "#F77F00"
  },
  "typography": {
    "font_family": "Poppins",
    "heading_size": 24,
    "body_size": 16
  },
  "logo_url": "s3://...",
  "custom_properties": {
    "app_name": "Culture Kids"
  }
}

Response 200:
{
  "message": "Theme updated",
  "theme": {...}
}
```

#### 25. Impersonate User
```bash
POST /admin/users/5/impersonate
Authorization: Bearer {super_admin_token}
Content-Type: application/json

{}

Response 200:
{
  "message": "Impersonation token issued",
  "token": "3|ghi789stu...",
  "user": {...}
}
```

---

## Database Queries

Connect to SQLite database:
```bash
sqlite3 database/database.sqlite
```

### Check Tables
```sql
.tables

-- Output:
-- audit_logs              child_profiles          migrations              tribespaces
-- cache                   comics                  model_has_permissions   users
-- failed_jobs             comic_panels            model_has_roles
-- jobs                    lessons                 organisations
-- personal_access_tokens  permissions             tribes
-- age_profiles            progress_events        roles
```

### View Users
```sql
SELECT id, name, email, org_id, role FROM users;
```

### View Tribes
```sql
SELECT id, name, slug, language, region FROM tribes;
```

### View Age Profiles
```sql
SELECT id, age_min, age_max, stage, ui_mode FROM age_profiles;
```

### View Child Profiles
```sql
SELECT id, parent_user_id, org_id, name, date_of_birth FROM child_profiles;
```

### View Progress Events
```sql
SELECT id, child_id, event_type, synced_at FROM progress_events ORDER BY created_at DESC;
```

### View Roles
```sql
SELECT name, guard_name FROM roles;
```

---

## Testing Checklist

- [ ] **Auth**: Login as parent → receive token
- [ ] **Content**: GET /tribes → see 6 tribes
- [ ] **Manifest**: GET /content/manifest → org-scoped comics
- [ ] **Age Profiles**: GET /age-profiles → 4 profiles
- [ ] **Child Progress**: GET /progress/child/1 → stories_completed count
- [ ] **Sync**: POST /sync → batch process 2 events with idempotency
- [ ] **CMS**: POST /cms/comics/upload → starts ProcessComicPDF job
- [ ] **Admin**: GET /admin/dashboard → super_admin global stats
- [ ] **Impersonate**: POST /admin/users/5/impersonate → receive impersonator token

---

## Troubleshooting

### 401 Unauthorized
- Verify `Authorization: Bearer {TOKEN}` header is included
- Check token is still valid (not expired)
- Re-login if expired

### 403 Forbidden
- Verify user has required role/permission
- For Super Admin routes, user must be super_admin role
- For CMS routes, user must have content:edit ability

### 404 Not Found
- Verify resource ID exists (e.g., tribe ID 1)
- Check route path is correct

### 422 Unprocessable Entity
- Validation failed. Check request body matches schema
- Email must be unique for registration
- Dates must be valid format (YYYY-MM-DD)

### Spatie Roles Not Working
- Ensure roles are created: `Role::create(['name' => 'parent'])`
- Verify user has role assigned: `$user->assignRole('parent')`
- Check in `roles` table via SQLite

---

## Next Steps

1. **Test all endpoints manually** using cURL or Postman
2. **Setup Expo app** to consume /api/v1 endpoints
3. **Create Blade dashboards** for web UI
4. **Deploy to production** (follow COMPLETE_SYSTEM_SPECIFICATION.md)

---

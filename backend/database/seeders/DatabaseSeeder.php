<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Tribe;
use App\Models\AgeProfile;
use App\Models\ChildProfile;
use App\Models\Comic;
use App\Models\ProgressEvent;
use App\Models\SyncEvent;
use App\Models\AuditLog;
use App\Models\ModuleFlag;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ──────────────────────────────────────────────────────
        $roles = ['super_admin', 'org_admin', 'cms_editor', 'teacher', 'parent', 'child'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── Organisations ──────────────────────────────────────────────
        $orgData = [
            ['name' => 'Culture Kids Global', 'slug' => 'culturekids-global', 'plan' => 'enterprise', 'modules' => ['comics', 'songs', 'vocab', 'offline', 'kiosk', 'theme'], 'is_active' => true],
            ['name' => 'Naluwooza Creative Space', 'slug' => 'naluwooza-creative', 'plan' => 'enterprise', 'modules' => ['comics', 'songs', 'vocab', 'offline', 'kiosk', 'theme'], 'is_active' => true],
            ['name' => 'Uganda Schools Pilot', 'slug' => 'uganda-schools-pilot', 'plan' => 'school', 'modules' => ['comics', 'songs', 'vocab', 'offline', 'kiosk'], 'is_active' => true],
            ['name' => 'Accra Heritage Club', 'slug' => 'accra-heritage-club', 'plan' => 'school', 'modules' => ['comics', 'songs', 'vocab', 'offline'], 'is_active' => true],
            ['name' => 'Lagos Cultural Institute', 'slug' => 'lagos-cultural', 'plan' => 'enterprise', 'modules' => ['comics', 'songs', 'vocab', 'offline', 'kiosk', 'theme'], 'is_active' => true],
            ['name' => 'Nairobi Learning Hub', 'slug' => 'nairobi-hub', 'plan' => 'school', 'modules' => ['comics', 'songs', 'vocab'], 'is_active' => true],
            ['name' => 'Heritage University', 'slug' => 'heritage-uni', 'plan' => 'free', 'modules' => ['comics', 'songs'], 'is_active' => true],
            ['name' => 'Global Tribes Ent.', 'slug' => 'global-tribes', 'plan' => 'enterprise', 'modules' => ['comics', 'songs', 'vocab', 'offline', 'kiosk', 'theme'], 'is_active' => true],
            ['name' => 'EduAfrica Partners', 'slug' => 'eduafrica', 'plan' => 'school', 'modules' => ['comics', 'songs', 'vocab', 'offline'], 'is_active' => false],
            ['name' => 'Cape Town Academy', 'slug' => 'cape-town-academy', 'plan' => 'free', 'modules' => ['comics'], 'is_active' => true],
        ];

        $orgs = [];
        foreach ($orgData as $idx => $data) {
            $org = Organisation::firstOrCreate(['slug' => $data['slug']], array_merge($data, [
                'created_at' => now()->subDays(rand(5, 180))->subHours(rand(0, 23)),
            ]));
            $orgs[] = $org;
        }

        // ── Age Profiles ───────────────────────────────────────────────
        $ageProfiles = [
            ['age_min' => 0, 'age_max' => 3, 'stage' => 'Early Years', 'ui_mode' => 'simple', 'difficulty_ceiling' => 1, 'rules' => ['max_cards' => 3, 'audio_only' => true, 'no_text' => true]],
            ['age_min' => 4, 'age_max' => 7, 'stage' => 'Foundation', 'ui_mode' => 'guided', 'difficulty_ceiling' => 2, 'rules' => ['max_cards' => 5, 'audio_first' => true, 'simple_labels' => true]],
            ['age_min' => 8, 'age_max' => 12, 'stage' => 'Active Learning', 'ui_mode' => 'advanced', 'difficulty_ceiling' => 3, 'rules' => ['max_cards' => 8, 'word_tracing' => true, 'badges' => true]],
            ['age_min' => 13, 'age_max' => 17, 'stage' => 'Maturation', 'ui_mode' => 'full', 'difficulty_ceiling' => 5, 'rules' => ['full_text' => true, 'cross_tribe_compare' => true, 'advanced_quizzes' => true]],
        ];

        $ages = [];
        foreach ($ageProfiles as $ap) {
            $ages[] = AgeProfile::firstOrCreate(['age_min' => $ap['age_min'], 'age_max' => $ap['age_max']], $ap);
        }

        // ── Tribes ─────────────────────────────────────────────────────
        $tribeData = [
            ['name' => 'Buganda', 'slug' => 'buganda', 'language' => 'Luganda', 'region' => 'Central Uganda', 'greeting' => 'Ssalaam', 'phonetic' => 'sah-LAHM', 'color_hex' => '#FF6B35', 'emoji_symbol' => '🥁'],
            ['name' => 'Acholi', 'slug' => 'acholi', 'language' => 'Luo', 'region' => 'Northern Uganda', 'greeting' => 'Oyawore', 'phonetic' => 'oh-yah-WOR-eh', 'color_hex' => '#004E89', 'emoji_symbol' => '🦅'],
            ['name' => 'Basoga', 'slug' => 'basoga', 'language' => 'Lusoga', 'region' => 'Eastern Uganda', 'greeting' => 'Sikiliza', 'phonetic' => 'see-kee-LEE-zah', 'color_hex' => '#F77F00', 'emoji_symbol' => '🎶'],
            ['name' => 'Iteso', 'slug' => 'iteso', 'language' => 'Ateso', 'region' => 'Eastern Uganda', 'greeting' => 'Apapai', 'phonetic' => 'ah-pah-PAH-ee', 'color_hex' => '#06A77D', 'emoji_symbol' => '🌾'],
            ['name' => 'Banyankole', 'slug' => 'banyankole', 'language' => 'Runyankole', 'region' => 'Southwest Uganda', 'greeting' => 'Againe', 'phonetic' => 'ah-GAH-ee-neh', 'color_hex' => '#D62828', 'emoji_symbol' => '🦁'],
            ['name' => 'Alur', 'slug' => 'alur', 'language' => 'Dho-Alur', 'region' => 'Northwest Uganda', 'greeting' => 'Okwahira', 'phonetic' => 'ok-wah-HIE-rah', 'color_hex' => '#1E88E5', 'emoji_symbol' => '🌊'],
            ['name' => 'Yoruba', 'slug' => 'yoruba', 'language' => 'Yoruba', 'region' => 'Southwest Nigeria', 'greeting' => 'E kaaro', 'phonetic' => 'eh-KAH-roh', 'color_hex' => '#8B5CF6', 'emoji_symbol' => '🎭'],
            ['name' => 'Akan', 'slug' => 'akan', 'language' => 'Twi', 'region' => 'Southern Ghana', 'greeting' => 'Maakye', 'phonetic' => 'MAH-cheh', 'color_hex' => '#059669', 'emoji_symbol' => '🌿'],
        ];

        $tribes = [];
        foreach ($tribeData as $tribe) {
            $tribes[] = Tribe::firstOrCreate(['slug' => $tribe['slug']], $tribe);
        }

        // ── Users ──────────────────────────────────────────────────────
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@culturekids.app'],
            ['name' => 'Paulette Admin', 'password' => Hash::make('password'), 'org_id' => $orgs[0]->id, 'role' => 'super_admin']
        );
        $superAdmin->syncRoles('super_admin');

        $userDefs = [
            ['name' => 'Kofi Anan', 'email' => 'kofi.anan@culturekids.org', 'org' => 3, 'role' => 'org_admin'],
            ['name' => 'Zahara Mbeki', 'email' => 'z.mbeki@globaltribes.co', 'org' => 7, 'role' => 'cms_editor'],
            ['name' => 'Olu Jacobs', 'email' => 'ojacobs@heritage.edu', 'org' => 6, 'role' => 'teacher'],
            ['name' => 'Chioma Okoro', 'email' => 'chi.okoro@futurekids.io', 'org' => 4, 'role' => 'super_admin'],
            ['name' => 'Amara Kimathi', 'email' => 'amara.k@nairobi.edu', 'org' => 5, 'role' => 'org_admin'],
            ['name' => 'Felix Gitonga', 'email' => 'felix.g@culturekids.app', 'org' => 0, 'role' => 'cms_editor'],
            ['name' => 'Liam Ndiaye', 'email' => 'liam.n@culturekids.app', 'org' => 1, 'role' => 'teacher'],
            ['name' => 'Aisha Bakare', 'email' => 'aisha.b@lagoscultural.ng', 'org' => 4, 'role' => 'org_admin'],
            ['name' => 'Tendai Moyo', 'email' => 'tendai.m@capetown.ac', 'org' => 9, 'role' => 'teacher'],
            ['name' => 'Fatou Diallo', 'email' => 'fatou.d@eduafrica.org', 'org' => 8, 'role' => 'parent'],
            ['name' => 'Kwame Asante', 'email' => 'kwame.a@accraheritage.gh', 'org' => 3, 'role' => 'parent'],
            ['name' => 'Nana Owusu', 'email' => 'nana.o@globaltribes.co', 'org' => 7, 'role' => 'parent'],
        ];

        $users = [$superAdmin];
        foreach ($userDefs as $ud) {
            $u = User::firstOrCreate(
                ['email' => $ud['email']],
                ['name' => $ud['name'], 'password' => Hash::make('password'), 'org_id' => $orgs[$ud['org']]->id, 'role' => $ud['role']]
            );
            $u->syncRoles($ud['role']);
            $users[] = $u;
        }

        // ── Child Profiles ─────────────────────────────────────────────
        $childNames = [
            'Naluwooza', 'Amara', 'Kweku', 'Adaeze', 'Baraka', 'Chidi', 'Dalia',
            'Emeka', 'Fatima', 'Garang', 'Hasina', 'Imani', 'Jelani', 'Keza',
            'Lethabo', 'Makena', 'Nia', 'Obinna', 'Penda', 'Rashid', 'Sanaa',
            'Tariro', 'Uzoma', 'Wangari', 'Xolani', 'Yemi', 'Zawadi', 'Akello',
            'Binta', 'Chiamaka', 'Dabiku', 'Esi', 'Folami', 'Gakuru', 'Halima',
            'Ife', 'Jabari', 'Kamau', 'Lindiwe', 'Mosi', 'Nkechi', 'Olumide',
            'Pili', 'Rudo', 'Sekou', 'Thandi', 'Ulemu', 'Vusi', 'Wumi', 'Zuri',
        ];

        $parentUsers = array_values(array_filter($users, fn ($u) => $u->role === 'parent'));
        if (empty($parentUsers)) {
            $parentUsers = [$superAdmin];
        }

        $children = [];
        foreach ($childNames as $i => $name) {
            $parent = $parentUsers[$i % count($parentUsers)];
            $org = $orgs[$i % count($orgs)];
            $age = $ages[$i % count($ages)];

            $children[] = ChildProfile::firstOrCreate(
                ['parent_user_id' => $parent->id, 'name' => $name],
                [
                    'org_id' => $org->id,
                    'age_profile_id' => $age->id,
                    'date_of_birth' => now()->subYears(rand($age->age_min, $age->age_max))->subMonths(rand(0, 11)),
                    'avatar' => 'avatar_' . (($i % 8) + 1) . '.png',
                    'preferred_tribe_ids' => array_map(fn () => $tribes[array_rand($tribes)]->id, range(1, rand(1, 3))),
                ]
            );
        }

        // ── Comics ─────────────────────────────────────────────────────
        $comicTitles = [
            'The Orisha Chronicles', 'Modern Anansi', 'Lullabies of the Savannah',
            'Drum Patterns Vol. 1', 'Essential Yoruba Verbs', 'Animal Names & Sounds',
            'Tales of the Iroko Tree', 'Sunbird Adventures', 'The Golden Stool',
            'River Spirits', 'Moonlight Stories', 'The Baobab Secret',
            'Kingdoms of the Savannah', 'Folktales Retold', 'Ubuntu Lessons',
            'Songs of the Serengeti', 'The Wise Tortoise', 'Lion & the Mouse',
            'Stars Over Kilimanjaro', 'Ocean Whispers', 'The Calabash Dancer',
            'Warriors of Light', 'The Naming Ceremony', 'Journey to Timbuktu',
        ];

        $comics = [];
        foreach ($comicTitles as $i => $title) {
            $tribe = $tribes[$i % count($tribes)];
            $org = $orgs[$i % count($orgs)];
            $status = $i < 18 ? 'published' : (in_array($i, [18, 19]) ? 'review' : 'draft');

            $comics[] = Comic::firstOrCreate(
                ['title' => $title],
                [
                    'org_id' => $org->id,
                    'tribe_id' => $tribe->id,
                    'age_min' => rand(3, 6),
                    'age_max' => rand(8, 14),
                    'status' => $status,
                    'cover_image_path' => null,
                    'bundle_path' => null,
                    'bundle_hash' => $status === 'published' ? Str::random(64) : null,
                    'created_at' => now()->subDays(rand(1, 120))->subHours(rand(0, 23)),
                ]
            );
        }

        // ── Progress Events (Learning Events) ──────────────────────────
        $eventTypes = ['story_start', 'story_complete', 'badge_earned', 'vocab_seen', 'activity_complete'];
        $publishedComics = array_filter($comics, fn ($c) => $c->status === 'published');
        $publishedComics = array_values($publishedComics);

        for ($i = 0; $i < 800; $i++) {
            $child = $children[array_rand($children)];
            $comic = ! empty($publishedComics) ? $publishedComics[array_rand($publishedComics)] : null;
            $tribe = $tribes[array_rand($tribes)];
            $eventType = $eventTypes[array_rand($eventTypes)];
            $daysAgo = rand(0, 30);
            $hoursAgo = rand(0, 23);
            $recordedAt = now()->subDays($daysAgo)->subHours($hoursAgo)->subMinutes(rand(0, 59));

            ProgressEvent::create([
                'child_id' => $child->id,
                'comic_id' => $comic?->id,
                'tribe_id' => $tribe->id,
                'event_type' => $eventType,
                'panel_number' => $eventType === 'story_start' ? rand(1, 12) : null,
                'duration_seconds' => in_array($eventType, ['story_complete', 'activity_complete']) ? rand(30, 600) : rand(5, 120),
                'score' => in_array($eventType, ['activity_complete', 'badge_earned']) ? rand(50, 100) : null,
                'idempotency_key' => Str::uuid()->toString(),
                'payload' => ['source' => ['mobile', 'tablet', 'kiosk'][array_rand(['mobile', 'tablet', 'kiosk'])]],
                'metadata' => ['device' => ['iPad', 'Android Tablet', 'iPhone', 'Samsung Galaxy'][rand(0, 3)]],
                'recorded_at' => $recordedAt,
                'synced_at' => rand(0, 4) < 4 ? $recordedAt->copy()->addSeconds(rand(1, 300)) : null,
                'created_at' => $recordedAt,
                'updated_at' => $recordedAt,
            ]);
        }

        // ── Sync Events ────────────────────────────────────────────────
        $syncEventTypes = ['story_start', 'story_complete', 'badge_earned', 'vocab_seen', 'activity_complete'];

        for ($i = 0; $i < 400; $i++) {
            $child = $children[array_rand($children)];
            $daysAgo = rand(0, 30);
            $createdAt = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $isProcessed = rand(0, 100) < 92; // 92% success rate
            $processedAt = $isProcessed ? $createdAt->copy()->addMilliseconds(rand(50, 800)) : null;

            SyncEvent::create([
                'child_id' => $child->id,
                'event_type' => $syncEventTypes[array_rand($syncEventTypes)],
                'payload' => [
                    'device_id' => 'dev_' . Str::random(8),
                    'device_name' => ['iPad Pro', 'Samsung Tab', 'Kindle Fire', 'Huawei Pad', 'Pixel Tablet'][rand(0, 4)],
                    'os_type' => ['iOS', 'Android', 'FireOS'][rand(0, 2)],
                    'app_version' => '2.' . rand(1, 4) . '.' . rand(0, 9),
                ],
                'idempotency_key' => Str::uuid()->toString(),
                'processed' => $isProcessed,
                'processed_at' => $processedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // ── Audit Logs ─────────────────────────────────────────────────
        $auditActions = [
            'create_organisation', 'update_organisation', 'suspend_organisation',
            'create_user', 'update_user', 'impersonate_user', 'delete_user',
            'publish_comic', 'update_comic', 'delete_comic',
            'update_tribe', 'update_age_profile',
            'toggle_module', 'update_theme', 'export_css',
            'failed_ssh_attempt', 'rate_limit_exceeded',
        ];

        $modelTypes = [Organisation::class, User::class, Comic::class, Tribe::class, AgeProfile::class, null];

        for ($i = 0; $i < 60; $i++) {
            $actor = $users[array_rand($users)];
            $action = $auditActions[array_rand($auditActions)];
            $modelType = $modelTypes[array_rand($modelTypes)];
            $daysAgo = rand(0, 30);
            $createdAt = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59))->subSeconds(rand(0, 59));

            AuditLog::create([
                'user_id' => $actor->id,
                'impersonator_id' => $action === 'impersonate_user' ? $superAdmin->id : null,
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelType ? rand(1, 10) : null,
                'old_values' => in_array($action, ['update_organisation', 'update_comic', 'update_user'])
                    ? ['status' => 'draft', 'name' => 'Old Name']
                    : null,
                'new_values' => in_array($action, ['update_organisation', 'update_comic', 'update_user'])
                    ? ['status' => 'published', 'name' => 'Updated Name']
                    : ($action === 'impersonate_user' ? ['target_id' => 'usr_' . rand(10000, 99999), 'duration_limit' => 3600, 'reason' => 'Troubleshoot checkout bug'] : null),
                'ip_address' => rand(0, 3) < 3
                    ? rand(10, 192) . '.' . rand(1, 254) . '.' . rand(1, 254) . '.' . rand(1, 254)
                    : '127.0.0.1',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // ── Module Flags ───────────────────────────────────────────────
        ModuleFlag::seedDefaults();

        $this->command->info('✅ Seeded: ' . Organisation::count() . ' orgs, ' . User::count() . ' users, ' . ChildProfile::count() . ' children, ' . Comic::count() . ' comics, ' . ProgressEvent::count() . ' progress events, ' . SyncEvent::count() . ' sync events, ' . AuditLog::count() . ' audit logs, ' . ModuleFlag::count() . ' module flags');
    }
}

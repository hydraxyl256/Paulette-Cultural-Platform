<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ChildProfile;
use App\Models\ProgressEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $parentUser;
    protected ChildProfile $child;

    protected function setUp(): void
    {
        parent::setUp();

        // Create parent user with token
        $this->parentUser = User::factory()->create()->assignRole('parent');
        $this->child = ChildProfile::factory()->create(['parent_user_id' => $this->parentUser->id]);
    }

    /** @test */
    public function can_sync_offline_events_as_parent()
    {
        $token = $this->parentUser->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/v1/sync', [
            'events' => [
                [
                    'child_id' => $this->child->id,
                    'event_type' => 'story_completed',
                    'tribe_id' => 1,
                    'comic_id' => 5,
                    'duration_seconds' => 180,
                    'recorded_at' => now()->subHours(2)->toDateTimeString(),
                    'idempotency_key' => 'test-sync-1',
                ],
            ],
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('events_processed', 1);
        $this->assertDatabaseHas('progress_events', [
            'child_id' => $this->child->id,
            'event_type' => 'story_completed',
            'idempotency_key' => 'test-sync-1',
        ]);
    }

    /** @test */
    public function duplicate_events_are_skipped()
    {
        // Create existing event
        ProgressEvent::create([
            'child_id' => $this->child->id,
            'event_type' => 'story_completed',
            'idempotency_key' => 'duplicate-key',
            'recorded_at' => now(),
            'synced_at' => now(),
        ]);

        $token = $this->parentUser->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/v1/sync', [
            'events' => [
                [
                    'child_id' => $this->child->id,
                    'event_type' => 'story_completed',
                    'idempotency_key' => 'duplicate-key',
                    'recorded_at' => now()->toDateTimeString(),
                ],
            ],
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('events_skipped', 1);
        $this->assertEquals(1, ProgressEvent::where('idempotency_key', 'duplicate-key')->count());
    }

    /** @test */
    public function batch_sync_with_max_100_events()
    {
        $token = $this->parentUser->createToken('test')->plainTextToken;

        $events = collect(range(1, 50))->map(fn($i) => [
            'child_id' => $this->child->id,
            'event_type' => 'panel_viewed',
            'comic_id' => 5,
            'panel_number' => $i,
            'idempotency_key' => "batch-event-{$i}",
            'recorded_at' => now()->subMinutes($i)->toDateTimeString(),
        ])->all();

        $response = $this->postJson('/api/v1/sync', [
            'events' => $events,
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('events_processed', 50);
        $this->assertEquals(50, ProgressEvent::count());
    }

    /** @test */
    public function cannot_sync_other_users_children()
    {
        $otherParent = User::factory()->create()->assignRole('parent');
        $token = $otherParent->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/v1/sync', [
            'events' => [
                [
                    'child_id' => $this->child->id, // Not owned by $otherParent
                    'event_type' => 'story_completed',
                    'idempotency_key' => 'unauthorized',
                    'recorded_at' => now()->toDateTimeString(),
                ],
            ],
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('events_skipped', 1);
    }

    /** @test */
    public function badges_are_awarded_on_milestones()
    {
        // Create 5 story completions
        for ($i = 1; $i <= 5; $i++) {
            ProgressEvent::create([
                'child_id' => $this->child->id,
                'event_type' => 'story_completed',
                'idempotency_key' => "story-complete-{$i}",
                'recorded_at' => now()->subDays(5 - $i),
                'synced_at' => now(),
            ]);
        }

        $token = $this->parentUser->createToken('test')->plainTextToken;

        // 5th completion should trigger badge
        $response = $this->postJson('/api/v1/sync', [
            'events' => [
                [
                    'child_id' => $this->child->id,
                    'event_type' => 'story_completed',
                    'idempotency_key' => 'badge-trigger',
                    'recorded_at' => now()->toDateTimeString(),
                ],
            ],
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('badges_awarded.0', 'explorer_5');
        
        $this->assertDatabaseHas('progress_events', [
            'child_id' => $this->child->id,
            'event_type' => 'badge_earned',
            'metadata->badge_name' => 'Story Explorer',
        ]);
    }

    /** @test */
    public function unauthenticated_request_fails()
    {
        $response = $this->postJson('/api/v1/sync', [
            'events' => [],
        ]);

        $response->assertStatus(401);
    }
}

<?php

namespace Database\Seeders;

use App\Models\AudioTrack;
use Illuminate\Database\Seeder;

class AudioTrackSeeder extends Seeder
{
    public function run(): void
    {
        $tracks = [
            ['title' => 'Ancestral Echoes',    'subtitle' => 'Drumming Session #04',          'category' => 'yoruba_tribe',      'status' => 'live',       'duration_seconds' => 262,  'play_count' => 12400],
            ['title' => 'King Shaka Tales',     'subtitle' => 'Episode 12: The Rise',           'category' => 'zulu_oral_history', 'status' => 'processing', 'duration_seconds' => 735,  'play_count' => 0],
            ['title' => 'Congo Rainstorm',      'subtitle' => 'High Fidelity Field Recording',  'category' => 'nature_ambience',   'status' => 'live',       'duration_seconds' => 2700, 'play_count' => 8900],
            ['title' => 'Moonlight Songs',      'subtitle' => 'Nursery Rhymes Vol. 1',          'category' => 'lullabies',         'status' => 'live',       'duration_seconds' => 225,  'play_count' => 23100],
            ['title' => 'Yoruba Folktales',     'subtitle' => 'The Spider and the Sky',         'category' => 'yoruba_tribe',      'status' => 'live',       'duration_seconds' => 480,  'play_count' => 5600],
            ['title' => 'Igbo War Chants',      'subtitle' => 'Ancient Rhythms Compilation',    'category' => 'igbo_tribe',        'status' => 'live',       'duration_seconds' => 330,  'play_count' => 3200],
            ['title' => 'Maasai Morning',       'subtitle' => 'Sunrise Ceremony Recording',     'category' => 'drumming',          'status' => 'processing', 'duration_seconds' => 900,  'play_count' => 0],
            ['title' => 'Sahara Winds',         'subtitle' => 'Desert Soundscape Vol. 2',       'category' => 'nature_ambience',   'status' => 'live',       'duration_seconds' => 3600, 'play_count' => 41500],
            ['title' => 'Berber Night Songs',   'subtitle' => 'Traditional Amazigh Music',      'category' => 'general',           'status' => 'live',       'duration_seconds' => 445,  'play_count' => 1800],
            ['title' => 'Swahili Lullaby',      'subtitle' => 'Coastal Cradle Songs',           'category' => 'lullabies',         'status' => 'live',       'duration_seconds' => 198,  'play_count' => 9300],
            ['title' => 'Zulu Anthem',          'subtitle' => 'Inkosi Yama Nkosi',              'category' => 'zulu_oral_history', 'status' => 'archived',   'duration_seconds' => 240,  'play_count' => 6700],
            ['title' => 'Oral History: Sundiata','subtitle' => 'Episode 1: The Lion King',      'category' => 'general',           'status' => 'processing', 'duration_seconds' => 1560, 'play_count' => 0],
        ];

        foreach ($tracks as $track) {
            AudioTrack::updateOrCreate(
                ['title' => $track['title']],
                array_merge($track, [
                    'file_size_bytes' => rand(5, 80) * 1024 * 1024,
                    'download_count'  => intval($track['play_count'] * 0.23),
                    'is_featured'     => in_array($track['title'], ['Ancestral Echoes', 'Congo Rainstorm', 'Sahara Winds']),
                ])
            );
        }

        $this->command->info('✅ Seeded ' . count($tracks) . ' audio tracks.');
    }
}

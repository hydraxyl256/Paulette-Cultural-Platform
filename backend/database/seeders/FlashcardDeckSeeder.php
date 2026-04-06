<?php

namespace Database\Seeders;

use App\Models\FlashcardDeck;
use App\Models\Flashcard;
use App\Models\Tribe;
use Illuminate\Database\Seeder;

class FlashcardDeckSeeder extends Seeder
{
    public function run(): void
    {
        $decks = [
            ['name' => 'Yoruba Animals',    'subtitle' => 'Vocabulary & Pronunciation', 'tribe' => 'Yoruba', 'age_min' => 3,  'age_max' => 5,  'status' => 'live',     'engagement_rate' => 8720, 'cards' => 42],
            ['name' => 'Igbo Numbers',      'subtitle' => 'Counting 1-20',             'tribe' => 'Igbo',   'age_min' => 2,  'age_max' => 4,  'status' => 'draft',    'engagement_rate' => 6200, 'cards' => 20],
            ['name' => 'Adinkra Symbols',   'subtitle' => 'History & Meaning',         'tribe' => 'Akan',   'age_min' => 6,  'age_max' => 8,  'status' => 'live',     'engagement_rate' => 9140, 'cards' => 64],
            ['name' => 'Swahili Greetings', 'subtitle' => 'Daily Conversations',       'tribe' => null,     'age_min' => 3,  'age_max' => 6,  'status' => 'live',     'engagement_rate' => 8820, 'cards' => 35],
            ['name' => 'Zulu Colours',      'subtitle' => 'Colours of the Rainbow',    'tribe' => 'Zulu',   'age_min' => 3,  'age_max' => 5,  'status' => 'live',     'engagement_rate' => 7900, 'cards' => 18],
            ['name' => 'Maasai Warriors',   'subtitle' => 'Cultural Heritage Cards',   'tribe' => null,     'age_min' => 7,  'age_max' => 10, 'status' => 'draft',    'engagement_rate' => 4500, 'cards' => 28],
            ['name' => 'Hausa Shapes',      'subtitle' => 'Shapes & Geometry',         'tribe' => null,     'age_min' => 4,  'age_max' => 6,  'status' => 'live',     'engagement_rate' => 8200, 'cards' => 22],
            ['name' => 'Ashanti Proverbs',  'subtitle' => 'Wisdom of the Ancestors',   'tribe' => 'Akan',   'age_min' => 8,  'age_max' => 12, 'status' => 'archived', 'engagement_rate' => 7600, 'cards' => 50],
        ];

        foreach ($decks as $deckData) {
            $tribeName = $deckData['tribe'];
            $tribe = $tribeName ? Tribe::where('name', 'like', "%{$tribeName}%")->first() : null;

            $deck = FlashcardDeck::updateOrCreate(
                ['name' => $deckData['name']],
                [
                    'tribe_id'        => $tribe?->id,
                    'subtitle'        => $deckData['subtitle'],
                    'age_min'         => $deckData['age_min'],
                    'age_max'         => $deckData['age_max'],
                    'status'          => $deckData['status'],
                    'engagement_rate' => $deckData['engagement_rate'],
                    'is_global'       => in_array($deckData['name'], ['Swahili Greetings', 'Maasai Warriors']),
                ]
            );

            // Seed individual flashcards
            $existing = $deck->cards()->count();
            if ($existing < $deckData['cards']) {
                for ($i = $existing + 1; $i <= $deckData['cards']; $i++) {
                    Flashcard::create([
                        'deck_id'     => $deck->id,
                        'front_text'  => "Card {$i} Front - {$deckData['name']}",
                        'back_text'   => "Card {$i} Back Answer",
                        'order_index' => $i,
                    ]);
                }
            }
        }

        $this->command->info('✅ Seeded ' . count($decks) . ' flashcard decks with cards.');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'hyperactivity',
            'agressiveness',
            'depression',
            'autism',
            'selective mutism',
            'odd',
            'anxiety disorders',
            'bipolar',
            'eating disorders',
            'dyslexia',
            'tourette',
            'speech & language delays',
            'learning disabilities',
            'down syndrome',
            'other'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'title' => $tag
            ]);
        }
    }
}

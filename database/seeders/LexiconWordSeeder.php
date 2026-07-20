<?php

namespace Database\Seeders;

use App\Models\LexiconWord;
use Illuminate\Database\Seeder;

class LexiconWordSeeder extends Seeder
{
    public function run(): void
    {
        $positives = [
            'growth', 'increase', 'profit', 'stable', 'improve', 
            'boom', 'recovery', 'surplus', 'positive', 'expansion', 
            'strengthen', 'success', 'gain', 'growth', 'progress', 
            'healthy', 'robust', 'agreement', 'peace', 'cooperation'
        ];

        $negatives = [
            'war', 'crisis', 'inflation', 'delay', 'disaster', 
            'decline', 'loss', 'deficit', 'negative', 'recession', 
            'weaken', 'failure', 'shutdown', 'strike', 'blockade', 
            'shortage', 'conflict', 'disruption', 'risk', 'sanction', 
            'tariffs', 'tension', 'protest', 'storm', 'flood', 'drought'
        ];

        foreach (array_unique($positives) as $word) {
            LexiconWord::updateOrCreate(
                ['word' => $word],
                ['type' => 'positive']
            );
        }

        foreach (array_unique($negatives) as $word) {
            LexiconWord::updateOrCreate(
                ['word' => $word],
                ['type' => 'negative']
            );
        }
    }
}

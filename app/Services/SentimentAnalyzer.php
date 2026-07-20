<?php

namespace App\Services;

use App\Models\LexiconWord;

class SentimentAnalyzer
{
    protected $positives = [];
    protected $negatives = [];

    public function __construct()
    {
        // Load words from DB or use fallback if DB is not populated
        try {
            $words = LexiconWord::all();
            $this->positives = $words->where('type', 'positive')->pluck('word')->toArray();
            $this->negatives = $words->where('type', 'negative')->pluck('word')->toArray();
        } catch (\Exception $e) {
            // Fallback words
            $this->positives = ['growth', 'increase', 'profit', 'stable', 'improve', 'boom', 'recovery', 'surplus', 'positive', 'expansion', 'strengthen', 'success'];
            $this->negatives = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decline', 'loss', 'deficit', 'negative', 'recession', 'weaken', 'failure', 'shutdown'];
        }
    }

    /**
     * Analyze sentiment of a given text.
     *
     * @param string $text
     * @return array Contains 'positive_count', 'negative_count', 'label', and 'breakdown' [positive%, neutral%, negative%]
     */
    public function analyze(string $text): array
    {
        if (empty($text)) {
            return [
                'positive_count' => 0,
                'negative_count' => 0,
                'label' => 'Neutral',
                'breakdown' => [
                    'positive' => 0,
                    'neutral' => 100,
                    'negative' => 0
                ]
            ];
        }

        // Clean text and split into words
        $cleanText = strtolower(preg_replace('/[^a-zA-Z\s]/', '', $text));
        $words = preg_split('/\s+/', $cleanText, -1, PREG_SPLIT_NO_EMPTY);

        $posCount = 0;
        $negCount = 0;
        $matchedPos = [];
        $matchedNeg = [];

        foreach ($words as $word) {
            if (in_array($word, $this->positives)) {
                $posCount++;
                $matchedPos[] = $word;
            }
            if (in_array($word, $this->negatives)) {
                $negCount++;
                $matchedNeg[] = $word;
            }
        }

        $totalWords = count($words);
        $totalMatches = $posCount + $negCount;

        if ($posCount > $negCount) {
            $label = 'Positive';
        } elseif ($negCount > $posCount) {
            $label = 'Negative';
        } else {
            $label = 'Neutral';
        }

        // Calculate breakdown percentages
        if ($totalMatches > 0) {
            $posPercent = round(($posCount / $totalMatches) * 100);
            $negPercent = round(($negCount / $totalMatches) * 100);
            $neutralPercent = 0;
        } else {
            $posPercent = 0;
            $negPercent = 0;
            $neutralPercent = 100;
        }

        // If there are matches, let's distribute neutral if it's overall neutral
        if ($totalMatches > 0 && $posCount == $negCount) {
            $posPercent = 40;
            $negPercent = 40;
            $neutralPercent = 20;
        } elseif ($totalMatches > 0 && $posPercent > 0 && $negPercent > 0) {
            // Give some neutral weight for variety
            $neutralPercent = 15;
            $posPercent = round($posPercent * 0.85);
            $negPercent = 100 - $posPercent - $neutralPercent;
        } elseif ($totalMatches > 0) {
            $neutralPercent = 20;
            if ($posCount > 0) {
                $posPercent = 80;
                $negPercent = 0;
            } else {
                $negPercent = 80;
                $posPercent = 0;
            }
        }

        return [
            'positive_count' => $posCount,
            'negative_count' => $negCount,
            'label' => $label,
            'breakdown' => [
                'positive' => (int) $posPercent,
                'neutral' => (int) $neutralPercent,
                'negative' => (int) $negPercent
            ]
        ];
    }
}

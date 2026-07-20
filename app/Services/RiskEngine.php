<?php

namespace App\Services;

use App\Models\Country;
use App\Models\NewsCache;
use App\Models\RiskScore;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RiskEngine
{
    protected $sentimentAnalyzer;

    public function __construct(SentimentAnalyzer $sentimentAnalyzer)
    {
        $this->sentimentAnalyzer = $sentimentAnalyzer;
    }

    /**
     * Calculate and cache risk score for a country.
     *
     * @param Country $country
     * @return RiskScore
     */
    public function calculateCountryRisk(Country $country): RiskScore
    {
        // 1. Weather Score (30%)
        $weatherScore = $this->getWeatherScore($country);

        // 2. Inflation Score (20%)
        $inflationScore = $this->getInflationScore($country);

        // 3. Currency Score (10%)
        $currencyScore = $this->getCurrencyScore($country);

        // 4. News Sentiment Score (40%)
        $newsSentimentScore = $this->getNewsSentimentScore($country);

        // Total Risk Score
        $totalScore = ($weatherScore * 0.3) + ($inflationScore * 0.2) + ($currencyScore * 0.1) + ($newsSentimentScore * 0.4);
        $totalScore = round(min(100, max(0, $totalScore)));

        // Determine Risk Level
        if ($totalScore < 30) {
            $riskLevel = 'Low';
        } elseif ($totalScore < 60) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'High';
        }

        // Cache/Save risk score
        return RiskScore::updateOrCreate(
            ['country_code' => $country->code],
            [
                'weather_score' => $weatherScore,
                'inflation_score' => $inflationScore,
                'currency_score' => $currencyScore,
                'news_sentiment_score' => $newsSentimentScore,
                'total_score' => $totalScore,
                'risk_level' => $riskLevel
            ]
        );
    }

    /**
     * Fetch weather and calculate score.
     */
    protected function getWeatherScore(Country $country): float
    {
        try {
            // Call Open-Meteo
            $response = Http::timeout(3)->get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $country->latitude,
                'longitude' => $country->longitude,
                'current' => 'temperature_2m,precipitation,wind_speed_10m,weather_code'
            ]);

            if ($response->successful()) {
                $data = $response->json('current');
                $precipitation = $data['precipitation'] ?? 0; // mm
                $windSpeed = $data['wind_speed_10m'] ?? 0; // km/h
                $weatherCode = $data['weather_code'] ?? 0;

                $score = 10; // Base score

                // Wind risk
                if ($windSpeed > 40) {
                    $score += 40;
                } elseif ($windSpeed > 20) {
                    $score += 20;
                }

                // Precipitation risk
                if ($precipitation > 10) {
                    $score += 40;
                } elseif ($precipitation > 2) {
                    $score += 20;
                }

                // Storm codes
                if (in_array($weatherCode, [95, 96, 99])) {
                    $score += 30; // Thunderstorm
                } elseif (in_array($weatherCode, [56, 57, 66, 67, 75, 77, 82, 86])) {
                    $score += 20; // Extreme rain/snow
                }

                return min(100, $score);
            }
        } catch (\Exception $e) {
            Log::warning("Failed to fetch weather for {$country->name}: " . $e->getMessage());
        }

        // Mock weather score based on coordinates to be deterministic but varied
        $hash = crc32($country->code);
        return 15 + ($hash % 45); // returns between 15 and 60
    }

    /**
     * Calculate inflation risk.
     */
    protected function getInflationScore(Country $country): float
    {
        $inflation = $country->inflation ?? 2.0;

        if ($inflation < 0) {
            return 90; // Deflation is high risk
        } elseif ($inflation <= 3.0) {
            return 15; // Ideal inflation
        } elseif ($inflation <= 6.0) {
            return 40; // Moderate inflation
        } elseif ($inflation <= 10.0) {
            return 70; // High inflation
        } else {
            return 95; // Hyperinflation
        }
    }

    /**
     * Calculate currency volatility score.
     */
    protected function getCurrencyScore(Country $country): float
    {
        // For USD, currency risk is low
        if ($country->currency_code === 'USD') {
            return 10;
        }

        // Determine rate volatility based on historical rates in CountryMetric
        $metrics = $country->metrics()->orderBy('year', 'desc')->take(5)->pluck('currency_rate')->toArray();
        
        if (count($metrics) >= 2) {
            $latest = $metrics[0];
            $prev = $metrics[1];
            if ($prev > 0) {
                $changePercent = abs(($latest - $prev) / $prev) * 100;
                if ($changePercent < 2) return 15;
                if ($changePercent < 5) return 40;
                if ($changePercent < 10) return 70;
                return 95;
            }
        }

        // Fallback score
        $hash = crc32($country->currency_code);
        return 20 + ($hash % 50);
    }

    /**
     * Compute news sentiment risk.
     */
    protected function getNewsSentimentScore(Country $country): float
    {
        $articles = NewsCache::where('country_code', $country->code)->take(5)->get();

        if ($articles->isEmpty()) {
            return 50; // Neutral default
        }

        $totalPos = 0;
        $totalNeg = 0;

        foreach ($articles as $art) {
            $totalPos += $art->sentiment_positive;
            $totalNeg += $art->sentiment_negative;
        }

        if ($totalPos + $totalNeg === 0) {
            return 50;
        }

        // If negatives are high, risk goes up
        $negRatio = $totalNeg / ($totalPos + $totalNeg);
        
        // Map negative ratio to a score 0-100
        return round($negRatio * 100);
    }
}

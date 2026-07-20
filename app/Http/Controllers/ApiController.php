<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\NewsCache;
use App\Models\Port;
use App\Models\RiskScore;
use App\Services\RiskEngine;
use App\Services\SentimentAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    protected $riskEngine;
    protected $sentimentAnalyzer;

    public function __construct(RiskEngine $riskEngine, SentimentAnalyzer $sentimentAnalyzer)
    {
        $this->riskEngine = $riskEngine;
        $this->sentimentAnalyzer = $sentimentAnalyzer;
    }

    public function countries()
    {
        $countries = Country::with('riskScore')->get();
        return response()->json($countries);
    }

    public function risk(Request $request)
    {
        $code = strtoupper($request->query('country_code'));
        if (!$code) {
            return response()->json(['error' => 'country_code query parameter is required'], 400);
        }

        $country = Country::where('code', $code)->first();
        if (!$country) {
            return response()->json(['error' => 'Country not found'], 404);
        }

        $risk = RiskScore::where('country_code', $code)->first();
        if (!$risk) {
            $risk = $this->riskEngine->calculateCountryRisk($country);
        }

        return response()->json($risk);
    }

    public function ports(Request $request)
    {
        $query = Port::query();

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('country_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('country')) {
            $country = $request->query('country');
            $query->where('country_code', strtoupper($country))
                  ->orWhere('country_name', 'like', "%{$country}%");
        }

        return response()->json($query->get());
    }

    public function news(Request $request)
    {
        $code = strtoupper($request->query('country_code'));
        if (!$code) {
            return response()->json(['error' => 'country_code query parameter is required'], 400);
        }

        $country = Country::where('code', $code)->first();
        if (!$country) {
            return response()->json(['error' => 'Country not found'], 404);
        }

        // Try to load cached news
        $news = NewsCache::where('country_code', $code)->orderBy('published_at', 'desc')->take(5)->get();

        if ($news->isEmpty()) {
            // Fetch news (GNews API if configured, otherwise generate mock articles)
            $apiKey = env('GNEWS_API_KEY');
            $fetchedArticles = [];

            if ($apiKey) {
                try {
                    $response = Http::get("https://gnews.io/api/v4/search", [
                        'q' => "{$country->name} economy trade shipping logistics",
                        'lang' => 'en',
                        'apikey' => $apiKey,
                        'max' => 5
                    ]);

                    if ($response->successful()) {
                        $fetchedArticles = $response->json('articles') ?? [];
                    }
                } catch (\Exception $e) {
                    Log::error("GNews API failed: " . $e->getMessage());
                }
            }

            // Fallback: Generate mock news
            if (empty($fetchedArticles)) {
                $fetchedArticles = $this->generateMockNews($country);
            }

            foreach ($fetchedArticles as $art) {
                // Perform sentiment analysis
                $analysis = $this->sentimentAnalyzer->analyze($art['title'] . ' ' . ($art['description'] ?? ''));

                NewsCache::create([
                    'country_code' => $code,
                    'title' => $art['title'],
                    'description' => $art['description'] ?? null,
                    'content' => $art['content'] ?? null,
                    'url' => $art['url'] ?? null,
                    'source' => $art['source']['name'] ?? 'Global Logistics News',
                    'published_at' => isset($art['publishedAt']) ? date('Y-m-d H:i:s', strtotime($art['publishedAt'])) : now(),
                    'sentiment_positive' => $analysis['positive_count'],
                    'sentiment_negative' => $analysis['negative_count'],
                    'sentiment_label' => $analysis['label']
                ]);
            }

            // Re-fetch news
            $news = NewsCache::where('country_code', $code)->orderBy('published_at', 'desc')->take(5)->get();

            // Refresh risk score since news sentiment changed!
            $this->riskEngine->calculateCountryRisk($country);
        }

        return response()->json($news);
    }

    public function currency(Request $request)
    {
        $code = strtoupper($request->query('country_code'));
        if (!$code) {
            return response()->json(['error' => 'country_code query parameter is required'], 400);
        }

        $country = Country::with('metrics')->where('code', $code)->first();
        if (!$country) {
            return response()->json(['error' => 'Country not found'], 404);
        }

        // Fetch latest exchange rate (ExchangeRate API fallback if offline)
        $latestRate = 1.0;
        if ($country->currency_code !== 'USD') {
            try {
                $response = Http::timeout(3)->get("https://open.er-api.com/v6/latest/USD");
                if ($response->successful()) {
                    $rates = $response->json('rates');
                    $latestRate = $rates[$country->currency_code] ?? $country->metrics()->orderBy('year', 'desc')->first()->currency_rate;
                }
            } catch (\Exception $e) {
                $latestRate = $country->metrics()->orderBy('year', 'desc')->first()->currency_rate ?? 1.0;
            }
        }

        $history = $country->metrics()
            ->orderBy('year', 'asc')
            ->get(['year', 'currency_rate']);

        return response()->json([
            'country_name' => $country->name,
            'currency_code' => $country->currency_code,
            'currency_name' => $country->currency_name,
            'latest_rate' => $latestRate,
            'history' => $history
        ]);
    }

    protected function generateMockNews($country): array
    {
        // Positive keywords: growth, increase, profit, stable, improve
        // Negative keywords: war, crisis, inflation, delay, disaster
        
        $templates = [
            [
                'title' => "{$country->name} Economic Growth Accelerates to New Highs",
                'description' => "Recent reports show a robust increase in trade surplus. Local businesses report higher profit margins and a stable financial outlook, which is expected to improve industrial growth.",
                'source' => ['name' => 'MarketWatch'],
                'url' => 'https://example.com/news/1'
            ],
            [
                'title' => "Supply Chain Crisis Hits {$country->name} Ports Leading to Disastrous Delays",
                'description' => "A major labor strike has caused severe delay in cargo clearance. Local firms warn that inflation is rising, worsening the economic war and deepening the logistics crisis.",
                'source' => ['name' => 'Logistics Portal'],
                'url' => 'https://example.com/news/2'
            ],
            [
                'title' => "{$country->name} Currency Rebound Offers Stable Outlook for Imports",
                'description' => "Stable currency rates are helping to improve trade relations. The central bank predicts inflation will decline, leading to long-term economic growth and profit.",
                'source' => ['name' => 'Reuters Business'],
                'url' => 'https://example.com/news/3'
            ],
            [
                'title' => "Storm Disaster Causes Port Delays in {$country->name} Coastal Areas",
                'description' => "Heavy flooding and natural disaster damage have disrupted rail corridors. Delay in shipping containers is expected to raise short-term inflation and risk.",
                'source' => ['name' => 'Maritime News'],
                'url' => 'https://example.com/news/4'
            ],
            [
                'title' => "Trade Negotiations Progress to Improve Regional Cooperation for {$country->name}",
                'description' => "Government officials signed an agreement aimed at growth and stability. Increased trade volume will bolster positive economic development.",
                'source' => ['name' => 'Financial Times'],
                'url' => 'https://example.com/news/5'
            ]
        ];

        // Shuffle to get diverse news
        shuffle($templates);
        return array_slice($templates, 0, 3);
    }
}

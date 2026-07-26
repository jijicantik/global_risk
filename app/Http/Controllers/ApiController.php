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

        // Cache expiration check (2 hours)
        $latest = NewsCache::where('country_code', $code)->orderBy('created_at', 'desc')->first();
        if ($latest && $latest->created_at->addHours(2)->isPast()) {
            NewsCache::where('country_code', $code)->delete();
        }

        // Try to load cached news
        $cached = NewsCache::where('country_code', $code)->orderBy('published_at', 'desc')->get()->unique('title')->values();

        // If cached items are less than 5, refresh cache to ensure at least 5 unique articles
        if ($cached->count() < 5) {
            NewsCache::where('country_code', $code)->delete();

            $apiKey = env('GNEWS_API_KEY');
            $fetchedArticles = [];

            if ($apiKey) {
                try {
                    $response = Http::get("https://gnews.io/api/v4/search", [
                        'q' => "{$country->name} AND (logistics OR trade OR shipping OR economy)",
                        'lang' => 'en',
                        'apikey' => $apiKey,
                        'max' => 10
                    ]);

                    if ($response->successful()) {
                        $fetchedArticles = $response->json('articles') ?? [];
                    }
                } catch (\Exception $e) {
                    Log::error("GNews API failed: " . $e->getMessage());
                }
            }

            // Fallback / complement with RSS
            $allRss = $this->fetchRealtimeRssNews();
            $matched = [];
            $unmatched = [];

            foreach ($allRss as $art) {
                $text = strtolower($art['title'] . ' ' . $art['description']);
                $countryName = strtolower($country->name);
                $countryCode = strtolower($country->code);
                $region = strtolower($country->region ?? '');

                $isMatch = str_contains($text, $countryName) || 
                           ($region && str_contains($text, $region)) ||
                           ($country->currency_code && str_contains($text, strtolower($country->currency_code))) ||
                           preg_match('/\b' . preg_quote($countryCode, '/') . '\b/i', $text);

                if ($isMatch) {
                    $matched[] = $art;
                } else {
                    $unmatched[] = $art;
                }
            }

            $candidateArticles = array_merge($fetchedArticles, $matched, $unmatched);

            // Deduplicate all candidates strictly by normalized title
            $uniqueCandidates = [];
            $seenTitles = [];
            foreach ($candidateArticles as $art) {
                $cleanTitle = trim($art['title'] ?? '');
                if (!$cleanTitle) continue;
                $normalized = strtolower(preg_replace('/[^a-z0-9]/i', '', $cleanTitle));
                if (!isset($seenTitles[$normalized])) {
                    $seenTitles[$normalized] = true;
                    $art['title'] = $cleanTitle;
                    $uniqueCandidates[] = $art;
                }
            }

            // If candidates are less than 6, supplement from country-specific mock news generator
            if (count($uniqueCandidates) < 6) {
                $mockArticles = $this->generateMockNews($country);
                foreach ($mockArticles as $mock) {
                    $normalized = strtolower(preg_replace('/[^a-z0-9]/i', '', $mock['title']));
                    if (!isset($seenTitles[$normalized])) {
                        $seenTitles[$normalized] = true;
                        $uniqueCandidates[] = $mock;
                    }
                }
            }

            // Take top 6 unique articles to ensure at least 5 articles are always presented
            $selectedArticles = array_slice($uniqueCandidates, 0, 6);

            $usedImages = [];

            foreach ($selectedArticles as $index => $art) {
                $titleDesc = $art['title'] . ' ' . ($art['description'] ?? '');
                $category = $art['category'] ?? $this->determineCategory($titleDesc);
                $analysis = $this->sentimentAnalyzer->analyze($titleDesc);

                // Prioritize original news image URL from the article
                $imageUrl = $art['image'] ?? $art['image_url'] ?? null;
                if (!$imageUrl || str_contains($imageUrl, '.mp3')) {
                    $imageUrl = $this->getTopicMatchingImage($art['title'], $art['description'] ?? '', $category, $usedImages);
                } else {
                    $usedImages[] = $imageUrl;
                }

                NewsCache::firstOrCreate(
                    [
                        'country_code' => $code,
                        'title' => $art['title'],
                    ],
                    [
                        'description' => $art['description'] ?? null,
                        'content' => $art['content'] ?? null,
                        'url' => $art['url'] ?? ('https://example.com/news/' . md5($art['title'])),
                        'image_url' => $imageUrl,
                        'source' => is_array($art['source']) ? ($art['source']['name'] ?? 'Global News') : ($art['source'] ?? 'Global News'),
                        'published_at' => isset($art['publishedAt']) ? date('Y-m-d H:i:s', strtotime($art['publishedAt'])) : (isset($art['published_at']) ? $art['published_at'] : now()->subHours($index * 2)),
                        'sentiment_positive' => $analysis['positive_count'],
                        'sentiment_negative' => $analysis['negative_count'],
                        'sentiment_label' => $analysis['label'],
                        'category' => $category
                    ]
                );
            }

            $cached = NewsCache::where('country_code', $code)->orderBy('published_at', 'desc')->get()->unique('title')->values();
            $this->riskEngine->calculateCountryRisk($country);
        }

        // Ensure cached items preserve original images, filling missing ones with topic fallback
        $usedImages = [];
        foreach ($cached as $item) {
            if (!$item->image_url || str_contains($item->image_url, '.mp3')) {
                $item->image_url = $this->getTopicMatchingImage($item->title, $item->description ?? '', $item->category ?? '', $usedImages);
                $item->save();
            } else {
                $usedImages[] = $item->image_url;
            }
        }

        return response()->json($cached->take(6));
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
                    $latestRate = $rates[$country->currency_code] ?? ($country->metrics()->orderBy('year', 'desc')->first()->currency_rate ?? 1.0);
                }
            } catch (\Exception $e) {
                $latestRate = $country->metrics()->orderBy('year', 'desc')->first()->currency_rate ?? 1.0;
            }
        }

        // Sync latest rate into the current year (2026) metric in DB
        $currentYear = (int)date('Y');
        $latestMetric = $country->metrics()->where('year', $currentYear)->first();
        if ($latestMetric) {
            $latestMetric->currency_rate = $latestRate;
            $latestMetric->save();
        }

        $history = $country->metrics()
            ->orderBy('year', 'asc')
            ->get(['year', 'currency_rate']);

        // Ensure the last entry in history reflects the live latest_rate exactly
        if ($history->isNotEmpty()) {
            $lastItem = $history->last();
            if ($lastItem->year == $currentYear) {
                $lastItem->currency_rate = $latestRate;
            }
        }

        return response()->json([
            'country_name' => $country->name,
            'currency_code' => $country->currency_code,
            'currency_name' => $country->currency_name,
            'latest_rate' => $latestRate,
            'history' => $history
        ]);
    }

    protected function getTopicMatchingImage(string $title, string $description = '', string $category = '', array &$usedImages = []): string
    {
        $text = strtolower($title . ' ' . $description . ' ' . $category);

        $pools = [
            'automotive' => [
                'keywords' => ['car', 'cars', 'vehicle', 'automotive', 'avandamobil', 'dealership', 'auto', 'motor', 'driving'],
                'images' => [
                    'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800&auto=format&fit=crop&q=80',
                ]
            ],
            'diplomacy' => [
                'keywords' => ['open new chapter', 'economic ties', 'bilateral', 'cooperation', 'partnership', 'forum', 'diplomacy', 'treaty', 'agreement', 'trade links', 'relations', 'ambassador', 'summit'],
                'images' => [
                    'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1577495508048-b635879837f1?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&auto=format&fit=crop&q=80',
                ]
            ],
            'technology' => [
                'keywords' => ['platform', 'whatsapp', 'chat', 'app', 'digital', 'technology', 'tech', 'software', 'online', 'ai', 'cloud', 'cyber'],
                'images' => [
                    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1556742049-0a67daf4005a?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=80',
                ]
            ],
            'shipping' => [
                'keywords' => ['port', 'shipping', 'maritime', 'vessel', 'container', 'harbor', 'canal', 'sea', 'ocean', 'red sea', 'ship', 'dock', 'cargo ship'],
                'images' => [
                    'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1565891741441-6ad9655777da?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1518241353330-0f7941c2d9b5?w=800&auto=format&fit=crop&q=80',
                ]
            ],
            'logistics' => [
                'keywords' => ['logistics', 'warehouse', 'freight', 'truck', 'trucking', 'delivery', 'supply chain', 'distribution', 'inventory', 'cargo', 'carrier'],
                'images' => [
                    'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1580674684081-7617fbf3d745?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1553413077-190dd305871c?w=800&auto=format&fit=crop&q=80',
                ]
            ],
            'economy' => [
                'keywords' => ['economy', 'economic', 'inflation', 'gdp', 'central bank', 'interest rate', 'surplus', 'growth', 'recession', 'financial', 'currency', 'market', 'stock', 'banking', 'profit'],
                'images' => [
                    'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1616401784845-180882ba9ba8?w=800&auto=format&fit=crop&q=80',
                ]
            ],
            'manufacturing' => [
                'keywords' => ['manufacturing', 'factory', 'steel', 'industrial', 'production', 'plant', 'assembly', 'copper', 'raw material'],
                'images' => [
                    'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=800&auto=format&fit=crop&q=80',
                ]
            ],
            'weather' => [
                'keywords' => ['weather', 'storm', 'rain', 'flood', 'climate', 'forecast', 'disruption', 'delay', 'typhoon', 'hurricane'],
                'images' => [
                    'https://images.unsplash.com/photo-1515694346937-94d85e41e6f0?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1534088568595-a066f410bcda?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1561484930-974554019ade?w=800&auto=format&fit=crop&q=80',
                ]
            ]
        ];

        // Find matching topic
        $matchedCategory = null;
        foreach ($pools as $key => $pool) {
            foreach ($pool['keywords'] as $kw) {
                if (str_contains($text, $kw)) {
                    $matchedCategory = $key;
                    break 2;
                }
            }
        }

        // Try to select an unused image from matched category
        if ($matchedCategory && isset($pools[$matchedCategory])) {
            foreach ($pools[$matchedCategory]['images'] as $img) {
                if (!in_array($img, $usedImages)) {
                    $usedImages[] = $img;
                    return $img;
                }
            }
        }

        // Fallback: cycle through all images across all pools
        foreach ($pools as $pool) {
            foreach ($pool['images'] as $img) {
                if (!in_array($img, $usedImages)) {
                    $usedImages[] = $img;
                    return $img;
                }
            }
        }

        $defaultImg = 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&auto=format&fit=crop&q=80';
        $usedImages[] = $defaultImg;
        return $defaultImg;
    }

    protected function generateMockNews($country): array
    {
        return [
            [
                'title' => "{$country->name} Economic Growth Accelerates with Record Trade Surplus",
                'description' => "Recent reports highlight a robust increase in trade surplus for {$country->name}. Local manufacturers report higher export profit margins and a stable financial outlook, improving industrial expansion.",
                'source' => 'MarketWatch Journal',
                'category' => 'Economy',
                'image_url' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=800&auto=format&fit=crop&q=80',
                'url' => "https://example.com/news/{$country->code}/1"
            ],
            [
                'title' => "Port Congestion and Container Delays Impact {$country->name} Supply Chains",
                'description' => "Severe harbor congestion at major freight hubs in {$country->name} has caused container clearance delays of up to 48 hours. Logistics operators urge supply chain managers to plan buffer inventories.",
                'source' => 'Global Logistics Review',
                'category' => 'Shipping',
                'image_url' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=800&auto=format&fit=crop&q=80',
                'url' => "https://example.com/news/{$country->code}/2"
            ],
            [
                'title' => "{$country->name} Central Bank Implements New Measures to Stabilize Inflation",
                'description' => "Monetary policy adjustments in {$country->name} aim to bring annual inflation back toward target levels. Key interest rates remain steady as market analysts project reduced price volatility.",
                'source' => 'Reuters Business News',
                'category' => 'Economy',
                'image_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&auto=format&fit=crop&q=80',
                'url' => "https://example.com/news/{$country->code}/3"
            ],
            [
                'title' => "Bilateral Trade Agreement Enhances Import & Export Security for {$country->name}",
                'description' => "Officials in {$country->name} signed a landmark trade agreement designed to streamline customs inspections and eliminate tariff barriers for regional supply chain partners.",
                'source' => 'Financial Times Today',
                'category' => 'Trade',
                'image_url' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=800&auto=format&fit=crop&q=80',
                'url' => "https://example.com/news/{$country->code}/4"
            ],
            [
                'title' => "Severe Weather Forecast Triggers Freight Logistics Alerts Across {$country->name}",
                'description' => "Heavy storm warnings along primary transport corridors in {$country->name} could disrupt overland trucking and rail delivery schedules over the coming days.",
                'source' => 'Maritime & Freight Daily',
                'category' => 'Logistics',
                'image_url' => 'https://images.unsplash.com/photo-1515694346937-94d85e41e6f0?w=800&auto=format&fit=crop&q=80',
                'url' => "https://example.com/news/{$country->code}/5"
            ],
            [
                'title' => "{$country->name} Industrial Sector Sees Increased Energy & Raw Material Stability",
                'description' => "Steady input costs and improved supplier reliability have boosted industrial manufacturing output across key production centers in {$country->name}.",
                'source' => 'Industry Week International',
                'category' => 'Trade',
                'image_url' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&auto=format&fit=crop&q=80',
                'url' => "https://example.com/news/{$country->code}/6"
            ]
        ];
    }

    protected function fetchRealtimeRssNews(): array
    {
        $feeds = [
            'https://theloadstar.com/feed/' => 'The Loadstar',
            'https://feeds.feedburner.com/logisticsmgmt/latest' => 'Logistics Management',
            'https://rss.nytimes.com/services/xml/rss/nyt/Economy.xml' => 'NYT Economy',
            'https://www.maritime-executive.com/rss' => 'Maritime Executive'
        ];

        $allArticles = [];
        foreach ($feeds as $url => $source) {
            $feedArticles = $this->parseRss($url, $source);
            $allArticles = array_merge($allArticles, $feedArticles);
        }

        return $allArticles;
    }

    protected function parseRss($url, $feedSource): array
    {
        $articles = [];
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $xmlString = curl_exec($ch);
            curl_close($ch);

            if (!$xmlString) return [];

            $xml = @simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (!$xml || !isset($xml->channel->item)) return [];

            foreach ($xml->channel->item as $item) {
                $title = (string)$item->title;
                $description = (string)$item->description;
                $link = (string)$item->link;
                $pubDate = (string)$item->pubDate;

                // Try to extract image URL from feed item
                $imageUrl = null;
                if (isset($item->enclosure) && isset($item->enclosure['url'])) {
                    $imageUrl = (string)$item->enclosure['url'];
                }
                if (!$imageUrl) {
                    $ns = $item->getNameSpaces(true);
                    if (isset($ns['media']) && $media = $item->children($ns['media'])) {
                        if (isset($media->content) && isset($media->content->attributes()->url)) {
                            $imageUrl = (string)$media->content->attributes()->url;
                        } elseif (isset($media->thumbnail) && isset($media->thumbnail->attributes()->url)) {
                            $imageUrl = (string)$media->thumbnail->attributes()->url;
                        }
                    }
                }

                $articles[] = [
                    'title' => trim($title),
                    'description' => trim(strip_tags($description)),
                    'url' => trim($link),
                    'image_url' => $imageUrl,
                    'source' => [
                        'name' => $feedSource
                    ],
                    'publishedAt' => $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : now()->toDateTimeString(),
                ];
            }
        } catch (\Exception $e) {
            Log::error("RSS Parse failed for $url: " . $e->getMessage());
        }
        return $articles;
    }

    protected function determineCategory($text): string
    {
        $text = strtolower($text);
        
        $logisticsKeywords = ['logistics', 'supply chain', 'freight', 'transport', 'carrier', 'truck', 'warehouse', 'delivery', 'cargo', 'corridor', 'logistik', 'distribusi', 'distribute', 'distribution'];
        $tradeKeywords = ['trade', 'export', 'import', 'tariff', 'treaty', 'sanctions', 'customs', 'duty', 'embargo', 'impor', 'ekspor', 'perdagangan', 'deal'];
        $shippingKeywords = ['shipping', 'port', 'maritime', 'vessel', 'container', 'ocean', 'harbor', 'canal', 'sea', 'marine', 'pelabuhan', 'kapalan', 'kapal'];
        $economyKeywords = ['economy', 'economic', 'gdp', 'inflation', 'currency', 'recession', 'growth', 'market', 'financial', 'finance', 'interest rate', 'ekonomi', 'rupiah', 'dollar', 'inflasi'];

        foreach ($shippingKeywords as $keyword) {
            if (str_contains($text, $keyword)) return 'Shipping';
        }
        foreach ($logisticsKeywords as $keyword) {
            if (str_contains($text, $keyword)) return 'Logistics';
        }
        foreach ($tradeKeywords as $keyword) {
            if (str_contains($text, $keyword)) return 'Trade';
        }
        foreach ($economyKeywords as $keyword) {
            if (str_contains($text, $keyword)) return 'Economy';
        }

        // Randomly return one if no keyword matches to keep it dynamic and balanced
        $categories = ['Logistics', 'Trade', 'Shipping', 'Economy'];
        return $categories[array_rand($categories)];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $authorId = $admin ? $admin->id : 1;

        $articles = [
            [
                'title' => 'Global Shipping Routes Disruptions and Rising Fuel Volatility',
                'content' => 'The global shipping sector is experiencing severe bottlenecks due to heightened geopolitical tensions in the Red Sea and low water levels in the Panama Canal. Logistics costs have jumped by 35% over the past two quarters. Companies are advised to diversify routing and stockpile safety buffer stock for critical raw materials.',
                'author_id' => $authorId,
                'status' => 'Published',
            ],
            [
                'title' => 'Inflationary Pressures in APAC Manufacturing Hubs',
                'content' => 'High inflation in core input materials like copper and steel is causing margin compression for electronic manufacturers in Asia-Pacific. Despite stable exchange rates, retail prices are expected to rise. Analysts recommend hedging commodity purchases and restructuring local supplier networks.',
                'author_id' => $authorId,
                'status' => 'Published',
            ],
            [
                'title' => 'Climate Extremes: Weather Risks in Northern European Ports',
                'content' => 'Increased frequency of heavy winter storms in Northern Europe has triggered cargo delays at major terminals. Hamburg and Rotterdam report an average delay of 48 hours for inbound container ships. Implementing real-time predictive scheduling tools is crucial to mitigate port congestion.',
                'author_id' => $authorId,
                'status' => 'Draft',
            ],
        ];

        foreach ($articles as $a) {
            Article::updateOrCreate(['title' => $a['title']], $a);
        }
    }
}

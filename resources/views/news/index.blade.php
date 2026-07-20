@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">News Intelligence</h1>
            <p class="text-slate-500 mt-1">Lexicon-based sentiment analysis of geopolitical and logistics news</p>
        </div>
        <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <label for="news-country-select" class="text-sm font-semibold text-slate-500">Track Country News:</label>
            <select id="news-country-select" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($countries as $c)
                    <option value="{{ $c->code }}" {{ $selectedCountry->code === $c->code ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sentiment Breakdown Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-6">Aggregate Sentiment Summary</h2>
                <div class="flex justify-center mb-6" style="height:200px">
                    <canvas id="sentimentOverviewChart"></canvas>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="flex items-center gap-2 font-medium text-slate-600">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span> Positive News weight
                        </span>
                        <span id="overview-positive" class="font-bold text-slate-800">--%</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="flex items-center gap-2 font-medium text-slate-600">
                            <span class="w-3 h-3 rounded-full bg-slate-300"></span> Neutral News weight
                        </span>
                        <span id="overview-neutral" class="font-bold text-slate-800">--%</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="flex items-center gap-2 font-medium text-slate-600">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span> Negative News weight
                        </span>
                        <span id="overview-negative" class="font-bold text-slate-800">--%</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 bg-slate-50 border border-slate-100 p-4 rounded-xl text-xs text-slate-500 leading-relaxed">
                🤖 Lexicon Parser analyzes headings and descriptions using a seeded dictionary of Positive (e.g. <i>growth, profit, stable</i>) and Negative (e.g. <i>war, crisis, delay</i>) supply chain terms.
            </div>
        </div>

        <!-- News Articles List -->
        <div class="lg:col-span-2 space-y-6" id="news-articles-container">
            <!-- Dynamically populated -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let summaryChart = null;

    const loadNews = async (code) => {
        const container = document.getElementById('news-articles-container');
        container.innerHTML = `
            <div class="bg-white rounded-2xl border p-12 text-center text-slate-500">
                <span class="inline-block animate-spin text-2xl mr-2">🔄</span> Loading news feed and parsing sentiments...
            </div>
        `;

        try {
            const res = await axios.get(`/api/news?country_code=${code}`);
            if (res.data && res.data.length > 0) {
                const articles = res.data;
                container.innerHTML = '';

                let posSum = 0;
                let negSum = 0;
                let neutralSum = 0;

                articles.forEach(art => {
                    const totalMatches = art.sentiment_positive + art.sentiment_negative;
                    let pPct = 0, nPct = 0, neuPct = 100;
                    
                    if (totalMatches > 0) {
                        pPct = Math.round((art.sentiment_positive / totalMatches) * 80);
                        nPct = Math.round((art.sentiment_negative / totalMatches) * 80);
                        neuPct = 100 - pPct - nPct;
                    }

                    if (art.sentiment_label === 'Positive') {
                        posSum++;
                    } else if (art.sentiment_label === 'Negative') {
                        negSum++;
                    } else {
                        neutralSum++;
                    }

                    let labelBadge = 'bg-slate-100 text-slate-600 border-slate-200';
                    if (art.sentiment_label === 'Positive') {
                        labelBadge = 'bg-green-50 text-green-700 border-green-200';
                    } else if (art.sentiment_label === 'Negative') {
                        labelBadge = 'bg-red-50 text-red-700 border-red-200';
                    }

                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4 hover:shadow-md transition';
                    card.innerHTML = `
                        <div class="flex justify-between items-start gap-4">
                            <span class="text-xs font-semibold text-slate-400 uppercase">${art.source} | ${new Date(art.published_at).toLocaleDateString()}</span>
                            <span class="px-2.5 py-0.5 rounded text-xs font-bold border ${labelBadge}">
                                ${art.sentiment_label}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 leading-snug">${art.title}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">${art.description || 'No description available.'}</p>
                        
                        <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 text-xs flex justify-between items-center text-slate-500">
                            <div>
                                Lexicon hits: 
                                <span class="text-green-600 font-bold">Positive (${art.sentiment_positive})</span> | 
                                <span class="text-red-500 font-bold">Negative (${art.sentiment_negative})</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="font-bold text-slate-700">Analysis Breakdown:</span>
                                <span class="text-green-600 font-semibold">${pPct}% Pos</span>
                                <span class="text-slate-400 font-semibold">${neuPct}% Neu</span>
                                <span class="text-red-500 font-semibold">${nPct}% Neg</span>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                });

                // Update charts
                const totalCount = articles.length;
                const posPct = Math.round((posSum / totalCount) * 100);
                const negPct = Math.round((negSum / totalCount) * 100);
                const neuPct = 100 - posPct - negPct;

                document.getElementById('overview-positive').innerText = `${posPct}%`;
                document.getElementById('overview-neutral').innerText = `${neuPct}%`;
                document.getElementById('overview-negative').innerText = `${negPct}%`;

                if (summaryChart) {
                    summaryChart.destroy();
                }

                const ctx = document.getElementById('sentimentOverviewChart').getContext('2d');
                summaryChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Positive', 'Neutral', 'Negative'],
                        datasets: [{
                            data: [posSum, neutralSum, negSum],
                            backgroundColor: ['#22c55e', '#cbd5e1', '#ef4444'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            } else {
                container.innerHTML = `
                    <div class="bg-white rounded-2xl border p-12 text-center text-slate-500">
                        No articles found for this country.
                    </div>
                `;
            }
        } catch (e) {
            console.error(e);
            container.innerHTML = `
                <div class="bg-white rounded-2xl border p-12 text-center text-red-500">
                    Failed to load news articles.
                </div>
            `;
        }
    };

    // Initialize
    const select = document.getElementById('news-country-select');
    loadNews(select.value);

    // Dropdown change
    select.addEventListener('change', (e) => {
        loadNews(e.target.value);
    });
});
</script>
@endsection

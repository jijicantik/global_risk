@extends('layouts.app')

@section('content')
<div class="scr-content">
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
                ðŸ¤– Lexicon Parser analyzes headings and descriptions using a seeded dictionary of Positive (e.g. <i>growth, profit, stable</i>) and Negative (e.g. <i>war, crisis, delay</i>) supply chain terms.
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

    let refreshInterval = null;

    const startAutoRefresh = (code) => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        refreshInterval = setInterval(() => {
            console.log(`Auto-refreshing news for ${code}...`);
            loadNews(code, true);
        }, 300000); // Auto refresh every 5 minutes
    };

    const loadNews = async (code, quiet = false) => {
        const container = document.getElementById('news-articles-container');
        if (!quiet) {
            container.innerHTML = `
                <div class="bg-white rounded-2xl border p-12 text-center text-slate-500">
                    <span class="inline-block animate-spin text-2xl mr-2">🔄</span> Loading news feed and parsing sentiments...
                </div>
            `;
        }

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

                    let labelClass = 'neutral';
                    if (art.sentiment_label === 'Positive') {
                        labelClass = 'positive';
                    } else if (art.sentiment_label === 'Negative') {
                        labelClass = 'negative';
                    }

                    let categoryBadge = '';
                    const cat = art.category || 'Logistics';
                    if (cat === 'Logistics') {
                        categoryBadge = '<span class="scr-badge" style="background:rgba(59,130,246,0.15); color:#60a5fa; border:1px solid rgba(59,130,246,0.3);">📦 Logistics</span>';
                    } else if (cat === 'Trade') {
                        categoryBadge = '<span class="scr-badge" style="background:rgba(245,158,11,0.15); color:#fbbf24; border:1px solid rgba(245,158,11,0.3);">🤝 Trade</span>';
                    } else if (cat === 'Shipping') {
                        categoryBadge = '<span class="scr-badge" style="background:rgba(139,92,246,0.15); color:#a78bfa; border:1px solid rgba(139,92,246,0.3);">🚢 Shipping</span>';
                    } else if (cat === 'Economy') {
                        categoryBadge = '<span class="scr-badge" style="background:rgba(16,185,129,0.15); color:#34d399; border:1px solid rgba(16,185,129,0.3);">📈 Economy</span>';
                    }

                    const getTopicFallback = (title, category) => {
                        const text = ((title || '') + ' ' + (category || '')).toLowerCase();
                        if (text.includes('car') || text.includes('auto') || text.includes('vehicle') || text.includes('avandamobil')) {
                            return 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800&auto=format&fit=crop&q=80';
                        }
                        if (text.includes('pakistan') || text.includes('ties') || text.includes('economic ties') || text.includes('bilateral') || text.includes('agreement') || text.includes('cooperation')) {
                            return 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=800&auto=format&fit=crop&q=80';
                        }
                        if (text.includes('port') || text.includes('ship') || text.includes('maritime') || text.includes('vessel') || text.includes('harbor')) {
                            return 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=800&auto=format&fit=crop&q=80';
                        }
                        if (text.includes('platform') || text.includes('whatsapp') || text.includes('chat') || text.includes('app') || text.includes('tech')) {
                            return 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&auto=format&fit=crop&q=80';
                        }
                        if (text.includes('inflation') || text.includes('bank') || text.includes('gdp') || text.includes('growth') || text.includes('economy') || text.includes('market')) {
                            return 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&auto=format&fit=crop&q=80';
                        }
                        if (text.includes('weather') || text.includes('storm') || text.includes('rain') || text.includes('delay')) {
                            return 'https://images.unsplash.com/photo-1515694346937-94d85e41e6f0?w=800&auto=format&fit=crop&q=80';
                        }
                        return 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&auto=format&fit=crop&q=80';
                    };

                    const fallbackUrl = getTopicFallback(art.title, art.category);

                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition flex flex-col md:flex-row';
                    
                    const imageHtml = art.image_url 
                        ? `<div class="md:w-1/3 h-48 md:h-auto relative flex-shrink-0 min-h-[180px]">
                             <img src="${art.image_url}" referrerpolicy="no-referrer" alt="${art.title}" class="w-full h-full object-cover absolute inset-0 bg-slate-100" onerror="if(!this.dataset.fallback){this.dataset.fallback=1;this.src='${fallbackUrl}';}">
                           </div>`
                        : '';

                    card.innerHTML = `
                        ${imageHtml}
                        <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex justify-between items-center gap-4 mb-2">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="text-xs font-semibold text-slate-400 uppercase">${art.source} · ${new Date(art.published_at).toLocaleDateString()}</span>
                                        ${categoryBadge}
                                    </div>
                                    <span class="scr-badge ${labelClass}">
                                        ${art.sentiment_label}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 leading-snug">
                                    <a href="${art.url}" target="_blank" rel="noopener noreferrer" class="hover:text-blue-600 hover:underline transition-colors">
                                        ${art.title}
                                    </a>
                                </h3>
                                <p class="text-sm text-slate-500 mt-2 leading-relaxed line-clamp-3">${art.description || 'No description available.'}</p>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center pt-1 pb-3">
                                    <a href="${art.url}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                                        Read full article
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>

                                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 text-xs flex flex-col sm:flex-row gap-2 justify-between items-start sm:items-center text-slate-500">
                                    <div>
                                        Lexicon hits: 
                                        <span class="text-green-600 font-bold">Positive (${art.sentiment_positive})</span> | 
                                        <span class="text-red-500 font-bold">Negative (${art.sentiment_negative})</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="font-bold text-slate-700">Analysis:</span>
                                        <span class="text-green-600 font-semibold">${pPct}% Pos</span>
                                        <span class="text-slate-400 font-semibold">${neuPct}% Neu</span>
                                        <span class="text-red-500 font-semibold">${nPct}% Neg</span>
                                    </div>
                                </div>
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
            if (!quiet) {
                container.innerHTML = `
                    <div class="bg-white rounded-2xl border p-12 text-center text-red-500">
                        Failed to load news articles.
                    </div>
                `;
            }
        }
    };

    // Initialize
    const select = document.getElementById('news-country-select');
    loadNews(select.value);
    startAutoRefresh(select.value);

    // Dropdown change
    select.addEventListener('change', (e) => {
        loadNews(e.target.value);
        startAutoRefresh(e.target.value);
    });
});
</script>
@endsection


@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <a href="{{ route('countries.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 p-2.5 rounded-xl transition">
                &larr;
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-800">{{ $country->name }} <span class="text-xl font-normal text-slate-400">({{ $country->code }})</span></h1>
                <p class="text-slate-500 mt-1">Region: {{ $country->region }} | Language: {{ $country->language }}</p>
            </div>
        </div>
        
        <div class="flex gap-3">
            @if($isWatchlisted)
                <form action="{{ route('watchlist.destroy', $country->code) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-medium px-5 py-3 rounded-xl transition flex items-center gap-2">
                        ⭐ Remove from Watchlist
                    </button>
                </form>
            @else
                <form action="{{ route('watchlist.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="country_code" value="{{ $country->code }}">
                    <button type="submit" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border border-yellow-200 font-medium px-5 py-3 rounded-xl transition flex items-center gap-2">
                        ⭐ Add to Watchlist
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Alert Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Top Grid: Key Indicators & Risk Scoring Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Key Economic Indicators -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Core Statistics</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500">Gross Domestic Product (GDP)</span>
                    <span class="font-bold text-slate-800">${{ number_format($country->gdp / 1000000000, 1) }} Billion</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500">Annual Inflation Rate</span>
                    <span class="font-bold text-slate-800">{{ $country->inflation }}%</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500">Total Population</span>
                    <span class="font-bold text-slate-800">{{ number_format($country->population) }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <span class="text-slate-500">Currency</span>
                    <span class="font-bold text-slate-800">{{ $country->currency_name }} ({{ $country->currency_code }})</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Coordinates</span>
                    <span class="font-semibold text-slate-600">{{ $country->latitude }}, {{ $country->longitude }}</span>
                </div>
            </div>
        </div>

        <!-- Risk Scoring Engine Breakdown -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">Supply Chain Risk Scoring</h2>
                <div class="flex items-center gap-3">
                    @php
                        $badge = 'bg-green-100 text-green-800';
                        if (($country->riskScore->risk_level ?? 'Low') === 'High') {
                            $badge = 'bg-red-100 text-red-800';
                        } elseif (($country->riskScore->risk_level ?? 'Low') === 'Medium') {
                            $badge = 'bg-yellow-100 text-yellow-800';
                        }
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-extrabold uppercase {{ $badge }}">
                        {{ $country->riskScore->risk_level ?? 'Low' }} Risk
                    </span>
                    <span class="text-3xl font-extrabold text-slate-800">{{ $country->riskScore->total_score ?? 0 }}/100</span>
                </div>
            </div>

            <!-- Scoring breakdown bars -->
            <div class="space-y-5">
                <div>
                    <div class="flex justify-between text-sm font-medium text-slate-600 mb-1">
                        <span>Weather & Climate Risk (30% weight)</span>
                        <span class="font-bold text-slate-800">{{ $country->riskScore->weather_score ?? 0 }}/100</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $country->riskScore->weather_score ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm font-medium text-slate-600 mb-1">
                        <span>Inflation Risk (20% weight)</span>
                        <span class="font-bold text-slate-800">{{ $country->riskScore->inflation_score ?? 0 }}/100</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-orange-500 h-3 rounded-full" style="width: {{ $country->riskScore->inflation_score ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm font-medium text-slate-600 mb-1">
                        <span>Currency Volatility Risk (10% weight)</span>
                        <span class="font-bold text-slate-800">{{ $country->riskScore->currency_score ?? 0 }}/100</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-yellow-500 h-3 rounded-full" style="width: {{ $country->riskScore->currency_score ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm font-medium text-slate-600 mb-1">
                        <span>News Sentiment & Geopolitics Risk (40% weight)</span>
                        <span class="font-bold text-slate-800">{{ $country->riskScore->news_sentiment_score ?? 0 }}/100</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-purple-500 h-3 rounded-full" style="width: {{ $country->riskScore->news_sentiment_score ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
            
            <p class="text-xs text-slate-400 mt-6 leading-relaxed">
                * Risk Index is calculated using real-time API aggregations: Open-Meteo forecasts, local Consumer Price Indexes, exchange rate standard deviation, and natural language sentiment weights mapped from GNews logs.
            </p>
        </div>
    </div>

    <!-- Historical Trends: 2x2 Grid of Chart.js line charts -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-6">Historical Trends (2020 - 2024)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="border border-slate-100 p-4 rounded-xl shadow-inner bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-600 mb-3 uppercase tracking-wider text-center">GDP Trend (USD Billions)</h3>
                <canvas id="gdpChart" style="max-height:220px"></canvas>
            </div>
            <div class="border border-slate-100 p-4 rounded-xl shadow-inner bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-600 mb-3 uppercase tracking-wider text-center">Inflation Trend (%)</h3>
                <canvas id="inflationChart" style="max-height:220px"></canvas>
            </div>
            <div class="border border-slate-100 p-4 rounded-xl shadow-inner bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-600 mb-3 uppercase tracking-wider text-center">Exchange Rate Trend (vs USD)</h3>
                <canvas id="currencyChart" style="max-height:220px"></canvas>
            </div>
            <div class="border border-slate-100 p-4 rounded-xl shadow-inner bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-600 mb-3 uppercase tracking-wider text-center">Risk Score Trend (0-100)</h3>
                <canvas id="riskChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const metrics = @json($country->metrics);
    const years = metrics.map(m => m.year);
    const gdps = metrics.map(m => m.gdp / 1000000000);
    const inflations = metrics.map(m => m.inflation);
    const rates = metrics.map(m => m.currency_rate);
    const risks = metrics.map(m => m.risk_score);

    const chartConfig = (label, data, color, yLabel) => ({
        type: 'line',
        data: {
            labels: years,
            datasets: [{
                label: label,
                data: data,
                borderColor: color,
                backgroundColor: color + '20',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: color
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    ticks: { font: { size: 10 } },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    ticks: { font: { size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });

    new Chart(document.getElementById('gdpChart'), chartConfig('GDP (Billions)', gdps, '#3b82f6'));
    new Chart(document.getElementById('inflationChart'), chartConfig('Inflation (%)', inflations, '#f97316'));
    new Chart(document.getElementById('currencyChart'), chartConfig('Exchange Rate', rates, '#eab308'));
    new Chart(document.getElementById('riskChart'), chartConfig('Risk Index', risks, '#ef4444'));
});
</script>
@endsection

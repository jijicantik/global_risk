@extends('layouts.app')

@section('content')
<div class="scr-content">
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h1 class="text-3xl font-bold text-slate-800">Country Comparison Engine</h1>
        <p class="text-slate-500 mt-1">Select and compare supply chain risk indicators side-by-side</p>
    </div>

    <!-- Selection Panel -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('countries.compare') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <div>
                <label for="country_a" class="block text-sm font-semibold text-slate-600 mb-2">Select Country A:</label>
                <select name="country_a" id="country_a" required class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Choose country...</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->code }}" {{ request('country_a') == $c->code ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="country_b" class="block text-sm font-semibold text-slate-600 mb-2">Select Country B:</label>
                <select name="country_b" id="country_b" required class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Choose country...</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->code }}" {{ request('country_b') == $c->code ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl transition duration-150 shadow-md">
                    âš– Compare Indicators
                </button>
            </div>
        </form>
    </div>

    <!-- Comparison Results -->
    @if($countryA && $countryB)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Comparison Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-6">Core Indicator Matrix</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50 rounded-lg">
                            <tr>
                                <th class="px-4 py-3">Indicator</th>
                                <th class="px-4 py-3 text-blue-600">{{ $countryA->name }}</th>
                                <th class="px-4 py-3 text-orange-600">{{ $countryB->name }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="px-4 py-4 font-semibold text-slate-800">Risk Score</td>
                                <td class="px-4 py-4 font-bold text-lg text-blue-600">{{ $countryA->riskScore->total_score ?? 'N/A' }}/100</td>
                                <td class="px-4 py-4 font-bold text-lg text-orange-600">{{ $countryB->riskScore->total_score ?? 'N/A' }}/100</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium">Risk Level</td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-slate-100">
                                        {{ $countryA->riskScore->risk_level ?? 'Low' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-slate-100">
                                        {{ $countryB->riskScore->risk_level ?? 'Low' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium">GDP (Nominal)</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">${{ number_format($countryA->gdp / 1000000000, 1) }}B</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">${{ number_format($countryB->gdp / 1000000000, 1) }}B</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium">Inflation Rate</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $countryA->inflation }}%</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $countryB->inflation }}%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium">Population</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ number_format($countryA->population / 1000000, 1) }}M</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ number_format($countryB->population / 1000000, 1) }}M</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium">Currency</td>
                                <td class="px-4 py-4">{{ $countryA->currency_name }} ({{ $countryA->currency_code }})</td>
                                <td class="px-4 py-4">{{ $countryB->currency_name }} ({{ $countryB->currency_code }})</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium">Weather Index</td>
                                <td class="px-4 py-4">{{ $countryA->riskScore->weather_score ?? 0 }}/100</td>
                                <td class="px-4 py-4">{{ $countryB->riskScore->weather_score ?? 0 }}/100</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium">News Sentiment</td>
                                <td class="px-4 py-4">{{ $countryA->riskScore->news_sentiment_score ?? 0 }}/100 (Neg. Weight)</td>
                                <td class="px-4 py-4">{{ $countryB->riskScore->news_sentiment_score ?? 0 }}/100 (Neg. Weight)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Comparison Radar/Bar Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <h2 class="text-xl font-bold text-slate-800 mb-6">Risk Profile Vector</h2>
                <div class="flex-grow flex items-center justify-center" style="min-height:300px">
                    <canvas id="comparisonChart" style="max-height: 320px;"></canvas>
                </div>
            </div>
        </div>
    @endif
</div>

@if($countryA && $countryB)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: [
                'Weather Risk', 
                'Inflation Risk', 
                'Currency Risk', 
                'News Sentiment Risk',
                'Overall Risk'
            ],
            datasets: [
                {
                    label: '{{ $countryA->name }}',
                    data: [
                        {{ $countryA->riskScore->weather_score ?? 0 }},
                        {{ $countryA->riskScore->inflation_score ?? 0 }},
                        {{ $countryA->riskScore->currency_score ?? 0 }},
                        {{ $countryA->riskScore->news_sentiment_score ?? 0 }},
                        {{ $countryA->riskScore->total_score ?? 0 }}
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgb(59, 130, 246)',
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2
                },
                {
                    label: '{{ $countryB->name }}',
                    data: [
                        {{ $countryB->riskScore->weather_score ?? 0 }},
                        {{ $countryB->riskScore->inflation_score ?? 0 }},
                        {{ $countryB->riskScore->currency_score ?? 0 }},
                        {{ $countryB->riskScore->news_sentiment_score ?? 0 }},
                        {{ $countryB->riskScore->total_score ?? 0 }}
                    ],
                    backgroundColor: 'rgba(249, 115, 22, 0.2)',
                    borderColor: 'rgb(249, 115, 22)',
                    pointBackgroundColor: 'rgb(249, 115, 22)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(249, 115, 22)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { display: true },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: { font: { size: 9 } }
                }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>
@endif
@endsection

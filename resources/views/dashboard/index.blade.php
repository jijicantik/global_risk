@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-slate-500 mt-1">Global Supply Chain Risk Intelligence Platform</p>
        </div>
        <form action="{{ route('dashboard.refresh') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl shadow transition duration-150 flex items-center gap-2">
                🔄 Recalculate Risk Scores
            </button>
        </form>
    </div>

    <!-- Alert Banner for Feedback -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 font-medium">Countries Monitored</p>
                <h2 class="text-4xl font-extrabold text-slate-800 mt-2">{{ count($countries) }}</h2>
            </div>
            <div class="stat-icon bg-blue text-white shadow-md">🌍</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 font-medium">Total Ports</p>
                <h2 class="text-4xl font-extrabold text-slate-800 mt-2">{{ number_format($portsCount) }}</h2>
            </div>
            <div class="stat-icon bg-orange text-white shadow-md">🚢</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 font-medium">High Risk Zones</p>
                <h2 class="text-4xl font-extrabold mt-2 {{ $highRiskCount > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $highRiskCount }}</h2>
            </div>
            <div class="stat-icon bg-red text-white shadow-md">⚠</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 font-medium">Daily News Log</p>
                <h2 class="text-4xl font-extrabold text-slate-800 mt-2">{{ $newsTodayCount }}</h2>
            </div>
            <div class="stat-icon bg-green text-white shadow-md">📰</div>
        </div>
    </div>

    <!-- Map + Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Leaflet Map -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-slate-800">Global Risk Heatmap</h2>
                <div class="flex items-center gap-2">
                    <label for="map-country-selector" class="text-sm font-medium text-slate-500">Go to:</label>
                    <select id="map-country-selector" class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select country...</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->code }}" data-lat="{{ $c->latitude }}" data-lng="{{ $c->longitude }}">
                                {{ $c->name }} (Score: {{ $c->riskScore->total_score ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="map" class="rounded-xl overflow-hidden shadow-inner flex-grow" style="height:500px"></div>
        </div>

        <!-- Latest Alerts -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Supply Chain Alerts</h2>
            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                @foreach($alerts as $alert)
                    @php
                        $border = 'border-green-500';
                        $bg = 'bg-green-50/50';
                        $text = 'text-green-700';
                        if ($alert->risk_level === 'High') {
                            $border = 'border-red-500';
                            $bg = 'bg-red-50/50';
                            $text = 'text-red-700';
                        } elseif ($alert->risk_level === 'Medium') {
                            $border = 'border-yellow-500';
                            $bg = 'bg-yellow-50/50';
                            $text = 'text-yellow-700';
                        }
                    @endphp
                    <div class="border-l-4 {{ $border }} {{ $bg }} p-4 rounded-r-xl transition duration-150 hover:shadow-sm">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-slate-800 text-lg">{{ $alert->country->name }}</h4>
                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $text }} bg-white border">
                                {{ $alert->risk_level }} Risk ({{ $alert->total_score }})
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 mt-2">
                            Weather factor is {{ $alert->weather_score }}/100. Inflation at {{ $alert->country->inflation }}%. News sentiment score is {{ $alert->news_sentiment_score }}/100 (Negative news weight).
                        </p>
                        <div class="mt-3 flex justify-between items-center text-xs">
                            <span class="text-slate-400">Updated: {{ $alert->updated_at->diffForHumans() }}</span>
                            <a href="{{ route('countries.show', $alert->country_code) }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                View Intelligence &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Map setup
    const map = L.map('map').setView([20, 10], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Color definitions
    const colors = {
        High: '#ef5350',
        Medium: '#fb8c00',
        Low: '#43a047'
    };

    // Load markers
    const countries = @json($countries);
    const markers = {};

    countries.forEach(country => {
        if (country.latitude && country.longitude && country.risk_score) {
            const riskLevel = country.risk_score.risk_level || 'Low';
            const riskScore = country.risk_score.total_score || 0;
            const color = colors[riskLevel];

            const circle = L.circleMarker([country.latitude, country.longitude], {
                radius: 12 + (riskScore * 0.08),
                fillColor: color,
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);

            const popupContent = `
                <div class="p-2 font-sans">
                    <h3 class="font-bold text-lg text-slate-800 leading-tight">${country.name}</h3>
                    <p class="text-slate-500 text-xs mt-1">Region: ${country.region || 'N/A'}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-xs font-bold text-white" style="background-color: ${color}">
                            ${riskLevel} Risk
                        </span>
                        <span class="text-sm font-bold text-slate-700">Score: ${riskScore}</span>
                    </div>
                    <hr class="my-2 border-slate-100">
                    <div class="space-y-1 text-xs text-slate-600">
                        <div>Weather Risk: ${country.risk_score.weather_score}</div>
                        <div>Inflation Risk: ${country.risk_score.inflation_score}</div>
                        <div>Currency Risk: ${country.risk_score.currency_score}</div>
                        <div>News Sentiment Risk: ${country.risk_score.news_sentiment_score}</div>
                    </div>
                    <div class="mt-3">
                        <a href="/countries/${country.code}" class="block text-center text-xs font-semibold bg-slate-800 text-white rounded-lg py-1.5 hover:bg-slate-700 transition">
                            View Dashboard
                        </a>
                    </div>
                </div>
            `;
            circle.bindPopup(popupContent);
            markers[country.code] = circle;
        }
    });

    // Dropdown pan event
    const selector = document.getElementById('map-country-selector');
    selector.addEventListener('change', (e) => {
        const code = e.target.value;
        if (code && markers[code]) {
            const marker = markers[code];
            const latLng = marker.getLatLng();
            map.setView(latLng, 5);
            marker.openPopup();
        }
    });
});
</script>
@endsection
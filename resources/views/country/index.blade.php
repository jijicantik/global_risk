@extends('layouts.app')

@section('content')
<div class="scr-content">
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Global Countries</h1>
            <p class="text-slate-500 mt-1">Country profiles, indicators, and risk summaries</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('countries.compare') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl shadow transition duration-150 flex items-center gap-2">
                ⚖ Compare Countries
            </a>
        </div>
    </div>

    <!-- Country Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($countries as $c)
            @php
                $lvl = strtolower($c->riskScore->risk_level ?? 'low');
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-200 p-6 flex flex-col justify-between">
                <div>
                    <!-- Flag + Country name -->
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $c->region }}</span>
                            <h2 class="text-2xl font-bold text-slate-800 mt-1 flex items-center gap-2">
                                {{ $c->name }}
                                <span class="text-sm font-normal text-slate-400">({{ $c->code }})</span>
                            </h2>
                        </div>
                        <span class="scr-badge {{ $lvl }}">
                            {{ $c->riskScore->risk_level ?? 'Low' }} Risk
                        </span>
                    </div>

                    <!-- Stats Table -->
                    <div class="mt-6 space-y-3 text-sm">
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">GDP (Nominal):</span>
                            <span class="font-semibold text-slate-700">${{ number_format($c->gdp / 1000000000, 1) }}B</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Inflation Rate:</span>
                            <span class="font-semibold text-slate-700">{{ $c->inflation }}%</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Population:</span>
                            <span class="font-semibold text-slate-700">{{ number_format($c->population / 1000000, 1) }}M</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Currency:</span>
                            <span class="font-semibold text-slate-700">{{ $c->currency_name }} ({{ $c->currency_code }})</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Risk Score:</span>
                            <span class="font-bold text-slate-800">{{ $c->riskScore->total_score ?? 'N/A' }}/100</span>
                        </div>
                        <!-- Live Weather Row -->
                        <div class="flex justify-between items-center pt-1"
                             data-weather-card
                             data-lat="{{ $c->latitude }}"
                             data-lng="{{ $c->longitude }}">
                            <span class="text-slate-400">Weather Now:</span>
                            <span class="weather-display font-semibold text-slate-700 flex items-center gap-1">
                                <span class="weather-icon text-base">⏳</span>
                                <span class="weather-temp text-xs">loading...</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-50">
                    <a href="{{ route('countries.show', $c->code) }}" class="block text-center text-sm font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 py-3 rounded-xl transition duration-150">
                        View Detailed Intelligence &rarr;
                    </a>
                </div>
            </div>
        @endforeach
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const weatherCards = document.querySelectorAll('[data-weather-card]');

    const getWeatherIcon = (code, rain) => {
        if (rain > 10) return '🌧️';
        if (rain > 1) return '🌦️';
        if (code >= 95) return '⛈️';
        if (code >= 71 && code <= 86) return '❄️';
        if (code >= 45 && code <= 48) return '🌫️';
        if (code >= 3) return '☁️';
        if (code >= 1) return '⛅';
        return '☀️';
    };

    // Stagger requests to avoid API rate limits (200ms between each)
    weatherCards.forEach((card, index) => {
        const lat = card.dataset.lat;
        const lng = card.dataset.lng;
        const iconEl = card.querySelector('.weather-icon');
        const tempEl = card.querySelector('.weather-temp');

        setTimeout(async () => {
            try {
                const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,precipitation,weather_code`);
                const data = await res.json();
                if (data && data.current) {
                    const temp = Math.round(data.current.temperature_2m);
                    const rain = data.current.precipitation;
                    const code = data.current.weather_code;
                    iconEl.textContent = getWeatherIcon(code, rain);
                    tempEl.textContent = `${temp}°C`;
                }
            } catch (e) {
                iconEl.textContent = '—';
                tempEl.textContent = 'N/A';
            }
        }, index * 200);
    });
});
</script>
@endsection

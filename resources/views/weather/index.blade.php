@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Global Weather Monitoring</h1>
            <p class="text-slate-500 mt-1">Geospatial weather mapping and logistics storm tracking</p>
        </div>
        <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <label for="weather-country-select" class="text-sm font-semibold text-slate-500">Track Country:</label>
            <select id="weather-country-select" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($countries as $c)
                    <option value="{{ $c->code }}" data-lat="{{ $c->latitude }}" data-lng="{{ $c->longitude }}" {{ $selectedCountry->code === $c->code ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Weather Main Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Weather Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-6">Conditions: <span id="weather-country-name">{{ $selectedCountry->name }}</span></h2>
                
                <div class="text-center py-6">
                    <div id="weather-icon" class="text-6xl mb-2">☁</div>
                    <div id="weather-temp" class="text-4xl font-extrabold text-slate-800">-- &deg;C</div>
                    <div id="weather-desc" class="text-sm text-slate-400 mt-1 uppercase font-semibold tracking-wider">Loading...</div>
                </div>

                <div class="mt-8 space-y-4 text-sm">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-slate-500">Precipitation (Rain/Snow)</span>
                        <span id="weather-precipitation" class="font-bold text-slate-800">-- mm</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-slate-500">Wind Speed</span>
                        <span id="weather-wind" class="font-bold text-slate-800">-- km/h</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-slate-500">Logistics Storm Risk</span>
                        <span id="weather-storm-level" class="px-2 py-0.5 rounded text-xs font-bold uppercase border">--</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-blue-50 border border-blue-100 p-4 rounded-xl">
                <p class="text-xs text-blue-700 leading-relaxed font-medium">
                    📍 Logistics Warning: High wind speed (> 40 km/h) or precipitation (> 5mm) may result in container loading delays at ports.
                </p>
            </div>
        </div>

        <!-- Geospatial Weather Map -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Live Weather Radar Location</h2>
            <div id="weather-map" class="rounded-xl overflow-hidden shadow-inner" style="height:450px"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Map setup
    const map = L.map('weather-map').setView([{{ $selectedCountry->latitude }}, {{ $selectedCountry->longitude }}], 4);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    let activeMarker = null;

    // Fetch and show weather
    const updateWeather = async (code, name, lat, lng) => {
        document.getElementById('weather-country-name').innerText = name;
        document.getElementById('weather-temp').innerText = '-- °C';
        document.getElementById('weather-desc').innerText = 'Loading...';
        document.getElementById('weather-precipitation').innerText = '-- mm';
        document.getElementById('weather-wind').innerText = '-- km/h';

        map.setView([lat, lng], 5);

        if (activeMarker) {
            map.removeLayer(activeMarker);
        }

        try {
            const res = await axios.get(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,precipitation,wind_speed_10m,weather_code`);
            if (res.data && res.data.current) {
                const c = res.data.current;
                const temp = Math.round(c.temperature_2m);
                const wind = c.wind_speed_10m;
                const rain = c.precipitation;
                const codeVal = c.weather_code;

                // Simple interpretation
                let desc = 'Clear/Cloudy';
                let icon = '☀️';
                if (rain > 10) {
                    desc = 'Heavy Rain';
                    icon = '🌧️';
                } elseif (rain > 1) {
                    desc = 'Rainy';
                    icon = '🌦️';
                } elseif (codeVal >= 71 && codeVal <= 86) {
                    desc = 'Snowy';
                    icon = '❄️';
                } elseif (codeVal >= 95) {
                    desc = 'Thunderstorm';
                    icon = '⛈️';
                } elseif (codeVal >= 45 && codeVal <= 48) {
                    desc = 'Foggy';
                    icon = '🌫️';
                }

                // Determine risk level
                let stormLvl = 'Low';
                let classList = 'bg-green-100 text-green-800 border-green-200';
                if (wind > 40 || rain > 10 || codeVal >= 95) {
                    stormLvl = 'High';
                    classList = 'bg-red-100 text-red-800 border-red-200';
                } elseif (wind > 20 || rain > 2) {
                    stormLvl = 'Medium';
                    classList = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                }

                document.getElementById('weather-icon').innerText = icon;
                document.getElementById('weather-temp').innerHTML = `${temp} &deg;C`;
                document.getElementById('weather-desc').innerText = desc;
                document.getElementById('weather-precipitation').innerText = `${rain} mm`;
                document.getElementById('weather-wind').innerText = `${wind} km/h`;
                
                const badge = document.getElementById('weather-storm-level');
                badge.className = `px-2 py-0.5 rounded text-xs font-bold uppercase border ${classList}`;
                badge.innerText = `${stormLvl} Risk`;

                // Add marker
                activeMarker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`<b>${name} Weather</b><br>${desc}, ${temp}°C<br>Wind: ${wind} km/h`)
                    .openPopup();
            }
        } catch (e) {
            console.error(e);
            document.getElementById('weather-desc').innerText = 'Offline / Error';
        }
    };

    // Initialize
    const startSelect = document.getElementById('weather-country-select');
    const startOpt = startSelect.options[startSelect.selectedIndex];
    updateWeather(startSelect.value, startOpt.text, startOpt.dataset.lat, startOpt.dataset.lng);

    // Dropdown change
    startSelect.addEventListener('change', (e) => {
        const opt = e.target.options[e.target.selectedIndex];
        updateWeather(e.target.value, opt.text, opt.dataset.lat, opt.dataset.lng);
    });
});
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="scr-content">
<div class="space-y-6">

    <!-- Header & Country Selection -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Global Weather Monitoring</h1>
            <p class="text-slate-500 mt-1">Pemantauan real-time Hujan, Badai, dan Angin Kencang pada rute logistik maritim</p>
        </div>
        <div class="flex items-center gap-3 bg-slate-50 p-2.5 rounded-xl border border-slate-200 w-full md:w-auto">
            <label for="weather-country-select" class="text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pilih Negara:</label>
            <select id="weather-country-select" class="bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64">
                @foreach($countries as $c)
                    <option value="{{ $c->code }}" 
                            data-lat="{{ $c->latitude }}" 
                            data-lng="{{ $c->longitude }}" 
                            data-ports='@json($c->ports)'
                            {{ $selectedCountry->code === $c->code ? 'selected' : '' }}>
                        {{ $c->name }} ({{ count($c->ports) }} Pelabuhan)
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- 3 Weather Monitoring KPI Cards (Hujan, Badai, Angin Kencang) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Card 1: Hujan (Precipitation & Rain) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-bl-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-2xl">🌧️</span>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Intensitas Hujan</h3>
                            <p class="text-xs text-slate-400">Precipitation & Rain Rate</p>
                        </div>
                    </div>
                    <span id="rain-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                        Memuat...
                    </span>
                </div>

                <div class="my-4">
                    <div class="flex items-baseline gap-2">
                        <span id="rain-value" class="text-4xl font-black text-slate-800">--</span>
                        <span class="text-sm font-bold text-slate-500">mm / jam</span>
                    </div>
                    <p id="rain-status" class="text-xs font-semibold text-slate-500 mt-2">Menghubungkan ke sensor stasiun cuaca...</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 text-xs space-y-2">
                <div class="flex justify-between">
                    <span class="text-slate-400">Tingkat Curah Hujan:</span>
                    <span id="rain-level-text" class="font-semibold text-slate-700">--</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Dampak Logistik:</span>
                    <span id="rain-impact-text" class="font-semibold text-slate-700">--</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Badai (Storm & Thunderstorm Risk) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-red-500/5 rounded-bl-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-2xl">⛈️</span>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Risiko Badai</h3>
                            <p class="text-xs text-slate-400">Thunderstorm & Lightning</p>
                        </div>
                    </div>
                    <span id="storm-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        Memuat...
                    </span>
                </div>

                <div class="my-4">
                    <div class="flex items-baseline gap-2">
                        <span id="storm-condition" class="text-2xl font-black text-slate-800">--</span>
                    </div>
                    <p id="storm-code-desc" class="text-xs font-semibold text-slate-500 mt-2">Kode Cuaca WMO: --</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 text-xs space-y-2">
                <div class="flex justify-between">
                    <span class="text-slate-400">Tingkat Bahaya Badai:</span>
                    <span id="storm-danger-text" class="font-semibold text-slate-700">--</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Status Pelayaran:</span>
                    <span id="storm-shipping-text" class="font-semibold text-slate-700">--</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Angin Kencang (High Wind & Gusts) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-2xl">💨</span>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Kecepatan Angin</h3>
                            <p class="text-xs text-slate-400">Wind Speed & Gusts</p>
                        </div>
                    </div>
                    <span id="wind-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                        Memuat...
                    </span>
                </div>

                <div class="my-4">
                    <div class="flex items-baseline gap-2">
                        <span id="wind-value" class="text-4xl font-black text-slate-800">--</span>
                        <span class="text-sm font-bold text-slate-500">km / jam</span>
                    </div>
                    <p id="wind-gust-text" class="text-xs font-semibold text-slate-500 mt-2">Hembusan Maksimal: -- km/h</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 text-xs space-y-2">
                <div class="flex justify-between">
                    <span class="text-slate-400">Skala Beaufort:</span>
                    <span id="wind-beaufort-text" class="font-semibold text-slate-700">--</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Operasional Crane Port:</span>
                    <span id="wind-crane-text" class="font-semibold text-slate-700">--</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Interactive World Weather Map Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Peta Radar Cuaca Maritim & Pelabuhan</h2>
                <p class="text-slate-500 text-sm mt-0.5">
                    Menampilkan lokasi indikator <span class="font-bold text-blue-600">Hujan (🌧️)</span>, <span class="font-bold text-red-600">Badai (⛈️)</span>, dan <span class="font-bold text-amber-600">Angin Kencang (💨)</span> di <span id="map-country-name" class="font-bold text-slate-800">{{ $selectedCountry->name }}</span>
                </p>
            </div>

            <!-- Layer Filter Buttons -->
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="filterMapLayer('all')" id="btn-layer-all" class="weather-filter-btn active bg-slate-800 text-white px-3.5 py-1.5 rounded-xl text-xs font-semibold shadow-sm transition">
                    🌐 Semua Kondisi
                </button>
                <button type="button" onclick="filterMapLayer('rain')" id="btn-layer-rain" class="weather-filter-btn bg-slate-100 text-slate-600 hover:bg-slate-200 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition">
                    🌧️ Hujan
                </button>
                <button type="button" onclick="filterMapLayer('storm')" id="btn-layer-storm" class="weather-filter-btn bg-slate-100 text-slate-600 hover:bg-slate-200 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition">
                    ⛈️ Badai
                </button>
                <button type="button" onclick="filterMapLayer('wind')" id="btn-layer-wind" class="weather-filter-btn bg-slate-100 text-slate-600 hover:bg-slate-200 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition">
                    💨 Angin Kencang
                </button>
            </div>
        </div>

        <!-- Map Container -->
        <div id="weather-map" class="rounded-xl shadow-inner border border-slate-100" style="height: 520px; z-index: 10;"></div>
    </div>

    <!-- Country Port Weather Stations Breakdown Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Stasiun Cuaca & Pelabuhan di <span id="stations-country-name">{{ $selectedCountry->name }}</span></h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Nama Pelabuhan / Stasiun</th>
                        <th class="px-4 py-3">UN/LOCODE</th>
                        <th class="px-4 py-3">Curah Hujan (🌧️)</th>
                        <th class="px-4 py-3">Kecepatan Angin (💨)</th>
                        <th class="px-4 py-3">Status Badai (⛈️)</th>
                        <th class="px-4 py-3">Status Risiko</th>
                    </tr>
                </thead>
                <tbody id="port-stations-tbody" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-400">
                            Memuat data stasiun pelabuhan...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<script>
let map = null;
let currentMarkers = [];
let riskCircles = [];
let currentFilter = 'all';
let selectedPorts = [];
let currentLat = {{ $selectedCountry->latitude }};
let currentLng = {{ $selectedCountry->longitude }};
let currentCountryName = "{{ $selectedCountry->name }}";

document.addEventListener('DOMContentLoaded', () => {
    // Leaflet map initialization
    map = L.map('weather-map').setView([currentLat, currentLng], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const selectEl = document.getElementById('weather-country-select');
    
    // Initial fetch
    loadCountryWeather(selectEl);

    // On country select change
    selectEl.addEventListener('change', (e) => {
        loadCountryWeather(e.target);
    });
});

async function loadCountryWeather(selectEl) {
    const opt = selectEl.options[selectEl.selectedIndex];
    currentCountryName = opt.text.split('(')[0].trim();
    currentLat = Number(opt.dataset.lat);
    currentLng = Number(opt.dataset.lng);
    
    try {
        selectedPorts = JSON.parse(opt.dataset.ports || '[]');
    } catch(e) {
        selectedPorts = [];
    }

    document.getElementById('map-country-name').innerText = currentCountryName;
    document.getElementById('stations-country-name').innerText = currentCountryName;

    // Pan map to country center
    map.flyTo([currentLat, currentLng], 5, { duration: 1.2 });

    // Fetch primary weather for country center
    fetchPrimaryWeather(currentLat, currentLng);

    // Fetch weather for all ports of this country
    fetchPortStationsWeather(selectedPorts);
}

// Fetch primary weather for selected country center
async function fetchPrimaryWeather(lat, lng) {
    try {
        const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,precipitation,rain,showers,weather_code,wind_speed_10m,wind_gusts_10m`);
        const data = await res.json();
        
        if (data && data.current) {
            const c = data.current;
            const temp = Math.round(c.temperature_2m);
            const rain = c.precipitation || 0;
            const wind = c.wind_speed_10m || 0;
            const gusts = c.wind_gusts_10m || Math.round(wind * 1.3);
            const code = c.weather_code || 0;

            // --- 1. Rain Metrics ---
            document.getElementById('rain-value').innerText = rain;
            const rainBadge = document.getElementById('rain-badge');
            const rainStatus = document.getElementById('rain-status');
            const rainLevelText = document.getElementById('rain-level-text');
            const rainImpactText = document.getElementById('rain-impact-text');

            if (rain > 10) {
                rainBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-600 text-white shadow-sm';
                rainBadge.innerText = 'Hujan Sangat Lebat';
                rainStatus.innerText = '🚨 Peringatan: Presipitasi tinggi terpantau di wilayah ini';
                rainLevelText.innerText = 'Tinggi (>10 mm/h)';
                rainImpactText.innerText = 'Risiko Genangan & Penundaan Bongkar Muat';
            } else if (rain > 2) {
                rainBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200';
                rainBadge.innerText = 'Hujan Sedang';
                rainStatus.innerText = '🌧️ Curah hujan sedang berlangsung';
                rainLevelText.innerText = 'Sedang (2 - 10 mm/h)';
                rainImpactText.innerText = 'Penyesuaian Kecepatan Kapal';
            } else if (rain > 0.1) {
                rainBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-700 border border-sky-100';
                rainBadge.innerText = 'Hujan Ringan / Gerimis';
                rainStatus.innerText = '🌦️ Gerimis ringan dilaporkan';
                rainLevelText.innerText = 'Ringan (<2 mm/h)';
                rainImpactText.innerText = 'Kondisi Logistik Normal';
            } else {
                rainBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100';
                rainBadge.innerText = 'Cerah / Tidak Hujan';
                rainStatus.innerText = '☀️ Kondisi atmosfer kering dan aman';
                rainLevelText.innerText = 'Nihil (0 mm/h)';
                rainImpactText.innerText = 'Bongkar Muat Lancar';
            }

            // --- 2. Storm Metrics ---
            const stormBadge = document.getElementById('storm-badge');
            const stormCond = document.getElementById('storm-condition');
            const stormCodeDesc = document.getElementById('storm-code-desc');
            const stormDanger = document.getElementById('storm-danger-text');
            const stormShip = document.getElementById('storm-shipping-text');

            let stormLevel = 'Aman';
            let stormDesc = 'Cuaca Stabil';

            if (code >= 95) {
                stormLevel = 'Tinggi (Badai Petir)';
                stormDesc = '⛈️ Thunderstorm / Badai Petir Ekstrem';
                stormBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-red-600 text-white shadow-sm';
                stormBadge.innerText = 'Peringatan Badai';
                stormDanger.innerText = 'Tinggi / Bahaya Petir';
                stormShip.innerText = '⚠️ Peringatan Keras Pelayaran';
            } else if (code >= 80 || rain > 15) {
                stormLevel = 'Sedang (Hujan Badai)';
                stormDesc = '🌧️ Heavy Rain Showers / Squall';
                stormBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200';
                stormBadge.innerText = 'Waspada Badai';
                stormDanger.innerText = 'Sedang / Turbulensi';
                stormShip.innerText = 'Waspada Gelombang Tinggi';
            } else if (code >= 71) {
                stormLevel = 'Salju / Pembekuan';
                stormDesc = '❄️ Snowfall / Freezing Conditions';
                stormBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100';
                stormBadge.innerText = 'Cuaca Dingin Ekstrem';
                stormDanger.innerText = 'Sedang / Pembekuan';
                stormShip.innerText = 'Waspada Pembekuan Dek';
            } else if (code >= 45) {
                stormLevel = 'Kabut Tebal';
                stormDesc = '🌫️ Heavy Fog / Visibility Low';
                stormBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200 text-slate-800';
                stormBadge.innerText = 'Kabut Tebal';
                stormDanger.innerText = 'Jarak Pandang Rendah';
                stormShip.innerText = 'Gunakan Navigasi Radar';
            } else {
                stormBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100';
                stormBadge.innerText = 'Kondisi Aman';
                stormDanger.innerText = 'Rendah / Normal';
                stormShip.innerText = 'Pelayaran Normal';
            }

            stormCond.innerText = stormDesc;
            stormCodeDesc.innerText = `Kode Cuaca WMO: ${code} (${stormLevel})`;

            // --- 3. Wind Metrics ---
            document.getElementById('wind-value').innerText = wind;
            document.getElementById('wind-gust-text').innerText = `Hembusan Maksimal: ${gusts} km/h`;
            
            const windBadge = document.getElementById('wind-badge');
            const windBeaufort = document.getElementById('wind-beaufort-text');
            const windCrane = document.getElementById('wind-crane-text');

            let beaufort = 'Skala 1-3 (Angin Sepoi-sepoi)';
            if (wind > 50) {
                windBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-red-600 text-white shadow-sm';
                windBadge.innerText = 'Angin Badai / Ekstrem';
                beaufort = 'Skala 7-9 (Gale / Strong Wind)';
                windCrane.innerText = '⛔ Hentikan Crane & Berth';
            } else if (wind > 25) {
                windBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200';
                windBadge.innerText = 'Angin Kencang';
                beaufort = 'Skala 4-6 (Fresh Breeze)';
                windCrane.innerText = '⚠️ Waspada Operasional Crane';
            } else {
                windBadge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100';
                windBadge.innerText = 'Angin Normal';
                beaufort = 'Skala 1-3 (Light / Moderate)';
                windCrane.innerText = 'Aman Bebas Beroperasi';
            }
            windBeaufort.innerText = beaufort;
        }
    } catch(e) {
        console.error('Error fetching primary weather:', e);
    }
}

// Fetch weather for all ports in selected country & render Leaflet markers + table
async function fetchPortStationsWeather(ports) {
    const tbody = document.getElementById('port-stations-tbody');
    
    // Clear old map layers
    currentMarkers.forEach(m => map.removeLayer(m));
    riskCircles.forEach(c => map.removeLayer(c));
    currentMarkers = [];
    riskCircles = [];

    if (!ports || ports.length === 0) {
        // Fallback if no specific ports registered: use country center point as station
        ports = [{
            name: `Stasiun Utama ${currentCountryName}`,
            code: `${currentCountryName.substr(0,2).toUpperCase()}MAIN`,
            latitude: currentLat,
            longitude: currentLng
        }];
    }

    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="px-4 py-6 text-center text-slate-400">
                🔄 Memperbarui sensor cuaca real-time untuk ${ports.length} stasiun pelabuhan...
            </td>
        </tr>
    `;

    let htmlBuffer = '';

    for (let i = 0; i < ports.length; i++) {
        const port = ports[i];
        try {
            const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${port.latitude}&longitude=${port.longitude}&current=temperature_2m,precipitation,weather_code,wind_speed_10m`);
            const data = await res.json();
            
            let temp = '--';
            let rain = 0;
            let wind = 0;
            let code = 0;

            if (data && data.current) {
                temp = Math.round(data.current.temperature_2m);
                rain = data.current.precipitation || 0;
                wind = data.current.wind_speed_10m || 0;
                code = data.current.weather_code || 0;
            }

            const isStorm = (code >= 95 || (rain > 10 && wind > 30));
            const isHeavyRain = (rain > 2);
            const isHighWind = (wind > 25);

            // Determine Risk Badge
            let riskBadge = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-green-50 text-green-700 border border-green-100">Kondisi Normal</span>';
            if (isStorm) {
                riskBadge = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-red-600 text-white shadow-sm">🔴 Bahaya Badai</span>';
            } else if (isHeavyRain && isHighWind) {
                riskBadge = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">🟠 Risiko Tinggi</span>';
            } else if (isHeavyRain || isHighWind) {
                riskBadge = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">🟡 Risiko Sedang</span>';
            }

            // Build Table Row
            htmlBuffer += `
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 font-bold text-slate-800 flex items-center gap-2">
                        ⚓ ${port.name}
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-bold text-xs text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded">${port.code}</span>
                    </td>
                    <td class="px-4 py-3 font-semibold ${rain > 5 ? 'text-blue-600 font-bold' : 'text-slate-700'}">
                        🌧️ ${rain} mm/h
                    </td>
                    <td class="px-4 py-3 font-semibold ${wind > 25 ? 'text-amber-600 font-bold' : 'text-slate-700'}">
                        💨 ${wind} km/h
                    </td>
                    <td class="px-4 py-3 font-semibold">
                        ${code >= 95 ? '<span class="text-red-600 font-bold">⛈️ Badai Petir</span>' : (rain > 5 ? '<span class="text-blue-600">🌧️ Hujan Lebat</span>' : '<span class="text-slate-500">🌤️ Cerah / Stabil</span>')}
                    </td>
                    <td class="px-4 py-3">
                        ${riskBadge}
                    </td>
                </tr>
            `;

            // Create Leaflet Marker & Circle
            createWeatherMarker(port, temp, rain, wind, code, isStorm, isHeavyRain, isHighWind);

        } catch(e) {
            console.error('Error fetching port weather:', e);
        }
    }

    tbody.innerHTML = htmlBuffer;
}

// Create custom colored Leaflet markers and risk circles
function createWeatherMarker(port, temp, rain, wind, code, isStorm, isHeavyRain, isHighWind) {
    let markerColor = '#3b82f6'; // Default Blue (Rain)
    let iconEmoji = '🌧️';
    let category = 'rain';

    if (isStorm) {
        markerColor = '#ef4444'; // Red (Storm)
        iconEmoji = '⛈️';
        category = 'storm';
    } else if (isHighWind && !isHeavyRain) {
        markerColor = '#f59e0b'; // Amber (Wind)
        iconEmoji = '💨';
        category = 'wind';
    } else if (!isHeavyRain && !isHighWind && !isStorm) {
        iconEmoji = '☀️';
        markerColor = '#10b981'; // Green
        category = 'normal';
    }

    const popupContent = `
        <div class="p-2 font-sans min-w-[200px]">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xl">${iconEmoji}</span>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm leading-tight">${port.name}</h4>
                    <p class="text-xs text-blue-600 font-bold">${port.code}</p>
                </div>
            </div>
            <div class="mt-2 space-y-1 text-xs text-slate-600 border-t pt-2">
                <div class="flex justify-between"><span>Suhu Air/Udara:</span> <b>${temp}°C</b></div>
                <div class="flex justify-between"><span>Curah Hujan (🌧️):</span> <b>${rain} mm/h</b></div>
                <div class="flex justify-between"><span>Kecepatan Angin (💨):</span> <b>${wind} km/h</b></div>
                <div class="flex justify-between"><span>Status Badai (⛈️):</span> <b>${code >= 95 ? 'Peringatan Badai' : 'Normal'}</b></div>
            </div>
        </div>
    `;

    const marker = L.marker([port.latitude, port.longitude]).addTo(map);
    marker.bindPopup(popupContent);
    marker.weatherCategory = category;
    currentMarkers.push(marker);

    // Draw risk radius circle overlay for high weather intensity
    if (isStorm || isHeavyRain || isHighWind) {
        const circle = L.circle([port.latitude, port.longitude], {
            color: markerColor,
            fillColor: markerColor,
            fillOpacity: 0.15,
            radius: isStorm ? 35000 : (isHeavyRain ? 25000 : 20000)
        }).addTo(map);
        circle.weatherCategory = category;
        riskCircles.push(circle);
    }
}

// Layer filtering
function filterMapLayer(layerType) {
    currentFilter = layerType;

    const buttons = document.querySelectorAll('.weather-filter-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-slate-800', 'text-white');
        btn.classList.add('bg-slate-100', 'text-slate-600');
    });

    const activeBtn = document.getElementById(`btn-layer-${layerType}`);
    if (activeBtn) {
        activeBtn.classList.add('active', 'bg-slate-800', 'text-white');
        activeBtn.classList.remove('bg-slate-100', 'text-slate-600');
    }

    currentMarkers.forEach(m => {
        if (layerType === 'all' || m.weatherCategory === layerType || (layerType === 'rain' && m.weatherCategory === 'normal')) {
            map.addLayer(m);
        } else {
            map.removeLayer(m);
        }
    });

    riskCircles.forEach(c => {
        if (layerType === 'all' || c.weatherCategory === layerType) {
            map.addLayer(c);
        } else {
            map.removeLayer(c);
        }
    });
}
</script>
@endsection

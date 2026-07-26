@extends('layouts.app')

@section('content')
<div class="scr-content">
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Port Location Dashboard</h1>
            <p class="text-slate-500 mt-1">Geospatial ports mapping from World Port Index Dataset</p>
        </div>
        <form action="{{ route('ports.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search ports or countries..." class="border border-slate-200 rounded-xl px-4 py-2.5 w-72 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition">
                Search
            </button>
        </form>
    </div>

    <!-- Map & Port Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Ports List -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Ports Dataset ({{ count($ports) }})</h2>
            
            <div class="space-y-3 max-h-[480px] overflow-y-auto pr-1 flex-grow">
                @forelse($ports as $port)
                    <button class="w-full text-left p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-100 hover:border-slate-200 transition flex justify-between items-center port-selector" 
                            data-name="{{ $port->name }}" 
                            data-lat="{{ $port->latitude }}" 
                            data-lng="{{ $port->longitude }}" 
                            data-code="{{ $port->code }}"
                            data-country="{{ $port->country_name }}">
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">{{ $port->name }}</h4>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $port->country_name }} | {{ $port->code }}</p>
                        </div>
                        <span class="text-slate-400 text-sm">&rarr;</span>
                    </button>
                @empty
                    <div class="text-center p-8 text-slate-400 text-sm">
                        No ports found matching query.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Leaflet Map -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Interactive Marine Traffic Map</h2>
            <div id="ports-map" class="rounded-xl overflow-hidden shadow-inner" style="height:520px"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Map setup
    const map = L.map('ports-map').setView([20, 20], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const ports = @json($ports);
    const markers = {};

    // Add markers
    ports.forEach(port => {
        if (port.latitude && port.longitude) {
            const marker = L.marker([port.latitude, port.longitude]).addTo(map);
            const popupContent = `
                <div class="p-1 font-sans">
                    <h4 class="font-bold text-slate-800 text-sm leading-tight">${port.name}</h4>
                    <p class="text-slate-400 text-xs mt-0.5">${port.country_name} (${port.country_code})</p>
                    <div class="mt-2 text-xs font-semibold text-slate-600">UN/LOCODE: ${port.code || 'N/A'}</div>
                    <div class="text-xs text-slate-500 mt-0.5">Coords: ${port.latitude}, ${port.longitude}</div>
                </div>
            `;
            marker.bindPopup(popupContent);
            markers[port.code] = marker;
        }
    });

    // Zoom on button click
    const buttons = document.querySelectorAll('.port-selector');
    buttons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const code = btn.dataset.code;
            const lat = Number(btn.dataset.lat);
            const lng = Number(btn.dataset.lng);

            if (markers[code]) {
                map.setView([lat, lng], 8);
                markers[code].openPopup();
            }
        });
    });
});
</script>
@endsection


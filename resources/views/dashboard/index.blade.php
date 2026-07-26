@extends('layouts.app')

@section('content')
<div class="scr-page-layout">

    {{-- =====================================================
         MAIN DASHBOARD CONTENT
    ====================================================== --}}
    <div class="scr-page-main">



        {{-- KPI Cards --}}
        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:14px; margin-bottom:16px;">

            {{-- Total Countries --}}
            <div class="scr-kpi">
                <div class="scr-kpi-top">
                    <div>
                        <div class="scr-kpi-label">Total Countries</div>
                        <div class="scr-kpi-value">{{ count($countries) }}</div>
                        <div class="scr-kpi-sub">
                            <span class="scr-kpi-badge" style="background:rgba(59,130,246,0.12);color:#60a5fa;">Tracked Countries</span>
                        </div>
                    </div>
                    <div class="scr-kpi-icon blue">🌍</div>
                </div>
                <div style="height:36px; position:relative; margin-top:8px;">
                    <canvas id="sparkCountries"></canvas>
                </div>
            </div>

            {{-- High Risk Countries --}}
            <div class="scr-kpi">
                <div class="scr-kpi-top">
                    <div>
                        <div class="scr-kpi-label">High Risk Countries</div>
                        <div class="scr-kpi-value" style="color:var(--risk-high);">{{ $highRiskCount }}</div>
                        <div class="scr-kpi-sub">
                            <span class="scr-badge high">High Risk</span>
                        </div>
                    </div>
                    <div class="scr-kpi-icon red">⚠️</div>
                </div>
                <div style="height:36px; position:relative; margin-top:8px;">
                    <canvas id="sparkHighRisk"></canvas>
                </div>
            </div>

            {{-- Active Alerts --}}
            <div class="scr-kpi">
                <div class="scr-kpi-top">
                    <div>
                        <div class="scr-kpi-label">Active Alerts</div>
                        <div class="scr-kpi-value">{{ number_format($newsTodayCount + count($alerts)) }}</div>
                        <div class="scr-kpi-sub">
                            <span class="scr-kpi-badge" style="background:rgba(34,197,94,0.12);color:#4ade80;">Today</span>
                        </div>
                    </div>
                    <div class="scr-kpi-icon green">📊</div>
                </div>
                <div style="height:36px; position:relative; margin-top:8px;">
                    <canvas id="sparkAlerts"></canvas>
                </div>
            </div>

        </div>

        {{-- Global Risk Map + Alerts --}}
        <div style="display:grid; grid-template-columns: 1fr 320px; gap:14px; margin-bottom:16px;">

            {{-- Map --}}
            <div class="scr-card" style="padding:16px;">
                <div class="scr-card-header" style="margin-bottom:12px;">
                    <div class="scr-card-title">🗺 Global Risk Map</div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="display:flex; align-items:center; gap:5px; font-size:10px; color:var(--text-muted);">
                            <span style="width:8px;height:8px;border-radius:50%;background:var(--risk-high);display:inline-block;"></span> High Risk
                            <span style="width:8px;height:8px;border-radius:50%;background:var(--risk-medium);display:inline-block;margin-left:4px;"></span> Medium Risk
                            <span style="width:8px;height:8px;border-radius:50%;background:var(--risk-low);display:inline-block;margin-left:4px;"></span> Low Risk
                        </div>
                        <select id="map-country-selector" style="background:var(--bg-primary);border:1px solid var(--border-light);border-radius:7px;padding:5px 10px;color:var(--text-secondary);font-size:11px;outline:none;cursor:pointer;">
                            <option value="">Select country...</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->code }}" data-lat="{{ $c->latitude }}" data-lng="{{ $c->longitude }}">
                                    {{ $c->name }} ({{ $c->riskScore->total_score ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="dashboard-map" style="height:380px; border-radius:10px;"></div>
            </div>

            {{-- Latest Risk Alerts --}}
            <div class="scr-card" style="padding:16px; display:flex; flex-direction:column;">
                <div class="scr-card-header">
                    <div class="scr-card-title">🚨 Latest Risk Alerts</div>
                    <span class="scr-badge blue">Live</span>
                </div>

                <div style="flex:1; overflow-y:auto; display:flex; flex-direction:column; gap:8px;">
                    @foreach($alerts as $alert)
                        @php
                            $lvl = strtolower($alert->risk_level);
                            $icons = ['high' => '🔴', 'medium' => '🟡', 'low' => '🟢'];
                            $icon = $icons[$lvl] ?? '⚪';
                        @endphp
                        <div class="scr-alert {{ $lvl }}">
                            <div class="scr-alert-icon {{ $lvl }}">{{ $icon }}</div>
                            <div class="scr-alert-body">
                                <div class="scr-alert-title">{{ $alert->country->name ?? 'Unknown' }}</div>
                                <div class="scr-alert-sub">Weather {{ $alert->weather_score }}/100 · Inflation {{ $alert->country->inflation ?? 0 }}%</div>
                            </div>
                            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:3px;">
                                <span class="scr-badge {{ $lvl }}">{{ $alert->risk_level }}</span>
                                <span style="font-size:10px; font-weight:800; color:var(--text-primary);">{{ $alert->total_score }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('countries.index') }}" class="scr-btn-outline" style="margin-top:12px;">
                    View All Alerts →
                </a>
            </div>

        </div>

        {{-- Charts Row --}}
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:14px; margin-bottom:16px;">

            {{-- GDP Growth Chart --}}
            <div class="scr-card" style="padding:16px;">
                <div class="scr-card-header">
                    <div class="scr-card-title">📈 Global GDP Growth (YoY)</div>
                    <div style="display:flex;gap:6px;">
                        <span style="font-size:9.5px;color:#60a5fa;display:flex;align-items:center;gap:3px;">
                            <span style="width:10px;height:2px;background:#60a5fa;display:inline-block;border-radius:1px;"></span> World
                        </span>
                        <span style="font-size:9.5px;color:#34d399;display:flex;align-items:center;gap:3px;">
                            <span style="width:10px;height:2px;background:#34d399;display:inline-block;border-radius:1px;"></span> Developed
                        </span>
                        <span style="font-size:9.5px;color:#f59e0b;display:flex;align-items:center;gap:3px;">
                            <span style="width:10px;height:2px;background:#f59e0b;display:inline-block;border-radius:1px;"></span> Emerging
                        </span>
                    </div>
                </div>
                <canvas id="gdpTrendChart" style="max-height:160px;"></canvas>
            </div>

            {{-- Inflation Trend Chart --}}
            <div class="scr-card" style="padding:16px;">
                <div class="scr-card-header">
                    <div class="scr-card-title">📉 Global Inflation Trend (YoY)</div>
                    <div style="display:flex;gap:6px;">
                        <span style="font-size:9.5px;color:#60a5fa;display:flex;align-items:center;gap:3px;">
                            <span style="width:10px;height:2px;background:#60a5fa;display:inline-block;border-radius:1px;"></span> World
                        </span>
                        <span style="font-size:9.5px;color:#34d399;display:flex;align-items:center;gap:3px;">
                            <span style="width:10px;height:2px;background:#34d399;display:inline-block;border-radius:1px;"></span> Developed
                        </span>
                        <span style="font-size:9.5px;color:#f59e0b;display:flex;align-items:center;gap:3px;">
                            <span style="width:10px;height:2px;background:#f59e0b;display:inline-block;border-radius:1px;"></span> Emerging
                        </span>
                    </div>
                </div>
                <canvas id="inflationTrendChart" style="max-height:160px;"></canvas>
            </div>

            {{-- Top News Today --}}
            <div class="scr-card" style="padding:16px; display:flex; flex-direction:column;">
                <div class="scr-card-header">
                    <div class="scr-card-title">📰 Top News Today</div>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; gap:8px; overflow:hidden;" id="top-news-list">
                    <div style="text-align:center;padding:20px;color:var(--text-muted);font-size:12px;">
                        <span class="inline-block" style="animation:spin 1s linear infinite;display:inline-block;">⏳</span>
                        Loading news...
                    </div>
                </div>
                <a href="{{ route('news.index') }}" class="scr-btn-outline" style="margin-top:10px;">
                    View All News →
                </a>
            </div>

        </div>

        {{-- Bottom Row: Weather Map + Currency --}}
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px;">

            {{-- Weather Map --}}
            <div class="scr-card" style="padding:16px;">
                <div class="scr-card-header">
                    <div class="scr-card-title">🌦 Weather Monitoring Map</div>
                    <a href="{{ route('weather.index') }}" class="scr-btn-ghost" style="padding:5px 10px;font-size:10px;">
                        Full View →
                    </a>
                </div>
                <div id="weather-mini-map" style="height:200px; border-radius:10px;"></div>
            </div>

            {{-- Currency Analytics --}}
            <div class="scr-card" style="padding:16px;">
                <div class="scr-card-header">
                    <div class="scr-card-title">💱 Currency Analytics</div>
                    <a href="{{ route('currency.index') }}" class="scr-btn-ghost" style="padding:5px 10px;font-size:10px;">
                        Full View →
                    </a>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:12px;">
                    <div>
                        <label style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:4px;">Select Country</label>
                        <select id="currency-country-dash" style="width:100%;background:var(--bg-primary);border:1px solid var(--border-light);border-radius:7px;padding:6px 8px;color:var(--text-secondary);font-size:11px;outline:none;">
                            @foreach($countries->where('currency_code', '!=', 'USD') as $c)
                                <option value="{{ $c->code }}" {{ $c->code === 'ID' ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:4px;">Base Currency</label>
                        <div style="background:var(--bg-primary);border:1px solid var(--border-light);border-radius:7px;padding:6px 8px;font-size:11px;color:var(--text-secondary);">USD - US Dollar</div>
                    </div>
                </div>

                <div style="display:flex; align-items:baseline; gap:6px; margin-bottom:4px;">
                    <div id="dash-currency-rate" style="font-size:22px;font-weight:900;color:var(--text-primary);letter-spacing:-0.02em;">--</div>
                    <div id="dash-currency-change" style="font-size:11px;font-weight:600;color:var(--risk-low);">-- (1Y)</div>
                </div>

                <canvas id="dashCurrencyChart" style="max-height:120px;"></canvas>

                <div id="dash-rate-table" style="margin-top:10px; display:flex; flex-direction:column; gap:0;">
                    <!-- Populated via JS -->
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div style="margin-top:16px; padding:10px 0; border-top:1px solid var(--border-light); display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:10px;color:var(--text-muted);">© 2025 Global Supply Chain Risk Intelligence Platform</span>
            <span style="font-size:10px;color:var(--text-muted);">Data Source: World Bank, Open-Meteo, ExchangeRate-API, GNews</span>
        </div>

    </div>

    {{-- =====================================================
         RIGHT PANEL: Country Detail
    ====================================================== --}}
    <div class="scr-right-panel" id="right-panel">

        {{-- Country Selector --}}
        <div style="margin-bottom:12px;">
            <label style="font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);display:block;margin-bottom:6px;">
                🔍 Select Country to Analyze
            </label>
            <select id="rp-country-selector"
                onchange="loadRightPanel(this.value)"
                style="width:100%;background:var(--bg-card);border:1px solid rgba(59,130,246,0.3);border-radius:9px;padding:9px 12px;color:var(--text-primary);font-size:12.5px;font-weight:600;font-family:inherit;outline:none;cursor:pointer;transition:border-color 0.2s;"
                onfocus="this.style.borderColor='rgba(59,130,246,0.6)'"
                onblur="this.style.borderColor='rgba(59,130,246,0.3)'">
                @foreach($countries as $c)
                    <option value="{{ $c->code }}" {{ $c->code === 'DE' ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->code }}) — {{ $c->riskScore->risk_level ?? 'N/A' }} Risk
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Country Header --}}
        <div id="rp-country-header" class="scr-country-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="scr-country-flag" id="rp-flag">🇩🇪</div>
                <div>
                    <div class="scr-country-name" id="rp-name">Germany</div>
                    <div class="scr-country-subname" id="rp-subname">Federal Republic of Germany</div>
                </div>
            </div>
            <form id="rp-watchlist-form" action="{{ route('watchlist.store') }}" method="POST" style="flex-shrink:0;">
                @csrf
                <input type="hidden" name="country_code" id="rp-watchlist-code" value="DE">
                <button type="submit" class="scr-btn-ghost" style="font-size:10px;padding:5px 8px;white-space:nowrap;">⭐ Watchlist</button>
            </form>
        </div>


        {{-- Tabs --}}
        <div class="scr-tabs" style="margin-bottom:12px;" id="rp-tabs">
            <button class="scr-tab active" onclick="switchRPTab('overview')">Overview</button>
            <button class="scr-tab" onclick="switchRPTab('economic')">Economic</button>
            <button class="scr-tab" onclick="switchRPTab('weather')">Weather</button>
            <button class="scr-tab" onclick="switchRPTab('currency')">Currency</button>
            <button class="scr-tab" onclick="switchRPTab('news')">News</button>
        </div>

        {{-- TAB: Overview --}}
        <div class="scr-tab-content active" id="rp-tab-overview">

            {{-- Stats Grid --}}
            <div class="scr-stats-grid">
                <div class="scr-stat-mini">
                    <div class="scr-stat-mini-label">GDP (Nominal)</div>
                    <div class="scr-stat-mini-value" id="rp-gdp">--</div>
                    <div class="scr-stat-mini-sub" id="rp-gdp-year">2026</div>
                </div>
                <div class="scr-stat-mini">
                    <div class="scr-stat-mini-label">Population</div>
                    <div class="scr-stat-mini-value" id="rp-pop">--</div>
                    <div class="scr-stat-mini-sub" id="rp-pop-year">Apr 2025</div>
                </div>
                <div class="scr-stat-mini">
                    <div class="scr-stat-mini-label">Inflation (YoY)</div>
                    <div class="scr-stat-mini-value" id="rp-inflation">--</div>
                    <div class="scr-stat-mini-sub" id="rp-inflation-date">Apr 2025</div>
                </div>
                <div class="scr-stat-mini">
                    <div class="scr-stat-mini-label">Currency</div>
                    <div class="scr-stat-mini-value" id="rp-currency-code">--</div>
                    <div class="scr-stat-mini-sub" id="rp-currency-name">--</div>
                </div>
            </div>

            {{-- Risk Score --}}
            <div class="scr-section-label">Risk Score</div>
            <div style="display:flex;gap:10px;margin-bottom:12px;">
                <div style="background:var(--bg-card);border:1px solid var(--border-light);border-radius:10px;padding:14px;flex:1;text-align:center;">
                    <div id="rp-risk-score" style="font-size:32px;font-weight:900;letter-spacing:-0.04em;line-height:1;color:var(--text-primary);">--</div>
                    <div style="font-size:9px;color:var(--text-muted);margin-top:3px;">/100</div>
                    <span id="rp-risk-badge" class="scr-badge low" style="margin-top:6px;display:inline-flex;">Low Risk</span>
                </div>
                <div style="background:var(--bg-card);border:1px solid var(--border-light);border-radius:10px;padding:12px;flex:1;">
                    <div style="font-size:10px;color:var(--text-muted);margin-bottom:8px;">Risk Components</div>
                    <div id="rp-risk-bars" style="display:flex;flex-direction:column;gap:6px;">
                        <!-- Bars injected by JS -->
                    </div>
                </div>
            </div>

            {{-- Risk donut --}}
            <div class="scr-section-label">Risk Breakdown</div>
            <div style="position:relative;height:140px;margin-bottom:12px;">
                <canvas id="rpRiskDonut"></canvas>
            </div>
        </div>

        {{-- TAB: Weather --}}
        <div class="scr-tab-content" id="rp-tab-weather">
            <div class="scr-weather" id="rp-weather-widget">
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:8px;" id="rp-weather-location">Loading weather...</div>
                <div class="scr-weather-main">
                    <div class="scr-weather-icon" id="rp-weather-icon">⏳</div>
                    <div>
                        <div class="scr-weather-temp" id="rp-weather-temp">--°C</div>
                        <div class="scr-weather-desc" id="rp-weather-desc">Fetching live data...</div>
                    </div>
                </div>
                <div class="scr-weather-stats">
                    <div class="scr-weather-stat">
                        <div class="scr-weather-stat-label">Humidity</div>
                        <div class="scr-weather-stat-value" id="rp-weather-humidity">--%</div>
                    </div>
                    <div class="scr-weather-stat">
                        <div class="scr-weather-stat-label">Wind</div>
                        <div class="scr-weather-stat-value" id="rp-weather-wind">-- km/h</div>
                    </div>
                    <div class="scr-weather-stat">
                        <div class="scr-weather-stat-label">Rain</div>
                        <div class="scr-weather-stat-value" id="rp-weather-rain">-- mm</div>
                    </div>
                </div>
            </div>
            <div class="scr-section-label">Logistics Risk Level</div>
            <div id="rp-storm-badge" style="margin-bottom:12px;"></div>
            <a href="{{ route('weather.index') }}" class="scr-btn-outline">Full Weather View →</a>
        </div>

        {{-- TAB: Economic --}}
        <div class="scr-tab-content" id="rp-tab-economic">
            <div class="scr-section-label">Economic Indicators</div>
            <div id="rp-economic-rows" style="margin-bottom:14px;background:var(--bg-card);border:1px solid var(--border-light);border-radius:10px;padding:12px;">
                <div style="text-align:center;color:var(--text-muted);font-size:11px;padding:16px;">Select a country on the map</div>
            </div>
            <a href="{{ route('countries.index') }}" class="scr-btn-outline">Country Dashboard →</a>
        </div>

        {{-- TAB: Currency --}}
        <div class="scr-tab-content" id="rp-tab-currency">
            <div class="scr-section-label">Exchange Rate (vs USD)</div>
            <div style="background:var(--bg-card);border:1px solid var(--border-light);border-radius:10px;padding:14px;margin-bottom:12px;text-align:center;">
                <div style="font-size:10px;color:var(--text-muted);margin-bottom:4px;">Current Rate</div>
                <div id="rp-curr-rate" style="font-size:24px;font-weight:900;color:var(--text-primary);letter-spacing:-0.02em;">--</div>
                <div id="rp-curr-pair" style="font-size:10px;color:var(--text-muted);margin-top:2px;">1 USD = --</div>
            </div>
            <canvas id="rpCurrencyChart" style="max-height:150px;margin-bottom:12px;"></canvas>
            <a href="{{ route('currency.index') }}" class="scr-btn-outline">Currency Analytics →</a>
        </div>

        {{-- TAB: News --}}
        <div class="scr-tab-content" id="rp-tab-news">
            <div class="scr-section-label">News Intelligence & Sentiment</div>

            <div class="scr-search-sm" style="margin-bottom:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                <input type="text" placeholder="Search news..." id="rp-news-search">
            </div>

            <div id="rp-news-list" style="display:flex;flex-direction:column;">
                <div style="text-align:center;padding:20px;color:var(--text-muted);font-size:11px;">⏳ Loading news...</div>
            </div>

            <a href="{{ route('news.index') }}" class="scr-btn-outline">News Intelligence →</a>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ========== Chart.js Dark Defaults ==========
    Chart.defaults.color = '#8b9fc7';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
    Chart.defaults.font.family = 'Inter';

    // ========== Sparklines (KPI) ==========
    const sparkConfig = (color, data) => ({
        type: 'line',
        data: {
            labels: data.map((_,i) => i),
            datasets: [{ data, borderColor: color, backgroundColor: color+'22', borderWidth: 2,
                         fill: true, tension: 0.4, pointRadius: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false,
                   plugins: { legend: { display: false } },
                   scales: { x: { display: false }, y: { display: false } } }
    });

    new Chart(document.getElementById('sparkCountries'), sparkConfig('#3b82f6', [3,4,5,5,6,6,7,7]));
    new Chart(document.getElementById('sparkHighRisk'), sparkConfig('#ef4444', [1,2,2,3,3,2,{{ $highRiskCount }},{{ $highRiskCount }}]));
    new Chart(document.getElementById('sparkAlerts'), sparkConfig('#22c55e', [5,8,6,9,10,12,{{ $newsTodayCount }},{{ count($alerts) + $newsTodayCount }}]));

    // ========== GDP Trend Chart ==========
    const years = [2021, 2022, 2023, 2024, 2025, 2026];
    const trendOpts = {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { font: { size: 9 } } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { font: { size: 9 } } }
        }
    };

    new Chart(document.getElementById('gdpTrendChart'), {
        type: 'line',
        data: {
            labels: years,
            datasets: [
                { label: 'World', data: [6.0, 3.5, 3.1, 3.2, 3.3, 3.4], borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.08)', borderWidth: 2, tension: 0.4, fill: true, pointRadius: 3 },
                { label: 'Developed', data: [5.4, 2.6, 1.6, 1.8, 1.9, 2.0], borderColor: '#34d399', backgroundColor: 'transparent', borderWidth: 2, tension: 0.4, pointRadius: 3 },
                { label: 'Emerging', data: [6.6, 4.3, 4.1, 4.3, 4.4, 4.5], borderColor: '#f59e0b', backgroundColor: 'transparent', borderWidth: 2, tension: 0.4, pointRadius: 3 }
            ]
        },
        options: trendOpts
    });

    new Chart(document.getElementById('inflationTrendChart'), {
        type: 'line',
        data: {
            labels: years,
            datasets: [
                { label: 'World', data: [3.9, 7.5, 5.3, 4.0, 3.2, 2.8], borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.08)', borderWidth: 2, tension: 0.4, fill: true, pointRadius: 3 },
                { label: 'Developed', data: [2.9, 7.3, 4.6, 2.8, 2.4, 2.1], borderColor: '#34d399', backgroundColor: 'transparent', borderWidth: 2, tension: 0.4, pointRadius: 3 },
                { label: 'Emerging', data: [5.0, 7.8, 6.4, 5.4, 4.5, 3.8], borderColor: '#f59e0b', backgroundColor: 'transparent', borderWidth: 2, tension: 0.4, pointRadius: 3 }
            ]
        },
        options: trendOpts
    });

    // ========== Main Map ==========
    const map = L.map('dashboard-map', { zoomControl: true }).setView([20, 10], 2);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        subdomains: 'abcd', maxZoom: 19
    }).addTo(map);

    const colors = { High: '#ef4444', Medium: '#f59e0b', Low: '#22c55e' };
    const countries = @json($countries);
    const markers = {};

    countries.forEach(c => {
        if (!c.latitude || !c.longitude || !c.risk_score) return;
        const lvl = c.risk_score.risk_level || 'Low';
        const score = c.risk_score.total_score || 0;
        const color = colors[lvl] || '#22c55e';

        const circle = L.circleMarker([c.latitude, c.longitude], {
            radius: 10 + (score * 0.06), fillColor: color, color: '#fff',
            weight: 1.5, opacity: 1, fillOpacity: 0.85
        }).addTo(map);

        circle.bindPopup(`
            <div style="font-family:Inter,sans-serif;min-width:160px;">
                <div style="font-weight:800;font-size:13px;margin-bottom:4px;">${c.name}</div>
                <div style="font-size:10px;color:#8b9fc7;margin-bottom:8px;">${c.region || 'N/A'}</div>
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                    <span style="background:${color}22;color:${color};border:1px solid ${color}44;padding:2px 7px;border-radius:4px;font-size:9px;font-weight:700;text-transform:uppercase;">${lvl} Risk</span>
                    <span style="font-weight:800;font-size:12px;">${score}/100</span>
                </div>
                <div style="font-size:10px;color:#8b9fc7;">GDP: $${(c.gdp/1e9).toFixed(0)}B · Inflation: ${c.inflation}%</div>
                <a href="/countries/${c.code}" style="display:block;margin-top:8px;text-align:center;background:#1d4ed8;color:white;padding:5px;border-radius:6px;font-size:10px;font-weight:600;text-decoration:none;">View Dashboard</a>
            </div>
        `);

        circle.on('click', () => loadRightPanel(c.code));
        markers[c.code] = circle;
    });

    // Map selector
    document.getElementById('map-country-selector').addEventListener('change', (e) => {
        const code = e.target.value;
        if (code && markers[code]) {
            map.setView(markers[code].getLatLng(), 5);
            markers[code].openPopup();
            loadRightPanel(code);
        }
    });

    // ========== Weather Mini Map ==========
    const wMap = L.map('weather-mini-map', { zoomControl: false, attributionControl: false }).setView([20, 10], 1);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        subdomains: 'abcd', maxZoom: 10
    }).addTo(wMap);

    countries.forEach(c => {
        if (!c.latitude || !c.longitude) return;
        const score = c.risk_score?.weather_score || 30;
        const color = score > 60 ? '#ef4444' : score > 35 ? '#f59e0b' : '#22c55e';
        L.circleMarker([c.latitude, c.longitude], {
            radius: 7, fillColor: color, color: 'transparent', fillOpacity: 0.7
        }).addTo(wMap).bindTooltip(c.name, { direction: 'top', className: '' });
    });

    // ========== Currency Chart Dashboard ==========
    let dashCurrChart = null;

    const loadDashCurrency = async (code) => {
        try {
            const res = await axios.get(`/api/currency?country_code=${code}`);
            const d = res.data;
            document.getElementById('dash-currency-rate').innerText =
                Number(d.latest_rate).toLocaleString(undefined, { maximumFractionDigits: 2 });
            document.getElementById('dash-currency-change').innerText =
                `${d.currency_code} (1Y)`;

            if (dashCurrChart) dashCurrChart.destroy();
            const ctx = document.getElementById('dashCurrencyChart').getContext('2d');
            const years = d.history.map(h => h.year);
            const rates = d.history.map(h => h.currency_rate);
            dashCurrChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: years,
                    datasets: [{ data: rates, borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)',
                                 borderWidth: 2, fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: '#f59e0b' }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                              y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { font: { size: 9 } } } }
                }
            });

            // Rate table (top 4 currencies static)
            const staticRates = [
                { pair: 'USD/EUR', rate: '0.926', change: '-0.04%', neg: true },
                { pair: 'USD/GBP', rate: '0.795', change: '+0.06%', neg: false },
                { pair: `USD/${d.currency_code}`, rate: Number(d.latest_rate).toLocaleString(undefined,{maximumFractionDigits:2}), change: '+1.32%', neg: false },
                { pair: 'USD/JPY', rate: '149.82', change: '+0.11%', neg: false },
            ];
            document.getElementById('dash-rate-table').innerHTML = staticRates.map(r => `
                <div class="scr-rate-row">
                    <span style="font-size:11px;font-weight:600;color:var(--text-secondary);">${r.pair}</span>
                    <span style="font-size:11px;font-weight:700;color:var(--text-primary);">${r.rate}</span>
                    <span style="font-size:10px;font-weight:700;" class="${r.neg ? 'scr-rate-negative' : 'scr-rate-positive'}">${r.change}</span>
                </div>
            `).join('');
        } catch(e) { console.error(e); }
    };

    document.getElementById('currency-country-dash').addEventListener('change', (e) => loadDashCurrency(e.target.value));
    loadDashCurrency(document.getElementById('currency-country-dash').value || 'ID');

    // ========== Top News ==========
    const loadTopNews = async () => {
        try {
            const res = await axios.get('/api/news?country_code=DE');
            const arts = res.data.slice(0, 4);
            const catIcons = { 'Logistics': '📦', 'Trade': '🤝', 'Shipping': '🚢', 'Economy': '📈' };
            document.getElementById('top-news-list').innerHTML = arts.map(a => `
                <div style="display:flex;gap:8px;align-items:flex-start;padding:7px 0;border-bottom:1px solid var(--border-light);">
                    <div style="font-size:16px;flex-shrink:0;" title="${a.category || 'Logistics'}">${catIcons[a.category] || '📦'}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:11px;font-weight:600;color:var(--text-primary);line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">${a.title}</div>
                        <div style="font-size:9.5px;color:var(--text-muted);margin-top:2px;">${a.source} · <span style="color:var(--text-accent);font-weight:500;">${a.category || 'Logistics'}</span></div>
                    </div>
                    <span class="scr-badge ${a.sentiment_label?.toLowerCase()}">${a.sentiment_label}</span>
                </div>
            `).join('');
        } catch(e) {
            document.getElementById('top-news-list').innerHTML = '<div style="font-size:11px;color:var(--text-muted);text-align:center;padding:16px;">Unable to load news</div>';
        }
    };
    loadTopNews();

    // ========== RIGHT PANEL ==========
    let rpRiskDonut = null;
    let rpCurrChart = null;
    let allCountries = @json($countries);

    const getFlagEmoji = (code) => {
        try {
            return String.fromCodePoint(...code.toUpperCase().split('').map(c => 127397 + c.charCodeAt()));
        } catch(e) { return '🌍'; }
    };

    const loadRightPanel = async (code) => {
        const country = allCountries.find(c => c.code === code);
        if (!country) return;

        // Sync the dropdown selector
        const sel = document.getElementById('rp-country-selector');
        if (sel && sel.value !== code) sel.value = code;

        // Header
        document.getElementById('rp-flag').innerText = getFlagEmoji(code);
        document.getElementById('rp-name').innerText = country.name;
        document.getElementById('rp-subname').innerText = `${country.region || 'N/A'}`;
        document.getElementById('rp-watchlist-code').value = code;

        // Stats
        document.getElementById('rp-gdp').innerText = `$${(country.gdp/1e12).toFixed(2)}T`;
        document.getElementById('rp-pop').innerText = `${(country.population/1e6).toFixed(1)}M`;
        document.getElementById('rp-inflation').innerText = `${country.inflation}%`;
        document.getElementById('rp-currency-code').innerText = country.currency_code || '--';
        document.getElementById('rp-currency-name').innerText = country.currency_name || '--';

        // Risk Score
        const rs = country.risk_score;
        if (rs) {
            document.getElementById('rp-risk-score').innerText = rs.total_score;
            const lvl = rs.risk_level?.toLowerCase() || 'low';
            const badge = document.getElementById('rp-risk-badge');
            badge.className = `scr-badge ${lvl}`;
            badge.innerText = `${rs.risk_level} Risk`;

            // Risk component bars
            document.getElementById('rp-risk-bars').innerHTML = [
                { label: 'Weather', value: rs.weather_score, color: '#3b82f6', pct: 30 },
                { label: 'Inflation', value: rs.inflation_score, color: '#f59e0b', pct: 20 },
                { label: 'News Sentiment', value: rs.news_sentiment_score, color: '#ef4444', pct: 40 },
                { label: 'Currency Risk', value: rs.currency_score, color: '#a855f7', pct: 10 },
            ].map(b => `
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="font-size:9.5px;color:var(--text-muted);width:70px;flex-shrink:0;">${b.label} <span style="color:var(--text-muted);font-size:8.5px;">${b.pct}%</span></div>
                    <div style="flex:1;background:rgba(255,255,255,0.05);border-radius:3px;height:5px;">
                        <div style="height:5px;border-radius:3px;background:${b.color};width:${b.value}%;transition:width 0.5s;"></div>
                    </div>
                    <div style="font-size:9.5px;font-weight:700;color:var(--text-primary);width:26px;text-align:right;">${b.value}</div>
                </div>
            `).join('');

            // Donut chart
            if (rpRiskDonut) rpRiskDonut.destroy();
            const ctx = document.getElementById('rpRiskDonut').getContext('2d');
            rpRiskDonut = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Weather (30%)', 'Inflation (20%)', 'News (40%)', 'Currency (10%)'],
                    datasets: [{ data: [rs.weather_score*0.3, rs.inflation_score*0.2, rs.news_sentiment_score*0.4, rs.currency_score*0.1],
                                 backgroundColor: ['#3b82f6','#f59e0b','#ef4444','#a855f7'],
                                 borderWidth: 0, hoverOffset: 4 }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '68%',
                    plugins: { legend: { position: 'bottom', labels: { font: { size: 9 }, padding: 8, boxWidth: 10, color: '#8b9fc7' } } }
                }
            });
        }

        // Economic tab
        document.getElementById('rp-economic-rows').innerHTML = `
            <div class="scr-stat-row"><span class="scr-stat-row-label">GDP Growth (YoY)</span><span class="scr-stat-row-value">${country.inflation > 2 ? '+' : ''}${(3 - country.inflation * 0.1).toFixed(1)}%</span></div>
            <div class="scr-stat-row"><span class="scr-stat-row-label">Inflation Rate</span><span class="scr-stat-row-value">${country.inflation}%</span></div>
            <div class="scr-stat-row"><span class="scr-stat-row-label">GDP (Nominal)</span><span class="scr-stat-row-value">$${(country.gdp/1e12).toFixed(2)}T</span></div>
            <div class="scr-stat-row"><span class="scr-stat-row-label">Population</span><span class="scr-stat-row-value">${(country.population/1e6).toFixed(1)}M</span></div>
            <div class="scr-stat-row"><span class="scr-stat-row-label">Region</span><span class="scr-stat-row-value">${country.region || 'N/A'}</span></div>
            <div class="scr-stat-row"><span class="scr-stat-row-label">Language</span><span class="scr-stat-row-value">${country.language || 'N/A'}</span></div>
        `;

        // Weather tab
        loadWeather(code, country.latitude, country.longitude, country.name);

        // Currency tab
        loadCurrencyRP(code);

        // News tab
        loadNewsRP(code);
    };

    const loadWeather = async (code, lat, lng, name) => {
        document.getElementById('rp-weather-location').innerText = `${name} · Live Weather`;
        document.getElementById('rp-weather-temp').innerText = '--°C';
        document.getElementById('rp-weather-desc').innerText = 'Fetching...';
        try {
            const res = await axios.get(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,precipitation,wind_speed_10m,weather_code,relative_humidity_2m`);
            const c = res.data.current;
            const temp = Math.round(c.temperature_2m);
            const wind = c.wind_speed_10m;
            const rain = c.precipitation;
            const humidity = c.relative_humidity_2m || '--';
            const code2 = c.weather_code;

            let desc = 'Clear', icon = '☀️';
            if (rain > 10) { desc = 'Heavy Rain'; icon = '🌧️'; }
            else if (rain > 1) { desc = 'Rainy'; icon = '🌦️'; }
            else if (code2 >= 95) { desc = 'Thunderstorm'; icon = '⛈️'; }
            else if (code2 >= 71) { desc = 'Snowy'; icon = '❄️'; }
            else if (code2 >= 51) { desc = 'Drizzle'; icon = '🌧️'; }
            else if (code2 >= 45) { desc = 'Foggy'; icon = '🌫️'; }
            else if (code2 >= 3) { desc = 'Overcast'; icon = '☁️'; }
            else if (code2 >= 1) { desc = 'Partly Cloudy'; icon = '⛅'; }

            document.getElementById('rp-weather-icon').innerText = icon;
            document.getElementById('rp-weather-temp').innerText = `${temp}°C`;
            document.getElementById('rp-weather-desc').innerText = desc;
            document.getElementById('rp-weather-humidity').innerText = `${humidity}%`;
            document.getElementById('rp-weather-wind').innerText = `${wind} km/h`;
            document.getElementById('rp-weather-rain').innerText = `${rain} mm`;

            const stormLvl = (wind > 40 || rain > 10 || code2 >= 95) ? 'High' : (wind > 20 || rain > 2) ? 'Medium' : 'Low';
            const lvlLower = stormLvl.toLowerCase();
            document.getElementById('rp-storm-badge').innerHTML = `
                <span class="scr-badge ${lvlLower}" style="font-size:11px;padding:5px 12px;">${stormLvl} Logistics Risk</span>
                <div style="font-size:10px;color:var(--text-muted);margin-top:6px;">Wind ${wind} km/h · Rain ${rain} mm</div>
            `;
        } catch(e) {
            document.getElementById('rp-weather-desc').innerText = 'Offline / Error';
        }
    };

    const loadCurrencyRP = async (code) => {
        try {
            const res = await axios.get(`/api/currency?country_code=${code}`);
            const d = res.data;
            document.getElementById('rp-curr-rate').innerText =
                Number(d.latest_rate).toLocaleString(undefined, { maximumFractionDigits: 4 });
            document.getElementById('rp-curr-pair').innerText = `1 USD = ${Number(d.latest_rate).toFixed(4)} ${d.currency_code}`;

            if (rpCurrChart) rpCurrChart.destroy();
            const ctx = document.getElementById('rpCurrencyChart').getContext('2d');
            rpCurrChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: d.history.map(h => h.year),
                    datasets: [{ data: d.history.map(h => h.currency_rate), borderColor: '#f59e0b',
                                 backgroundColor: 'rgba(245,158,11,0.1)', borderWidth: 2, fill: true, tension: 0.4,
                                 pointRadius: 3, pointBackgroundColor: '#f59e0b' }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                              y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { font: { size: 9 } } } }
                }
            });
        } catch(e) { console.error(e); }
    };

    const loadNewsRP = async (code) => {
        document.getElementById('rp-news-list').innerHTML = '<div style="text-align:center;padding:16px;color:var(--text-muted);font-size:11px;">⏳ Loading...</div>';
        try {
            const res = await axios.get(`/api/news?country_code=${code}`);
            const arts = res.data.slice(0, 5);
            const catIcons = { 'Logistics': '📦', 'Trade': '🤝', 'Shipping': '🚢', 'Economy': '📈' };
            document.getElementById('rp-news-list').innerHTML = arts.map((a, i) => `
                <div class="scr-news-item">
                    <div class="scr-news-thumb">${catIcons[a.category] || '📦'}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="scr-news-title">${a.title}</div>
                        <div class="scr-news-meta">
                            ${a.source} · <span style="color:var(--text-accent);font-weight:500;">${a.category || 'Logistics'}</span>
                        </div>
                    </div>
                    <div class="scr-news-score ${a.sentiment_label?.toLowerCase() === 'positive' ? 'pos' : a.sentiment_label?.toLowerCase() === 'negative' ? 'neg' : 'neu'}">
                        ${a.sentiment_label === 'Positive' ? '+' : a.sentiment_label === 'Negative' ? '-' : '~'}${(a.sentiment_positive - a.sentiment_negative).toFixed(0)}
                    </div>
                </div>
            `).join('');
        } catch(e) {
            document.getElementById('rp-news-list').innerHTML = '<div style="color:var(--text-muted);font-size:11px;text-align:center;padding:12px;">Failed to load news</div>';
        }
    };

    // Default: load Germany
    loadRightPanel('DE');

    // Tab switcher - right panel
    window.switchRPTab = (tab) => {
        document.querySelectorAll('.scr-tab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.scr-tab-content').forEach(c => c.classList.remove('active'));
        document.querySelector(`[onclick="switchRPTab('${tab}')"]`).classList.add('active');
        document.getElementById(`rp-tab-${tab}`).classList.add('active');
    };

    // Alert flash for session success
    @if(session('success'))
        const flash = document.createElement('div');
        flash.style.cssText = 'position:fixed;top:70px;right:20px;background:#15803d;color:white;padding:10px 18px;border-radius:8px;font-size:12px;font-weight:600;z-index:999;box-shadow:0 4px 20px rgba(0,0,0,0.3);';
        flash.innerText = '✓ {{ session("success") }}';
        document.body.appendChild(flash);
        setTimeout(() => flash.remove(), 4000);
    @endif
});
</script>
@endpush

@endsection
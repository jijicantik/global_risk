@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Currency Impact Dashboard</h1>
            <p class="text-slate-500 mt-1">Monitor exchange rate fluctuations and currency trends</p>
        </div>
        <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <label for="currency-country-select" class="text-sm font-semibold text-slate-500">Base Currency Comparison:</label>
            <select id="currency-country-select" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($countries as $c)
                    <option value="{{ $c->code }}" {{ $selectedCountry->code === $c->code ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->currency_code }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Currency Impact Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Live Rate Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-6">Rate Matrix (vs USD)</h2>
                
                <div class="text-center py-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="text-sm text-slate-400 font-semibold tracking-wider uppercase">Current Rate</div>
                    <div id="currency-rate" class="text-4xl font-black text-slate-800 mt-2">--</div>
                    <div class="text-xs text-slate-400 mt-1" id="currency-pair">1 USD = --</div>
                </div>

                <div class="mt-8 space-y-4 text-sm">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-slate-500">Currency Name</span>
                        <span id="currency-name" class="font-bold text-slate-800">--</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-slate-500">Currency Code</span>
                        <span id="currency-code" class="font-bold text-slate-800">--</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-amber-50 border border-amber-100 p-4 rounded-xl">
                <p class="text-xs text-amber-700 leading-relaxed font-medium">
                    ⚠️ Import/Export Warning: Exchange rate volatility directly impacts product unit costs and shipping customs values.
                </p>
            </div>
        </div>

        <!-- Exchange Rate Trend Line Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Historical Valuation Trend (2020-2024)</h2>
            <div class="flex-grow flex items-center justify-center min-h-[300px]">
                <canvas id="currencyTrendChart" style="max-height:350px"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let trendChart = null;

    const loadCurrencyDetails = async (code) => {
        try {
            const res = await axios.get(`/api/currency?country_code=${code}`);
            if (res.data) {
                const data = res.data;
                document.getElementById('currency-rate').innerText = Number(data.latest_rate).toLocaleString(undefined, { maximumFractionDigits: 4 });
                document.getElementById('currency-pair').innerText = `1 USD = ${Number(data.latest_rate).toLocaleString(undefined, { maximumFractionDigits: 4 })} ${data.currency_code}`;
                document.getElementById('currency-name').innerText = data.currency_name;
                document.getElementById('currency-code').innerText = data.currency_code;

                // Update Chart.js
                const years = data.history.map(h => h.year);
                const rates = data.history.map(h => h.currency_rate);

                if (trendChart) {
                    trendChart.destroy();
                }

                const ctx = document.getElementById('currencyTrendChart').getContext('2d');
                trendChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: years,
                        datasets: [{
                            label: `1 USD to ${data.currency_code}`,
                            data: rates,
                            borderColor: '#eab308',
                            backgroundColor: 'rgba(234, 179, 8, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#eab308'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        },
                        scales: {
                            y: { grid: { color: '#f1f5f9' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        } catch (e) {
            console.error(e);
        }
    };

    // Initialize
    const select = document.getElementById('currency-country-select');
    loadCurrencyDetails(select.value);

    // Dropdown change
    select.addEventListener('change', (e) => {
        loadCurrencyDetails(e.target.value);
    });
});
</script>
@endsection

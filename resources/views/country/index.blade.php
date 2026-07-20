@extends('layouts.app')

@section('content')
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
                $color = 'text-green-600 bg-green-50 border-green-200';
                if (($c->riskScore->risk_level ?? 'Low') === 'High') {
                    $color = 'text-red-600 bg-red-50 border-red-200';
                } elseif (($c->riskScore->risk_level ?? 'Low') === 'Medium') {
                    $color = 'text-yellow-600 bg-yellow-50 border-yellow-200';
                }
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
                        <span class="px-2.5 py-1 rounded-lg text-sm font-bold border {{ $color }}">
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
                        <div class="flex justify-between pb-2">
                            <span class="text-slate-400">Risk Score:</span>
                            <span class="font-bold text-slate-800">{{ $c->riskScore->total_score ?? 'N/A' }}/100</span>
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
@endsection

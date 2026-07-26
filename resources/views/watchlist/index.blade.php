@extends('layouts.app')

@section('content')
<div class="scr-content">
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 font-sans">Favorite Watchlist</h1>
            <p class="text-slate-500 mt-1">Monitored supply chain regions and critical alerts</p>
        </div>
        <div class="text-sm font-semibold text-slate-400 bg-slate-50 border px-4 py-2 rounded-xl">
            â­ Favorited: {{ count($countries) }}
        </div>
    </div>

    <!-- Success message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Watchlist Grid -->
    @if(count($countries) > 0)
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
                        <!-- Header details -->
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $c->region }}</span>
                                <h2 class="text-2xl font-bold text-slate-800 mt-1 flex items-center gap-2">
                                    {{ $c->name }}
                                </h2>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-sm font-bold border {{ $color }}">
                                {{ $c->riskScore->risk_level ?? 'Low' }} Risk
                            </span>
                        </div>

                        <!-- Statistics info -->
                        <div class="mt-6 space-y-3 text-sm">
                            <div class="flex justify-between border-b border-slate-50 pb-2">
                                <span class="text-slate-400">Current Risk Index:</span>
                                <span class="font-extrabold text-slate-800">{{ $c->riskScore->total_score ?? 'N/A' }}/100</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-50 pb-2">
                                <span class="text-slate-400">Inflation Rate:</span>
                                <span class="font-semibold text-slate-700">{{ $c->inflation }}%</span>
                            </div>
                            <div class="flex justify-between pb-2">
                                <span class="text-slate-400 font-medium">Currency Status:</span>
                                <span class="font-semibold text-slate-700">{{ $c->currency_code }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-4 border-t border-slate-50 flex gap-2">
                        <a href="{{ route('countries.show', $c->code) }}" class="flex-grow text-center text-sm font-semibold bg-slate-800 hover:bg-slate-700 text-white py-3 rounded-xl transition duration-150">
                            Details &rarr;
                        </a>
                        <form action="{{ route('watchlist.destroy', $c->code) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 p-3 rounded-xl transition duration-150" title="Remove from Favorites">
                                ðŸ—‘ï¸
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-100 p-16 text-center shadow-sm">
            <div class="text-5xl mb-4">â­</div>
            <h2 class="text-2xl font-bold text-slate-800">Your Watchlist is Empty</h2>
            <p class="text-slate-500 mt-2 max-w-md mx-auto">Track country risk scores and core supply chain indicators by clicking the 'Add to Watchlist' button on any country page.</p>
            <a href="{{ route('countries.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl mt-6 shadow transition">
                Browse Countries &rarr;
            </a>
        </div>
    @endif
</div>
</div>
@endsection


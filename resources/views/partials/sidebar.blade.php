<aside class="fixed left-0 top-0 w-64 h-screen bg-slate-900 text-white">

    <div class="text-2xl font-bold p-6 border-b border-slate-700">

        🌍 SCR Platform

    </div>

    <nav class="mt-6">

        <a href="/dashboard"
           class="flex items-center gap-3 px-6 py-3 hover:bg-slate-800">

            📊 Dashboard

        </a>

        <a href="/countries"
           class="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 {{ Request::is('countries*') ? 'bg-slate-800 border-l-4 border-blue-500' : '' }}">
            🌎 Countries
        </a>

        <a href="/weather"
           class="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 {{ Request::is('weather*') ? 'bg-slate-800 border-l-4 border-blue-500' : '' }}">
            ☁ Weather
        </a>

        <a href="/currency"
           class="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 {{ Request::is('currency*') ? 'bg-slate-800 border-l-4 border-blue-500' : '' }}">
            💵 Currency
        </a>

        <a href="/news"
           class="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 {{ Request::is('news*') ? 'bg-slate-800 border-l-4 border-blue-500' : '' }}">
            📰 News
        </a>

        <a href="/ports"
           class="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 {{ Request::is('ports*') ? 'bg-slate-800 border-l-4 border-blue-500' : '' }}">
            🚢 Ports
        </a>

        <a href="/watchlist"
           class="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 {{ Request::is('watchlist*') ? 'bg-slate-800 border-l-4 border-blue-500' : '' }}">
            ⭐ Watchlist
        </a>

        @if(auth()->user()->is_admin)
        <a href="/admin"
           class="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 {{ Request::is('admin*') ? 'bg-slate-800 border-l-4 border-blue-500' : '' }}">
            🛡 Admin Panel
        </a>
        @endif
    </nav>
</aside>
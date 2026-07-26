<aside class="scr-sidebar">

    <!-- Logo -->
    <a href="{{ auth()->user()->is_admin ? route('admin.index') : route('dashboard') }}" class="scr-logo" style="text-decoration:none;">
        <div class="scr-logo-icon">🌍</div>
        <div class="scr-logo-text">
            <div class="scr-logo-title">GSC Risk</div>
            <div class="scr-logo-sub">{{ auth()->user()->is_admin ? 'Admin Control Center' : 'Global Supply Chain Intelligence' }}</div>
        </div>
    </a>

    <!-- Navigation -->
    <nav class="scr-nav">

        @if(auth()->user()->is_admin)
        <!-- Dedicated Admin Menu -->
        <div class="scr-nav-section" style="color:#f87171; font-weight:800; letter-spacing:0.08em;">ADMINISTRATOR CONTROL</div>

        <a href="{{ route('admin.index') }}"
           class="scr-nav-link {{ Request::is('admin*') ? 'active' : '' }}"
           style="background:rgba(239,68,68,0.15); border-left:3px solid #ef4444; font-weight:700; color:#fca5a5; margin-bottom:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="color:#ef4444;">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
            </svg>
            🛡️ Admin Dashboard
        </a>

        <div class="scr-nav-section" style="margin-top:10px;">Modul Kelola</div>

        <a href="{{ route('admin.index') }}#users-tab" onclick="if(window.location.pathname==='/admin'){ switchTab(event, 'users-tab'); return false; }"
           class="scr-nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            👥 Kelola User
        </a>

        <a href="{{ route('admin.index') }}#ports-tab" onclick="if(window.location.pathname==='/admin'){ switchTab(event, 'ports-tab'); return false; }"
           class="scr-nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 21c-1.39 0-2.78-.47-4-1.32-2.44 1.71-5.56 1.71-8 0C6.78 20.53 5.39 21 4 21H2v2h2c1.38 0 2.74-.35 4-.99 2.52 1.29 5.48 1.29 8 0 1.26.65 2.62.99 4 .99h2v-2h-2zM3.95 19H4c1.6 0 3.02-.88 4-2 .98 1.12 2.4 2 4 2s3.02-.88 4-2c.98 1.12 2.4 2 4 2h.05l1.89-6.68c.08-.26.06-.54-.06-.78s-.34-.42-.6-.5L20 10.62V6c0-1.1-.9-2-2-2h-3V1H9v3H6c-1.1 0-2 .9-2 2v4.62l-1.29.42c-.26.08-.48.26-.6.5s-.15.52-.06.78L3.95 19zM6 6h12v3.97L12 8 6 9.97V6z"/>
            </svg>
            🚢 Dataset Pelabuhan
        </a>

        <a href="{{ route('admin.index') }}#articles-tab" onclick="if(window.location.pathname==='/admin'){ switchTab(event, 'articles-tab'); return false; }"
           class="scr-nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            📰 Artikel Analisis
        </a>

        <div class="scr-nav-divider"></div>
        <div class="scr-nav-section">Akses Platform</div>

        <a href="{{ route('dashboard') }}" class="scr-nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
            </svg>
            🔍 Lihat Tampilan User
        </a>

        <a href="{{ route('profile.edit') }}" class="scr-nav-link {{ Request::is('profile*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Profile Akun
        </a>

        @else
        <!-- Standard Analyst User Menu -->
        <div class="scr-nav-section">Main Menu</div>

        <a href="{{ route('dashboard') }}"
           class="scr-nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('countries.index') }}"
           class="scr-nav-link {{ Request::is('countries*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
            </svg>
            Country Dashboard
        </a>

        <a href="{{ route('weather.index') }}"
           class="scr-nav-link {{ Request::is('weather*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M6.76 4.84l-1.8-1.79-1.41 1.41 1.79 1.79 1.42-1.41zM4 10.5H1v2h3v-2zm9-9.95h-2V3.5h2V.55zm7.45 3.91l-1.41-1.41-1.79 1.79 1.41 1.41 1.79-1.79zm-3.21 13.7l1.79 1.8 1.41-1.41-1.8-1.79-1.4 1.4zM20 10.5v2h3v-2h-3zm-8-5c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm-1 16.95h2V19.5h-2v2.95zm-7.45-3.91l1.41 1.41 1.79-1.8-1.41-1.41-1.79 1.8z"/>
            </svg>
            Weather Monitoring
        </a>

        <a href="{{ route('currency.index') }}"
           class="scr-nav-link {{ Request::is('currency*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
            </svg>
            Currency Analytics
        </a>

        <a href="{{ route('ports.index') }}"
           class="scr-nav-link {{ Request::is('ports*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 21c-1.39 0-2.78-.47-4-1.32-2.44 1.71-5.56 1.71-8 0C6.78 20.53 5.39 21 4 21H2v2h2c1.38 0 2.74-.35 4-.99 2.52 1.29 5.48 1.29 8 0 1.26.65 2.62.99 4 .99h2v-2h-2zM3.95 19H4c1.6 0 3.02-.88 4-2 .98 1.12 2.4 2 4 2s3.02-.88 4-2c.98 1.12 2.4 2 4 2h.05l1.89-6.68c.08-.26.06-.54-.06-.78s-.34-.42-.6-.5L20 10.62V6c0-1.1-.9-2-2-2h-3V1H9v3H6c-1.1 0-2 .9-2 2v4.62l-1.29.42c-.26.08-.48.26-.6.5s-.15.52-.06.78L3.95 19zM6 6h12v3.97L12 8 6 9.97V6z"/>
            </svg>
            Port Monitoring
        </a>

        <a href="{{ route('news.index') }}"
           class="scr-nav-link {{ Request::is('news*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.89 2-2V5c0-1.11-.89-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4v-2z"/>
            </svg>
            News Intelligence
        </a>

        <div class="scr-nav-divider"></div>
        <div class="scr-nav-section">Tools</div>

        <a href="{{ route('countries.compare') }}"
           class="scr-nav-link {{ Request::is('countries/compare*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9.01 14H2v2h7.01v3L13 15l-3.99-4v3zm5.98-1v-3H22V8h-7.01V5L11 9l3.99 4z"/>
            </svg>
            Country Comparison
        </a>

        <a href="{{ route('watchlist.index') }}"
           class="scr-nav-link {{ Request::is('watchlist*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
            Watchlist
        </a>

        <a href="{{ route('profile.edit') }}"
           class="scr-nav-link {{ Request::is('profile*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Profile
        </a>
        @endif

    </nav>

    <!-- Footer: Logout + Status -->
    <div class="scr-sidebar-footer">

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="scr-nav-link" style="width:100%; background:none; border:none; cursor:pointer; font-family:inherit;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px;opacity:0.75;">
                    <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                </svg>
                Logout
            </button>
        </form>

        <div class="scr-status-card">
            <div class="scr-status-row">
                <div class="scr-status-dot"></div>
                Platform Status
            </div>
            <div class="scr-status-detail">
                On-line · All systems operational<br>
                Last updated: {{ now()->format('d M Y, H:i') }} WIB
            </div>
        </div>

    </div>

</aside>
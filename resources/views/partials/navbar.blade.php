<header class="scr-navbar">

    <!-- Hamburger -->
    <button class="scr-navbar-hamburger" onclick="toggleSidebar()" title="Toggle Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
    </button>

    <!-- Search -->
    <div class="scr-search">
        <svg class="scr-search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
        </svg>
        <input type="text" id="global-search" placeholder="Search country, port, news..." autocomplete="off">
    </div>

    <!-- Right Actions -->
    <div class="scr-navbar-actions">

        <!-- Notifications -->
        <div class="scr-icon-btn" title="Alerts">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
            </svg>
            <span class="notif-badge">{{ App\Models\RiskScore::where('risk_level','High')->count() }}</span>
        </div>

        <!-- Settings -->
        <a href="{{ route('profile.edit') }}" class="scr-icon-btn" title="Settings">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
            </svg>
        </a>

        @if(Auth::user()->is_admin)
        <a href="{{ route('admin.index') }}" 
           style="background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; font-size:11.5px; font-weight:800; padding:6px 12px; border-radius:8px; text-decoration:none; display:flex; align-items:center; gap:5px; transition:all 0.15s;"
           onmouseover="this.style.background='rgba(239,68,68,0.25)';"
           onmouseout="this.style.background='rgba(239,68,68,0.15)';"
           title="Buka Console Admin">
            🛡️ Admin Dashboard
        </a>
        @endif

        <div class="scr-nav-divider-v" style="width:1px;height:20px;background:var(--border-light);"></div>

        <!-- User -->
        <a href="{{ route('profile.edit') }}" class="scr-user" style="text-decoration:none;">
            <div class="scr-user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="scr-user-name">{{ Auth::user()->name }}</div>
                <div class="scr-user-role">{{ Auth::user()->is_admin ? 'Administrator' : 'Analyst' }}</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="color:var(--text-muted);margin-left:4px;">
                <path d="M7 10l5 5 5-5z"/>
            </svg>
        </a>

    </div>

</header>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.scr-sidebar');
    if (sidebar) {
        sidebar.style.transform = sidebar.style.transform === 'translateX(-100%)' 
            ? 'translateX(0)' : 'translateX(-100%)';
    }
}

// Global search — redirect to countries page
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('global-search');
    if (searchInput) {
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.target.value.trim()) {
                window.location.href = `/countries?search=${encodeURIComponent(e.target.value.trim())}`;
            }
        });
    }
});
</script>
@extends('layouts.app')

@section('content')
<div class="scr-content">
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Admin Console Header Banner -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-red-600 bg-red-50 border border-red-100 px-3 py-1 rounded-lg uppercase tracking-wider">
                    🛡️ Administrator Control Center
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 mt-2">Admin Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">Pusat Pengelolaan User, Dataset Pelabuhan Dunia, dan Artikel Analisis Logistik</p>
        </div>

        <div class="flex items-center gap-3 bg-slate-50 border border-slate-100 p-3 rounded-xl">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-red-600 to-rose-600 flex items-center justify-center text-white font-bold text-base shadow">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="text-xs">
                <div class="font-bold text-slate-800">{{ auth()->user()->name }}</div>
                <div class="text-red-600 font-semibold">{{ auth()->user()->email }}</div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm font-medium text-sm flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm font-medium text-sm flex items-center gap-2">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif

    <!-- 3 Admin Management Executive KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- 1. Kelola User KPI Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between cursor-pointer hover:border-blue-300 transition" onclick="switchTabByButton('users-tab')">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul 1</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">👥 Kelola User</h3>
                <div class="mt-3 text-xs space-y-1">
                    <div class="font-bold text-slate-700">Total Pengguna: <span class="text-blue-600 font-extrabold">{{ count($users) }}</span></div>
                    <div class="text-slate-500 font-semibold">
                        🛡️ <span class="text-red-600 font-bold">{{ $users->where('is_admin', true)->count() }}</span> Administrator | 👤 <span class="text-blue-600 font-bold">{{ $users->where('is_admin', false)->count() }}</span> Risk Analyst
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-2xl text-blue-600">
                👥
            </div>
        </div>

        <!-- 2. Dataset Pelabuhan KPI Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between cursor-pointer hover:border-blue-300 transition" onclick="switchTabByButton('ports-tab')">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul 2</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">🚢 Dataset Pelabuhan</h3>
                <div class="mt-3 text-xs space-y-1">
                    <div class="font-bold text-slate-700">Total Pelabuhan: <span class="text-blue-600 font-extrabold">{{ count($ports) }}</span></div>
                    <div class="text-slate-500 font-semibold">
                        ⚓ Terdaftar di <span class="text-blue-600 font-bold">{{ $ports->pluck('country_code')->unique()->count() }}</span> Negara Dunia
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-2xl text-blue-600">
                🚢
            </div>
        </div>

        <!-- 3. Artikel Analisis KPI Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between cursor-pointer hover:border-blue-300 transition" onclick="switchTabByButton('articles-tab')">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul 3</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">📰 Artikel Analisis</h3>
                <div class="mt-3 text-xs space-y-1">
                    <div class="font-bold text-slate-700">Total Artikel: <span class="text-blue-600 font-extrabold">{{ count($articles) }}</span></div>
                    <div class="text-slate-500 font-semibold">
                        ✅ <span class="text-green-600 font-bold">{{ $articles->where('status', 'Published')->count() }}</span> Terbit | 📝 <span class="text-amber-600 font-bold">{{ $articles->where('status', 'Draft')->count() }}</span> Draft
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-2xl text-blue-600">
                📰
            </div>
        </div>

    </div>

    <!-- Main Navigation Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex gap-4 border-b border-slate-100 pb-4 mb-6 overflow-x-auto">
            <button class="tab-btn active px-5 py-2.5 font-bold text-blue-600 border-b-2 border-blue-600 text-sm focus:outline-none flex items-center gap-2" onclick="switchTab(event, 'users-tab')">
                👥 1. Kelola User ({{ count($users) }})
            </button>
            <button class="tab-btn px-5 py-2.5 font-bold text-slate-500 hover:text-blue-600 text-sm focus:outline-none flex items-center gap-2" onclick="switchTab(event, 'ports-tab')">
                🚢 2. Dataset Pelabuhan Dunia ({{ count($ports) }})
            </button>
            <button class="tab-btn px-5 py-2.5 font-bold text-slate-500 hover:text-blue-600 text-sm focus:outline-none flex items-center gap-2" onclick="switchTab(event, 'articles-tab')">
                📰 3. Artikel Analisis Logistik ({{ count($articles) }})
            </button>
        </div>

        <!-- =========================================================
             MODUL 1: KELOLA USER
        ========================================================= -->
        <div id="users-tab" class="tab-content">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">👥 Kelola User Platform</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar akun pengguna, kontrol peran (Administrator vs Risk Analyst), dan fitur CRUD user</p>
                </div>
                <div class="flex gap-2 text-xs font-bold">
                    <span class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-100">
                        🛡️ Administrator: {{ $users->where('is_admin', true)->count() }}
                    </span>
                    <span class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-100">
                        👤 Risk Analyst: {{ $users->where('is_admin', false)->count() }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">User ID</th>
                            <th class="px-4 py-3">Nama Lengkap</th>
                            <th class="px-4 py-3">Email Address</th>
                            <th class="px-4 py-3">Peran / Role</th>
                            <th class="px-4 py-3">Tanggal Dibuat</th>
                            <th class="px-4 py-3 text-right">Aksi CRUD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3.5 font-mono text-xs font-bold text-slate-400">#{{ $user->id }}</td>
                                <td class="px-4 py-3.5 font-bold text-slate-800">{{ $user->name }}</td>
                                <td class="px-4 py-3.5 font-medium text-slate-600">{{ $user->email }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase {{ $user->is_admin ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                        {{ $user->is_admin ? '🛡️ Administrator' : '👤 Risk Analyst' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-slate-400">
                                    {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3.5 text-right flex justify-end gap-2">
                                    <button onclick="openEditUserModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', {{ $user->is_admin ? 'true' : 'false' }})" class="text-blue-600 hover:text-blue-800 font-semibold text-xs border border-blue-100 rounded-lg px-3 py-1.5 bg-blue-50/50 transition">
                                        ✏️ Edit User
                                    </button>
                                    @if(auth()->id() != $user->id)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ addslashes($user->name) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs border border-red-100 rounded-lg px-3 py-1.5 bg-red-50/50 transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-300 italic px-2 py-1">(Akun Anda)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- =========================================================
             MODUL 2: DATASET PELABUHAN DUNIA
        ========================================================= -->
        <div id="ports-tab" class="tab-content hidden">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">🚢 Dataset Pelabuhan Dunia</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola lokasi pelabuhan, kode UN/LOCODE, nama negara, dan koordinat geospasial</p>
                </div>
                <button onclick="openAddPortModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow flex items-center gap-1.5">
                    + Tambah Lokasi Pelabuhan Baru
                </button>
            </div>

            <div class="overflow-x-auto max-h-[550px]">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs sticky top-0 bg-white">
                        <tr>
                            <th class="px-4 py-3">UN/LOCODE</th>
                            <th class="px-4 py-3">Nama Pelabuhan</th>
                            <th class="px-4 py-3">Negara</th>
                            <th class="px-4 py-3">Koordinat (Lat, Lng)</th>
                            <th class="px-4 py-3 text-right">Aksi CRUD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($ports as $port)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3.5 font-bold text-blue-600">
                                    <span class="bg-blue-50 border border-blue-100 px-2.5 py-0.5 rounded text-xs font-mono">{{ $port->code }}</span>
                                </td>
                                <td class="px-4 py-3.5 font-bold text-slate-800">⚓ {{ $port->name }}</td>
                                <td class="px-4 py-3.5 font-medium">{{ $port->country_name }} ({{ $port->country_code }})</td>
                                <td class="px-4 py-3.5 font-mono text-xs text-slate-500">{{ number_format($port->latitude, 4) }}, {{ number_format($port->longitude, 4) }}</td>
                                <td class="px-4 py-3.5 text-right flex justify-end gap-2">
                                    <button onclick="openEditPortModal('{{ $port->id }}', '{{ addslashes($port->name) }}', '{{ addslashes($port->code) }}', '{{ $port->latitude }}', '{{ $port->longitude }}', '{{ addslashes($port->country_code) }}', '{{ addslashes($port->country_name) }}')" class="text-blue-600 hover:text-blue-800 font-semibold text-xs border border-blue-100 rounded-lg px-3 py-1.5 bg-blue-50/50 transition">
                                        ✏️ Edit Pelabuhan
                                    </button>
                                    <form action="{{ route('admin.ports.destroy', $port->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelabuhan {{ addslashes($port->name) }} dari dataset?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs border border-red-100 rounded-lg px-3 py-1.5 bg-red-50/50 transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- =========================================================
             MODUL 3: ARTIKEL ANALISIS LOGISTIK
        ========================================================= -->
        <div id="articles-tab" class="tab-content hidden">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">📰 Artikel Analisis Logistik & Risiko</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola artikel intelijen maritim, penulis, serta status publikasi (Draft vs Published)</p>
                </div>
                <button onclick="openAddArticleModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow flex items-center gap-1.5">
                    + Tulis Artikel Analisis Baru
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Judul Artikel Analisis</th>
                            <th class="px-4 py-3">Penulis</th>
                            <th class="px-4 py-3">Status Publikasi</th>
                            <th class="px-4 py-3">Tanggal Dibuat</th>
                            <th class="px-4 py-3 text-right">Aksi CRUD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($articles as $art)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-800">📝 {{ $art->title }}</td>
                                <td class="px-4 py-3.5 font-medium">{{ $art->author->name ?? 'Administrator' }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase {{ $art->status === 'Published' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                        {{ $art->status === 'Published' ? '✅ Published' : '📝 Draft' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-slate-400">{{ $art->created_at ? $art->created_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="px-4 py-3.5 text-right flex justify-end gap-2">
                                    <button onclick="openEditArticleModal('{{ $art->id }}', '{{ addslashes($art->title) }}', '{{ addslashes(str_replace(array("\r", "\n"), ' ', $art->content)) }}', '{{ $art->status }}')" class="text-blue-600 hover:text-blue-800 font-semibold text-xs border border-blue-100 rounded-lg px-3 py-1.5 bg-blue-50/50 transition">
                                        ✏️ Edit Artikel
                                    </button>
                                    <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs border border-red-100 rounded-lg px-3 py-1.5 bg-red-50/50 transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</div>

<!-- Modal Dialog Container for Admin CRUD Operations -->
<div id="admin-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-2xl font-bold focus:outline-none">&times;</button>
        <h2 id="modal-title" class="text-2xl font-bold text-slate-800 mb-6">Aksi Modul Admin</h2>
        <div id="modal-body-content"></div>
    </div>
</div>

<script>
function switchTab(evt, tabId) {
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.add('hidden'));

    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active', 'text-blue-600', 'border-b-2', 'border-blue-600');
        btn.classList.add('text-slate-500');
    });

    document.getElementById(tabId).classList.remove('hidden');
    if (evt && evt.currentTarget) {
        evt.currentTarget.classList.add('active', 'text-blue-600', 'border-b-2', 'border-blue-600');
        evt.currentTarget.classList.remove('text-slate-500');
    }
}

function switchTabByButton(tabId) {
    let index = 0;
    if (tabId === 'ports-tab') index = 1;
    if (tabId === 'articles-tab') index = 2;
    const btn = document.querySelectorAll('.tab-btn')[index];
    if (btn) btn.click();
}

const modal = document.getElementById('admin-modal');
const modalTitle = document.getElementById('modal-title');
const modalBody = document.getElementById('modal-body-content');

function openModal(title, contentHtml) {
    modalTitle.innerText = title;
    modalBody.innerHTML = contentHtml;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// User Modal
function openEditUserModal(id, name, email, isAdmin) {
    const html = `
        <form action="/admin/users/${id}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="${name}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="${email}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="is_admin_check" name="is_admin" ${isAdmin ? 'checked' : ''} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                <label for="is_admin_check" class="text-sm font-semibold text-slate-700">Berikan Hak Akses Administrator</label>
            </div>
            <div class="pt-3">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow">Simpan Perubahan User</button>
            </div>
        </form>
    `;
    openModal('Edit User Platform', html);
}

// Port Modals
function openAddPortModal() {
    const html = `
        <form action="{{ route('admin.ports.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Pelabuhan</label>
                <input type="text" name="name" placeholder="Contoh: Port of Rotterdam" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode UN/LOCODE</label>
                <input type="text" name="code" placeholder="Contoh: NLRTM" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Latitude</label>
                    <input type="number" step="any" name="latitude" placeholder="51.924" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Longitude</label>
                    <input type="number" step="any" name="longitude" placeholder="4.477" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Negara (ISO2)</label>
                    <input type="text" name="country_code" placeholder="NL" size="2" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Negara</label>
                    <input type="text" name="country_name" placeholder="Netherlands" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="pt-3">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow">Simpan Pelabuhan Baru</button>
            </div>
        </form>
    `;
    openModal('Tambah Dataset Pelabuhan', html);
}

function openEditPortModal(id, name, code, latitude, longitude, countryCode, countryName) {
    const html = `
        <form action="/admin/ports/${id}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Pelabuhan</label>
                <input type="text" name="name" value="${name}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode UN/LOCODE</label>
                <input type="text" name="code" value="${code}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Latitude</label>
                    <input type="number" step="any" name="latitude" value="${latitude}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Longitude</label>
                    <input type="number" step="any" name="longitude" value="${longitude}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Negara (ISO2)</label>
                    <input type="text" name="country_code" value="${countryCode}" size="2" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Negara</label>
                    <input type="text" name="country_name" value="${countryName}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="pt-3">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow">Perbarui Pelabuhan</button>
            </div>
        </form>
    `;
    openModal('Edit Dataset Pelabuhan', html);
}

// Article Modals
function openAddArticleModal() {
    const html = `
        <form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Artikel</label>
                <input type="text" name="title" placeholder="Contoh: Analisis Risiko Kemacetan Jalur Terusan Suez" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Konten Analisis Risiko</label>
                <textarea name="content" rows="6" placeholder="Tuliskan analisis intelijen rantai pasok..." required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status Publikasi</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Draft">Draft</option>
                    <option value="Published">Published (Publikasikan)</option>
                </select>
            </div>
            <div class="pt-3">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow">Simpan Artikel</button>
            </div>
        </form>
    `;
    openModal('Tulis Artikel Analisis Baru', html);
}

function openEditArticleModal(id, title, content, status) {
    const html = `
        <form action="/admin/articles/${id}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Artikel</label>
                <input type="text" name="title" value="${title}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Konten Analisis Risiko</label>
                <textarea name="content" rows="6" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">${content}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status Publikasi</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Draft" ${status === 'Draft' ? 'selected' : ''}>Draft</option>
                    <option value="Published" ${status === 'Published' ? 'selected' : ''}>Published (Publikasikan)</option>
                </select>
            </div>
            <div class="pt-3">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow">Perbarui Artikel</button>
            </div>
        </form>
    `;
    openModal('Edit Artikel Analisis', html);
}

// Auto open tab based on URL hash (e.g., #ports-tab)
document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.replace('#', '');
    if (hash && (hash === 'users-tab' || hash === 'ports-tab' || hash === 'articles-tab')) {
        switchTabByButton(hash);
    }
});
</script>
@endsection

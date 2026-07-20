@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Administrator Console</h1>
            <p class="text-slate-500 mt-1">Manage platform users, ports data, and intelligence articles</p>
        </div>
        <div class="text-sm font-semibold text-red-600 bg-red-50 border border-red-100 px-4 py-2 rounded-xl">
            🛡 Secure Area
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Admin Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex gap-4 border-b border-slate-100 pb-4 mb-6">
            <button class="tab-btn active px-4 py-2 font-bold text-blue-600 border-b-2 border-blue-600 text-sm focus:outline-none" onclick="switchTab(event, 'users-tab')">👤 Users Panel</button>
            <button class="tab-btn px-4 py-2 font-bold text-slate-500 hover:text-blue-600 text-sm focus:outline-none" onclick="switchTab(event, 'ports-tab')">⚓ Ports Panel</button>
            <button class="tab-btn px-4 py-2 font-bold text-slate-500 hover:text-blue-600 text-sm focus:outline-none" onclick="switchTab(event, 'articles-tab')">📝 Articles Panel</button>
        </div>

        <!-- 1. Users Panel -->
        <div id="users-tab" class="tab-content">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Manage Platform Users</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">User ID</th>
                            <th class="px-4 py-3">Full Name</th>
                            <th class="px-4 py-3">Email Address</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $user->id }}</td>
                                <td class="px-4 py-3 font-bold text-slate-800">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $user->is_admin ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 flex gap-2">
                                    <button onclick="openEditUserModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', {{ $user->is_admin ? 'true' : 'false' }})" class="text-blue-600 hover:text-blue-800 font-semibold text-xs border border-blue-100 rounded px-2.5 py-1.5 bg-blue-50/50">
                                        Edit
                                    </button>
                                    @if(auth()->id() != $user->id)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs border border-red-100 rounded px-2.5 py-1.5 bg-red-50/50">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Ports Panel -->
        <div id="ports-tab" class="tab-content hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">World Ports Dataset Management</h2>
                <button onclick="openAddPortModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                    + Add Port Location
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Code</th>
                            <th class="px-4 py-3">Port Name</th>
                            <th class="px-4 py-3">Country</th>
                            <th class="px-4 py-3">Coordinates (Lat, Lng)</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($ports as $port)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $port->code }}</td>
                                <td class="px-4 py-3 font-bold text-slate-800">{{ $port->name }}</td>
                                <td class="px-4 py-3">{{ $port->country_name }} ({{ $port->country_code }})</td>
                                <td class="px-4 py-3">{{ $port->latitude }}, {{ $port->longitude }}</td>
                                <td class="px-4 py-3 flex gap-2">
                                    <button onclick="openEditPortModal('{{ $port->id }}', '{{ $port->name }}', '{{ $port->code }}', '{{ $port->latitude }}', '{{ $port->longitude }}', '{{ $port->country_code }}', '{{ $port->country_name }}')" class="text-blue-600 hover:text-blue-800 font-semibold text-xs border border-blue-100 rounded px-2.5 py-1.5 bg-blue-50/50">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.ports.destroy', $port->id) }}" method="POST" onsubmit="return confirm('Delete this port from database?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs border border-red-100 rounded px-2.5 py-1.5 bg-red-50/50">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Articles Panel -->
        <div id="articles-tab" class="tab-content hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">Intelligence Articles List</h2>
                <button onclick="openAddArticleModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                    + Write Article
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Author</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($articles as $art)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-800">{{ $art->title }}</td>
                                <td class="px-4 py-3">{{ $art->author->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $art->status === 'Published' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-yellow-50 text-yellow-700 border border-yellow-100' }}">
                                        {{ $art->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $art->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 flex gap-2">
                                    <button onclick="openEditArticleModal('{{ $art->id }}', '{{ $art->title }}', '{{ addslashes($art->content) }}', '{{ $art->status }}')" class="text-blue-600 hover:text-blue-800 font-semibold text-xs border border-blue-100 rounded px-2.5 py-1.5 bg-blue-50/50">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Delete this article?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs border border-red-100 rounded px-2.5 py-1.5 bg-red-50/50">
                                            Delete
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

<!-- Modal Dialogs for Admin Panel Operations -->
<div id="admin-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-xl border border-slate-100 relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-2xl font-bold focus:outline-none">&times;</button>
        <h2 id="modal-title" class="text-2xl font-bold text-slate-800 mb-6">Modal Action</h2>
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
    evt.currentTarget.classList.add('active', 'text-blue-600', 'border-b-2', 'border-blue-600');
    evt.currentTarget.classList.remove('text-slate-500');
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

// User modal helper
function openEditUserModal(id, name, email, isAdmin) {
    const html = `
        <form action="/admin/users/${id}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Full Name</label>
                <input type="text" name="name" value="${name}" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Email</label>
                <input type="email" name="email" value="${email}" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_admin_check" name="is_admin" ${isAdmin ? 'checked' : ''} class="rounded border-slate-200">
                <label for="is_admin_check" class="text-sm font-semibold text-slate-600">Give Administrative Privileges</label>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">Save Changes</button>
        </form>
    `;
    openModal('Edit Platform User', html);
}

// Port modals helpers
function openAddPortModal() {
    const html = `
        <form action="{{ route('admin.ports.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Port Name</label>
                <input type="text" name="name" placeholder="e.g. Port of Rotterdam" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">UN/LOCODE</label>
                <input type="text" name="code" placeholder="e.g. NLRTM" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Latitude</label>
                    <input type="number" step="any" name="latitude" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Longitude</label>
                    <input type="number" step="any" name="longitude" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Country Code</label>
                    <input type="text" name="country_code" placeholder="e.g. NL" size="2" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Country Name</label>
                    <input type="text" name="country_name" placeholder="e.g. Netherlands" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">Save Port</button>
        </form>
    `;
    openModal('Add Port Dataset', html);
}

function openEditPortModal(id, name, code, latitude, longitude, countryCode, countryName) {
    const html = `
        <form action="/admin/ports/${id}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Port Name</label>
                <input type="text" name="name" value="${name}" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">UN/LOCODE</label>
                <input type="text" name="code" value="${code}" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Latitude</label>
                    <input type="number" step="any" name="latitude" value="${latitude}" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Longitude</label>
                    <input type="number" step="any" name="longitude" value="${longitude}" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Country Code</label>
                    <input type="text" name="country_code" value="${countryCode}" size="2" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Country Name</label>
                    <input type="text" name="country_name" value="${countryName}" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">Update Port</button>
        </form>
    `;
    openModal('Edit Port Dataset', html);
}

// Articles modals helpers
function openAddArticleModal() {
    const html = `
        <form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Article Title</label>
                <input type="text" name="title" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Content Analysis</label>
                <textarea name="content" rows="6" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Publish Status</label>
                <select name="status" class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Draft">Draft</option>
                    <option value="Published">Published</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">Save Article</button>
        </form>
    `;
    openModal('Write New Article', html);
}

function openEditArticleModal(id, title, content, status) {
    const html = `
        <form action="/admin/articles/${id}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Article Title</label>
                <input type="text" name="title" value="${title}" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Content Analysis</label>
                <textarea name="content" rows="6" required class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">${content}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Publish Status</label>
                <select name="status" class="w-full border rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Draft" ${status === 'Draft' ? 'selected' : ''}>Draft</option>
                    <option value="Published" ${status === 'Published' ? 'selected' : ''}>Published</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition">Update Article</button>
        </form>
    `;
    openModal('Edit Intelligence Article', html);
}
</script>
@endsection

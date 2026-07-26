@extends('layouts.app')

@section('content')
<div class="scr-content">
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Header Banner -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">User Profile</h1>
            <p class="text-slate-500 mt-1">Manage your account information, security credentials, and access settings</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider {{ $user->is_admin ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                {{ $user->is_admin ? '🛡️ Administrator' : '👤 Risk Analyst' }}
            </span>
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold text-green-700 bg-green-50 border border-green-100">
                ● Active Status
            </span>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if (session('status') === 'profile-updated')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2 font-medium">
            <span>✓</span> Profile information has been updated successfully!
        </div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2 font-medium">
            <span>✓</span> Password changed successfully!
        </div>
    @endif

    <!-- Profile Identity Overview Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <!-- User Avatar Circle -->
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-3xl shadow-lg shadow-blue-500/20 flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <!-- User Info Details -->
            <div class="flex-1 text-center md:text-left space-y-2">
                <div class="flex flex-col md:flex-row md:items-center gap-2">
                    <h2 class="text-2xl font-bold text-slate-800">{{ $user->name }}</h2>
                </div>
                <p class="text-slate-500 font-medium text-sm">✉️ {{ $user->email }}</p>
                <div class="pt-2 flex flex-wrap justify-center md:justify-start gap-4 text-xs font-semibold text-slate-500">
                    <div class="bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg">
                        ⭐ Watchlist Items: <span class="text-slate-800 font-bold">{{ $watchlistCount ?? 0 }}</span>
                    </div>
                    @if($user->is_admin)
                        <div class="bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg">
                            📝 Articles Published: <span class="text-slate-800 font-bold">{{ $articleCount ?? 0 }}</span>
                        </div>
                    @endif
                    <div class="bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg">
                        📅 Joined: <span class="text-slate-800 font-bold">{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex gap-4 border-b border-slate-100 pb-4 mb-6 overflow-x-auto">
            <button class="profile-tab-btn active px-4 py-2 font-bold text-blue-600 border-b-2 border-blue-600 text-sm focus:outline-none flex items-center gap-2" onclick="switchProfileTab(event, 'info-tab')">
                👤 Profile Information
            </button>
            <button class="profile-tab-btn px-4 py-2 font-bold text-slate-500 hover:text-blue-600 text-sm focus:outline-none flex items-center gap-2" onclick="switchProfileTab(event, 'password-tab')">
                🔒 Security & Password
            </button>
            <button class="profile-tab-btn px-4 py-2 font-bold text-slate-500 hover:text-red-600 text-sm focus:outline-none flex items-center gap-2 ml-auto" onclick="switchProfileTab(event, 'danger-tab')">
                ⚠️ Danger Zone
            </button>
        </div>

        <!-- Tab 1: Profile Information -->
        <div id="info-tab" class="profile-tab-content">
            <div class="max-w-2xl">
                <h3 class="text-xl font-bold text-slate-800 mb-1">Update Account Information</h3>
                <p class="text-slate-500 text-sm mb-6">Update your name, primary email address, and personal details.</p>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition duration-150">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab 2: Security & Password -->
        <div id="password-tab" class="profile-tab-content hidden">
            <div class="max-w-2xl">
                <h3 class="text-xl font-bold text-slate-800 mb-1">Update Password</h3>
                <p class="text-slate-500 text-sm mb-6">Ensure your account uses a strong, random password to maintain security.</p>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-1">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required autocomplete="current-password" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @if ($errors->updatePassword->has('current_password'))
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">New Password</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @if ($errors->updatePassword->has('password'))
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $errors->updatePassword->first('password') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @if ($errors->updatePassword->has('password_confirmation'))
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                        @endif
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition duration-150">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab 3: Danger Zone -->
        <div id="danger-tab" class="profile-tab-content hidden">
            <div class="max-w-2xl bg-red-50/50 border border-red-100 rounded-2xl p-6">
                <h3 class="text-xl font-bold text-red-600 mb-1">Delete Account</h3>
                <p class="text-slate-600 text-sm mb-6">
                    Once your account is deleted, all resources, watchlist preferences, and associated data will be permanently removed. Before deleting your account, please save any data you wish to retain.
                </p>

                <button type="button" onclick="openDeleteModal()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition duration-150">
                    Delete Account
                </button>
            </div>
        </div>
    </div>

</div>
</div>

<!-- Modal Dialog for Deleting User Account -->
<div id="delete-account-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm {{ $errors->userDeletion->isNotEmpty() ? 'flex' : 'hidden' }} items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-xl border border-slate-100 relative">
        <button type="button" onclick="closeDeleteModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-2xl font-bold focus:outline-none">&times;</button>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Are you sure you want to delete your account?</h2>
        <p class="text-slate-500 text-sm mb-6">
            Please enter your password to confirm permanent account deletion. This action cannot be undone.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
            @csrf
            @method('DELETE')

            <div>
                <label for="delete_password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" id="delete_password" name="password" placeholder="Enter your password" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                @if ($errors->userDeletion->has('password'))
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-3">
                <button type="button" onclick="closeDeleteModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-5 py-2.5 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow transition">
                    Confirm Deletion
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function switchProfileTab(evt, tabId) {
    const contents = document.querySelectorAll('.profile-tab-content');
    contents.forEach(content => content.classList.add('hidden'));

    const buttons = document.querySelectorAll('.profile-tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active', 'text-blue-600', 'text-red-600', 'border-b-2', 'border-blue-600', 'border-red-600');
        btn.classList.add('text-slate-500');
    });

    document.getElementById(tabId).classList.remove('hidden');
    
    if (tabId === 'danger-tab') {
        evt.currentTarget.classList.add('active', 'text-red-600', 'border-b-2', 'border-red-600');
    } else {
        evt.currentTarget.classList.add('active', 'text-blue-600', 'border-b-2', 'border-blue-600');
    }
    evt.currentTarget.classList.remove('text-slate-500');
}

function openDeleteModal() {
    const modal = document.getElementById('delete-account-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-account-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Auto open password tab or danger modal if validation errors exist in those bags
@if($errors->updatePassword->isNotEmpty())
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.querySelectorAll('.profile-tab-btn')[1];
        if (btn) btn.click();
    });
@endif
@if($errors->userDeletion->isNotEmpty())
    document.addEventListener('DOMContentLoaded', () => {
        openDeleteModal();
    });
@endif
</script>
@endsection

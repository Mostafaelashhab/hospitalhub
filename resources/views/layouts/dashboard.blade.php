<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('app.app_name') }}</title>
    @include('partials.meta')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|cairo:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(app()->getLocale() === 'ar')
    <style>body { font-family: 'Cairo', sans-serif !important; }</style>
    @endif
    <style>
        .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.08); }
        .glass-header { background: rgba(255,255,255,0.9); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .nav-link-active {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white !important;
            box-shadow: 0 4px 12px -2px rgba(99,102,241,0.4);
        }
        .nav-link-active svg { color: white !important; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
        @media (min-width: 1024px) {
            [dir="ltr"] #main-content { margin-left: 280px; }
            [dir="rtl"] #main-content { margin-right: 280px; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/30 lg:hidden" @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : (document.dir === 'rtl' ? 'translate-x-full' : '-translate-x-full')"
           class="fixed inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} z-50 w-[280px] bg-white border-{{ app()->getLocale() === 'ar' ? 'l' : 'r' }} border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col">

        {{-- Logo --}}
        <div class="flex items-center justify-between h-[72px] px-6 border-b border-gray-100">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">{{ __('app.app_name') }}</span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto scrollbar-thin">
            {{ $sidebar }}
        </nav>

        {{-- User info at bottom --}}
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-sm font-bold text-white shadow-md">
                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('lang.switch', 'ar') }}" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 {{ app()->getLocale() === 'ar' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    العربية
                </a>
                <a href="{{ route('lang.switch', 'en') }}" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 {{ app()->getLocale() === 'en' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    English
                </a>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    {{ __('app.logout') }}
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="min-h-screen" id="main-content">
        {{-- Top Header Bar --}}
        <header class="sticky top-0 z-30 h-14 glass-header border-b border-gray-200 flex items-center {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }} justify-between px-4 lg:px-8">
            {{-- Mobile menu button --}}
            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            {{-- Spacer for desktop --}}
            <div class="hidden lg:block"></div>

            {{-- Branch + Lang (left in RTL, right in LTR) --}}
            <div class="flex items-center gap-3">
                {{-- Branch Switcher --}}
                @php
                    $clinic = auth()->user()->clinic;
                    $branches = $clinic ? $clinic->branches()->where('is_active', true)->get() : collect();
                    $activeBranchId = session('active_branch_id');
                    $currentBranch = $activeBranchId ? $branches->firstWhere('id', $activeBranchId) : null;
                    $currentBranch = $currentBranch ?? $branches->firstWhere('is_main', true) ?? $branches->first();
                @endphp
                @if($branches->count() > 0)
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="max-w-[180px] truncate">{{ $currentBranch->name ?? __('app.main_branch') }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 bg-white border border-gray-200 rounded-xl shadow-lg py-2 z-50" style="min-width: 280px;">
                        <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 mb-1">{{ __('app.branches') }}</p>
                        @foreach($branches as $branch)
                        <form method="POST" action="{{ route('dashboard.branches.switch', $branch) }}">
                            @csrf
                            <button type="submit" class="w-full text-start px-4 py-3 text-sm {{ $currentBranch && $currentBranch->id === $branch->id ? 'text-indigo-700 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                                <span class="flex items-center justify-between gap-3">
                                    <span class="flex items-center gap-3 min-w-0">
                                        <span class="w-8 h-8 rounded-lg {{ $currentBranch && $currentBranch->id === $branch->id ? 'bg-indigo-100' : 'bg-gray-100' }} inline-flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 {{ $currentBranch && $currentBranch->id === $branch->id ? 'text-indigo-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </span>
                                        <span class="truncate font-medium">{{ $branch->name }}</span>
                                        @if($branch->is_main)
                                        <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-1.5 py-0.5 rounded shrink-0">{{ __('app.main_branch') }}</span>
                                        @endif
                                    </span>
                                    @if($currentBranch && $currentBranch->id === $branch->id)
                                    <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </span>
                            </button>
                        </form>
                        @endforeach
                        <div class="border-t border-gray-100 mt-2 pt-2 px-2">
                            <a href="{{ route('dashboard.branches.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                {{ __('app.manage_branches') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Notification Bell --}}
                <div x-data="notificationBell()" x-init="init()" class="relative">
                    <button @click="toggle()" class="relative p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full px-1"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900">{{ __('app.notifications') }}</h3>
                            <button x-show="unreadCount > 0" @click="markAllRead()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">{{ __('app.mark_all_read') }}</button>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-8 text-center text-sm text-gray-400">{{ __('app.no_notifications') }}</div>
                            </template>
                            <template x-for="notification in notifications" :key="notification.id">
                                <a :href="notification.url" @click="markRead(notification.id)"
                                   :class="notification.read ? 'bg-white' : 'bg-indigo-50/50'"
                                   class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="shrink-0 mt-0.5">
                                            <div :class="notification.read ? 'bg-gray-100' : 'bg-indigo-100'" class="w-8 h-8 rounded-lg flex items-center justify-center">
                                                <svg :class="notification.read ? 'text-gray-400' : 'text-indigo-600'" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="notification.title"></p>
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="notification.body"></p>
                                            <p class="text-[10px] text-gray-400 mt-1" x-text="notification.created_at"></p>
                                        </div>
                                        <div x-show="!notification.read" class="w-2 h-2 bg-indigo-500 rounded-full shrink-0 mt-2"></div>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Push Notification Permission Button --}}
                <div x-data="{ canAsk: Notification.permission === 'default' }" x-show="canAsk" x-cloak>
                    <button @click="window.requestPushPermission().then(r => { if(r) canAsk = false })"
                            class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="{{ __('app.enable_notifications') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 005.714 0m-5.714 0a3 3 0 115.714 0M3.124 15.772A8.966 8.966 0 016 9.75V9a6 6 0 0112 0v.75a8.966 8.966 0 012.876 6.022M3.124 15.772a23.85 23.85 0 005.454 1.31m8.844 0a23.85 23.85 0 005.454-1.31M12 3v1.5"/></svg>
                    </button>
                </div>

                {{-- Language Switcher --}}
                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                    <a href="{{ route('lang.switch', 'ar') }}"
                       class="px-3 py-1.5 text-xs font-semibold transition-all {{ app()->getLocale() === 'ar' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                        عربي
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="px-3 py-1.5 text-xs font-semibold transition-all {{ app()->getLocale() === 'en' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                        EN
                    </a>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
             class="mx-6 lg:mx-8 mt-4 px-5 py-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
             class="mx-6 lg:mx-8 mt-4 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        @endif

        {{-- Page content --}}
        <main class="p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    <script>
    function notificationBell() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,
            init() {
                this.fetch();
                // Poll every 30 seconds
                setInterval(() => this.fetch(), 30000);
            },
            async fetch() {
                try {
                    const res = await window.axios.get('/notifications');
                    this.notifications = res.data.notifications;
                    this.unreadCount = res.data.unread_count;
                } catch (e) {}
            },
            toggle() {
                this.open = !this.open;
                if (this.open) this.fetch();
            },
            async markRead(id) {
                try {
                    await window.axios.post(`/notifications/${id}/read`);
                    const n = this.notifications.find(n => n.id === id);
                    if (n && !n.read) {
                        n.read = true;
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                } catch (e) {}
            },
            async markAllRead() {
                try {
                    await window.axios.post('/notifications/read-all');
                    this.notifications.forEach(n => n.read = true);
                    this.unreadCount = 0;
                } catch (e) {}
            },
        };
    }
    </script>
</body>
</html>

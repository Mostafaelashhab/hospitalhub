<x-dashboard-layout>
    <x-slot name="title">{{ app()->getLocale() === 'ar' ? 'رسائل واتساب' : 'WhatsApp Messages' }}</x-slot>
    <x-slot name="pageTitle">{{ app()->getLocale() === 'ar' ? 'رسائل واتساب' : 'WhatsApp Messages' }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.super-admin-sidebar')
    </x-slot>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2.546 21.2a1 1 0 001.254 1.254l4.032-.892A9.96 9.96 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ app()->getLocale() === 'ar' ? 'الرسائل الواردة' : 'Incoming Messages' }}</h2>
                @if($unreadCount > 0)
                <p class="text-xs text-green-600 font-semibold">{{ $unreadCount }} {{ app()->getLocale() === 'ar' ? 'رسالة غير مقروءة' : 'unread' }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{-- Search --}}
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'بحث...' : 'Search...' }}"
                       class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition w-48">
                @if(request('unread'))
                <input type="hidden" name="unread" value="1">
                @endif
                <button type="submit" class="px-3 py-2 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>

            {{-- Filter --}}
            <a href="{{ route('super.whatsapp.index', request('unread') ? [] : ['unread' => 1]) }}"
               class="px-3 py-2 text-xs font-semibold rounded-xl transition {{ request('unread') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ app()->getLocale() === 'ar' ? 'غير مقروءة' : 'Unread' }}
            </a>

            {{-- Mark all read --}}
            @if($unreadCount > 0)
            <form method="POST" action="{{ route('super.whatsapp.read-all') }}">
                @csrf
                <button type="submit" class="px-3 py-2 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition">
                    {{ app()->getLocale() === 'ar' ? 'تعليم الكل مقروء' : 'Mark all read' }}
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Messages List --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($messages->count())
        <div class="divide-y divide-gray-100">
            @foreach($messages as $msg)
            <div class="flex items-start gap-4 px-6 py-4 {{ !$msg->is_read ? 'bg-green-50/50' : '' }} hover:bg-gray-50 transition">
                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ !$msg->is_read ? 'bg-green-100' : 'bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ !$msg->is_read ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-sm font-bold text-gray-900 truncate">
                            {{ $msg->sender_name ?: $msg->clean_from }}
                        </p>
                        @if(!$msg->is_read)
                        <span class="inline-flex w-2 h-2 bg-green-500 rounded-full shrink-0"></span>
                        @endif
                        @if($msg->is_group)
                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ app()->getLocale() === 'ar' ? 'جروب' : 'Group' }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mb-1" dir="ltr">{{ $msg->clean_from }}</p>
                    <p class="text-sm text-gray-700 line-clamp-2">{{ $msg->body }}</p>
                </div>

                {{-- Time & Actions --}}
                <div class="text-end shrink-0 flex flex-col items-end gap-2">
                    <p class="text-xs text-gray-400">{{ $msg->message_at?->diffForHumans() ?? $msg->created_at->diffForHumans() }}</p>
                    @if(!$msg->is_read)
                    <form method="POST" action="{{ route('super.whatsapp.read', $msg) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs text-indigo-500 hover:text-indigo-700 font-medium transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $messages->withQueryString()->links() }}
        </div>
        @else
        <div class="py-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <p class="text-gray-400 text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'مفيش رسائل لسه' : 'No messages yet' }}</p>
            <p class="text-gray-300 text-xs mt-1">{{ app()->getLocale() === 'ar' ? 'الرسائل الواردة من واتساب هتظهر هنا' : 'Incoming WhatsApp messages will appear here' }}</p>
        </div>
        @endif
    </div>
</x-dashboard-layout>

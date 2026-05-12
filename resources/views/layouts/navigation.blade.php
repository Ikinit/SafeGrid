<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('family.index')" :active="request()->routeIs('family.*')">
                        {{ __('Household Profile') }}
                    </x-nav-link>
                    <x-nav-link :href="route('gobag.index')" :active="request()->routeIs('gobag.*')">
                        {{ __('Go Bag') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contacts.index')" :active="request()->routeIs('contacts.*')">
                        {{ __('Contacts') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-1">

                {{-- ── ALERTS BELL BUTTON ── --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" onclick="markAlertsAsRead()"
                            class="relative inline-flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition focus:outline-none"
                            title="Alerts">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(isset($unreadAlertCount) && $unreadAlertCount > 0)
                            <span id="alertBadge" class="absolute top-0.5 right-0.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full leading-none">
                                {{ $unreadAlertCount > 9 ? '9+' : $unreadAlertCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" x-transition @click.stop
                         class="absolute right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden"
                         style="display:none; width:420px;">
                        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                            <span style="font-size:1rem; font-weight:700; color:#1e293b; letter-spacing:-0.01em;">Alerts</span>
                            @if(Auth::user()->is_admin ?? false)
                                <a href="{{ route('alerts.index') }}" class="text-xs text-blue-500 hover:underline">Manage</a>
                            @endif
                        </div>
                        <div class="overflow-y-auto divide-y divide-gray-50" style="max-height:480px;">
                            @forelse(isset($recentAlerts) ? $recentAlerts : [] as $alert)
                                <div style="padding: 0.85rem 1.5rem;" class="hover:bg-gray-50 {{ !($alert->is_read ?? true) ? 'bg-blue-50' : '' }}">
                                    <div class="flex items-start gap-3">
                                        @if(($alert->type ?? '') === 'expiry')
                                            <svg class="w-4 h-4 mt-0.5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 mt-0.5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p style="font-size:0.875rem; font-weight:600; color:#1e293b;" class="truncate">{{ $alert->title }}</p>
                                            <p style="font-size:0.8rem; color:#64748b; margin-top:0.2rem; line-height:1.4;" class="line-clamp-2">{{ $alert->message }}</p>
                                            <p style="font-size:0.72rem; color:#94a3b8; margin-top:0.35rem;">{{ $alert->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 2.5rem 1.5rem; text-align:center; font-size:0.875rem; color:#94a3b8;">No alerts right now.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ── INVITATIONS BUTTON ── --}}
                <div class="relative me-2" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open"
                            class="relative inline-flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition focus:outline-none"
                            title="Household Invitations">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        @if(isset($pendingInvitationCount) && $pendingInvitationCount > 0)
                            <span class="absolute top-0.5 right-0.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-blue-500 rounded-full leading-none">
                                {{ $pendingInvitationCount > 9 ? '9+' : $pendingInvitationCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" x-transition @click.stop
                         class="absolute right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden"
                         style="display:none; width:420px;">
                        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;">
                            <span style="font-size:1rem; font-weight:700; color:#1e293b; letter-spacing:-0.01em;">Household Invitations</span>
                        </div>
                        <div class="overflow-y-auto divide-y divide-gray-50" style="max-height:480px;">
                            @forelse(isset($pendingInvitations) ? $pendingInvitations : [] as $invitation)
                                <div style="padding: 0.85rem 1.5rem;" class="hover:bg-gray-50">
                                    <p style="font-size:0.875rem; font-weight:600; color:#1e293b;">
                                        {{ $invitation->familyProfile->household_name }}
                                    </p>
                                    <p style="font-size:0.78rem; color:#64748b; margin-top:0.2rem;">
                                        @if($invitation->invitedBy)
                                            Invited by <strong>{{ $invitation->invitedBy->username }}</strong>
                                        @else
                                            <strong>Join Request Sent</strong> (Waiting for approval)
                                        @endif
                                        · {{ $invitation->created_at->diffForHumans() }}
                                    </p>
                                    
                                    <div class="flex gap-2" style="margin-top:0.6rem;">
                                        @if($invitation->invitedBy)
                                            <button type="button" onclick="handleNavInvitation({{ $invitation->id }}, 'accept')" class="hover:bg-blue-600 transition" style="padding:0.3rem 1rem; font-size:0.78rem; font-weight:600; background:#3b82f6; color:#fff; border-radius:999px; border:none; cursor:pointer;">
                                                Accept
                                            </button>
                                            <button type="button" onclick="handleNavInvitation({{ $invitation->id }}, 'decline')" class="hover:bg-gray-200 transition" style="padding:0.3rem 1rem; font-size:0.78rem; font-weight:600; background:#f1f5f9; color:#475569; border-radius:999px; border:none; cursor:pointer;">
                                                Decline
                                            </button>
                                        @else
                                            <button type="button" onclick="handleNavInvitation({{ $invitation->id }}, 'cancel')" class="hover:bg-red-600 transition" style="padding:0.3rem 1rem; font-size:0.78rem; font-weight:600; background:#ef4444; color:#fff; border-radius:999px; border:none; cursor:pointer;">
                                                Cancel Request
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 2.5rem 1.5rem; text-align:center; font-size:0.875rem; color:#94a3b8;">No pending invitations.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->username }}</div>  {{-- fixed: was ->name --}}
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('family.index')" :active="request()->routeIs('family.*')">
                {{ __('Family Profile') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->username }}</div>  {{-- fixed: was ->name --}}
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
<script>
    function markAlertsAsRead() {
        const badge = document.getElementById('alertBadge');
        
        // If the badge exists (meaning there are unread alerts)
        if (badge && badge.style.display !== 'none') {

            badge.style.display = 'none';

            fetch('/api/alerts', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(err => console.error('Failed to sync alert status:', err));
        }
    }

    async function handleNavInvitation(id, action) {
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
        try {
            const method = action === 'accept' ? 'POST' : 'DELETE';
            const res = await fetch(`/api/family/invitation/${id}/${action}`, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            
            if (res.ok) {
                if(action === 'accept') window.location.href = '/family'; 
                else window.location.reload();
            } else {
                const data = await res.json();
                alert(data.message || 'Failed to process invitation.');
            }
        } catch (err) {
            console.error(err);
            alert('A network error occurred.');
        }
    }
</script>
<x-app-layout>

<style>
    .fp-wrap {
        max-width: 960px;
        margin: 2rem auto;
        padding: 0 1.25rem 3rem;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    /* ── Flash messages ── */
    .fp-flash {
        padding: .6rem 1rem;
        border-radius: .5rem;
        margin-bottom: 1rem;
        font-size: .85rem;
    }
    .fp-flash--success { background:#dcfce7; color:#166534; }
    .fp-flash--error   { background:#fee2e2; color:#991b1b; }

    /* ── Household Switcher ── */
    .fp-switcher-row {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: .25rem;
    }
    .fp-switcher-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #93c5fd;
        display: flex; align-items: center; justify-content: center;
        font-size: .65rem; font-weight: 700; color: #1e3a5f;
        text-decoration: none; border: 2px solid #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,.1);
        transition: transform .15s;
        cursor: pointer; padding: 0;
    }
    .fp-switcher-avatar:hover { transform: scale(1.1); }
    .fp-switcher-add {
        background: #e2e8f0; color: #64748b; border: 2px dashed #cbd5e1;
    }
    .fp-switcher-add:hover { border-color: #3b82f6; color: #3b82f6; }

    /* ── Household header card ── */
    .fp-header {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-radius: 1rem;
        padding: 1.5rem 1.75rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
        position: relative;
        box-shadow: 0 2px 10px rgba(59,130,246,.15);
    }
    .fp-avatar {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: #93c5fd;
        border: 3px solid #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: #1d4ed8;
        flex-shrink: 0;
    }
    .fp-header-info h1 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e3a5f;
        margin: 0 0 .25rem;
    }
    .fp-header-info p {
        font-size: .8rem;
        color: #475569;
        margin: 0;
    }
    .fp-household-code {
        margin-left: auto;
        text-align: right;
        font-size: .78rem;
        color: #475569;
    }
    .fp-household-code span {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1d4ed8;
        font-family: 'Courier New', monospace;
        letter-spacing: .05em;
    }
    .fp-header-actions {
        display: flex;
        gap: .5rem;
        margin-left: .75rem;
    }
    .fp-icon-btn {
        background: rgba(255,255,255,.7);
        border: none;
        border-radius: .5rem;
        width: 34px; height: 34px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #3b82f6;
        transition: background .15s;
    }
    .fp-icon-btn:hover { background: #fff; }
    .fp-icon-btn svg { width:16px; height:16px; }

    /* ── Two-panel grid ── */
    .fp-panels {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 640px) {
        .fp-panels { grid-template-columns: 1fr; }
    }

    /* ── Panel card ── */
    .fp-card {
        background: #fff;
        border-radius: .875rem;
        box-shadow: 0 1px 6px rgba(0,0,0,.08);
        padding: 1.25rem;
    }
    .fp-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .fp-card-title {
        font-size: .9rem;
        font-weight: 600;
        color: #334155;
    }
    .fp-btn-pill {
        background: #3b82f6;
        color: #fff;
        border: none;
        border-radius: 999px;
        padding: .3rem .85rem;
        font-size: .75rem;
        cursor: pointer;
        transition: background .15s;
        font-weight: 500;
    }
    .fp-btn-pill:hover { background: #2563eb; }
    .fp-btn-pill--gray {
        background: #e2e8f0;
        color: #475569;
    }
    .fp-btn-pill--gray:hover { background: #cbd5e1; }

    /* ── Role selector row ── */
    .fp-role-row {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: .75rem;
    }
    .fp-role-select {
        flex: 1;
        border: 1px solid #cbd5e1;
        border-radius: .5rem;
        padding: .4rem .7rem;
        font-size: .82rem;
        color: #334155;
        background: #f8fafc;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .6rem center;
        padding-right: 2rem;
    }
    .fp-edit-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        padding: .25rem;
        border-radius: .375rem;
        transition: color .15s;
    }
    .fp-edit-btn:hover { color: #3b82f6; }
    .fp-edit-btn svg { width:14px; height:14px; }

    /* ── Role task list ── */
    .fp-role-task-list {
        background: #f1f5f9;
        border-radius: .625rem;
        padding: .75rem;
        min-height: 140px;
        font-size: .8rem;
        color: #475569;
    }
    .fp-role-task-item {
        display: flex;
        align-items: flex-start;
        gap: .5rem;
        padding: .35rem 0;
        border-bottom: 1px solid #e2e8f0;
        line-height: 1.4;
    }
    .fp-role-task-item:last-child { border-bottom: none; }
    .fp-task-label {
        font-weight: 600;
        color: #1e40af;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .fp-task-delete {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        color: #cbd5e1;
        padding: 0;
        flex-shrink: 0;
    }
    .fp-task-delete:hover { color: #ef4444; }
    .fp-task-delete svg { width:12px; height:12px; }
    .fp-empty-roles {
        text-align: center;
        color: #94a3b8;
        font-size: .8rem;
        padding-top: 2rem;
    }

    /* ── Member list (right panel) ── */
    .fp-member-status-label {
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #94a3b8;
        margin: .5rem 0 .4rem;
    }
    .fp-member-item {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .45rem .5rem;
        border-radius: .5rem;
        transition: background .15s;
        cursor: default;
    }
    .fp-member-item:hover { background: #f8fafc; }
    .fp-member-avatar {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: #93c5fd;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem;
        font-weight: 600;
        color: #1d4ed8;
        flex-shrink: 0;
    }
    .fp-member-avatar--offline { background: #e2e8f0; color: #94a3b8; }
    .fp-member-name {
        font-size: .85rem;
        font-weight: 500;
        color: #334155;
        flex: 1;
    }
    .fp-member-role-tag {
        font-size: .7rem;
        color: #64748b;
    }
    .fp-location-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #3b82f6;
        padding: .2rem;
        border-radius: .375rem;
        transition: color .15s;
    }
    .fp-location-btn:hover { color: #1d4ed8; }
    .fp-location-btn svg { width: 15px; height: 15px; }
    .fp-active-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #22c55e;
        flex-shrink: 0;
    }
    .fp-offline-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #cbd5e1;
        flex-shrink: 0;
    }

    /* ── Disaster risks chips ── */
    .fp-risks {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem;
        margin-top: .5rem;
    }
    .fp-risk-chip {
        background: #dbeafe;
        color: #1e40af;
        font-size: .7rem;
        font-weight: 500;
        padding: .2rem .6rem;
        border-radius: 999px;
    }

    /* ── Owner-only edit section ── */
    .fp-edit-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }
    .fp-edit-section details summary {
        font-size: .8rem;
        color: #3b82f6;
        cursor: pointer;
    }
    .fp-edit-section details summary:hover { text-decoration: underline; }
    .fp-edit-form label { font-size: .78rem; color: #64748b; display:block; margin-bottom:.25rem; margin-top:.6rem; }
    .fp-edit-form input[type="text"] {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: .5rem;
        padding: .4rem .7rem;
        font-size: .82rem;
        box-sizing: border-box;
        background: #f8fafc;
        color: #334155;
    }
    .fp-edit-form input[type="text"]:focus {
        outline: none; border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.15);
    }
    .fp-checkbox-group {
        display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .35rem;
    }
    .fp-checkbox-group label {
        display: flex; align-items: center; gap: .3rem;
        font-size: .78rem; color: #475569; margin: 0;
    }

    /* ── Modals ── */
    .fp-modal-bg {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.35);
        z-index: 50;
        align-items: center;
        justify-content: center;
    }
    .fp-modal-bg.open { display: flex; }
    .fp-modal {
        background: #fff;
        border-radius: 1rem;
        padding: 1.5rem;
        width: min(480px, 90vw);
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 8px 32px rgba(0,0,0,.18);
        position: relative;
    }
    .fp-modal h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #1e3a5f;
        margin: 0 0 1rem;
    }
    .fp-modal-close {
        position: absolute; top: 1rem; right: 1rem;
        background: none; border: none; cursor: pointer;
        color: #94a3b8; font-size: 1.25rem; line-height: 1;
    }
    .fp-modal-close:hover { color: #475569; }
    .fp-tab-bar {
        display: flex;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1rem;
        gap: .25rem;
    }
    .fp-tab {
        padding: .4rem .9rem;
        font-size: .82rem;
        font-weight: 500;
        color: #94a3b8;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: color .15s, border-color .15s;
    }
    .fp-tab.active { color: #3b82f6; border-bottom-color: #3b82f6; }
    .fp-tab-panel { display: none; }
    .fp-tab-panel.active { display: block; }
    .fp-waiting-item {
        display: flex; align-items: center;
        gap: .65rem; padding: .5rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: .83rem; color: #475569;
    }
    .fp-waiting-item:last-child { border-bottom: none; }
    .fp-invite-input {
        width: 100%; box-sizing: border-box;
        border: 1px solid #e2e8f0; border-radius: .5rem;
        padding: .45rem .75rem; font-size: .83rem;
        color: #334155; margin-bottom: .75rem;
    }
    .fp-invite-input:focus { outline:none; border-color:#3b82f6; }
    .fp-user-found {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: .5rem;
        padding: .6rem .8rem;
        display: flex; align-items: center; gap: .5rem;
        margin-bottom: .75rem;
        font-size: .83rem; color: #0369a1;
    }
    .fp-no-user {
        background: #fef2f2; border: 1px solid #fecaca;
        border-radius: .5rem; padding: .5rem .8rem;
        font-size: .82rem; color: #b91c1c; margin-bottom: .75rem;
    }

    /* roles modal */
    .fp-modal select, .fp-modal textarea {
        width: 100%; box-sizing: border-box;
        border: 1px solid #e2e8f0; border-radius: .5rem;
        padding: .45rem .75rem; font-size: .83rem;
        color: #334155; margin-bottom: .75rem;
        background: #f8fafc;
    }
    .fp-modal textarea { min-height: 90px; resize: vertical; }
    .fp-modal label { font-size: .8rem; color: #64748b; display:block; margin-bottom: .3rem; }
</style>

<div class="fp-wrap">

    {{-- Flash --}}
    @if(session('success'))
        <div class="fp-flash fp-flash--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="fp-flash fp-flash--error">{{ session('error') }}</div>
    @endif

    {{-- ── Household Header ── --}}
    <div class="fp-header">
        <div class="fp-avatar">🏠</div>
        <div class="fp-header-info">
            <div class="fp-switcher-row">
                {{-- Home icon to signify the active household --}}
                <div class="fp-switcher-avatar" style="background:#3b82f6;color:#fff;" title="Current Household">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>

                {{-- Loop through the user's other households --}}
                @foreach(auth()->user()->familyMembers as $fm)
                    @if($fm->familyProfile && $fm->familyProfile->id !== $profile->id)
                        <form method="POST" action="{{ route('family.switch', $fm->familyProfile->id) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="fp-switcher-avatar" title="Switch to {{ $fm->familyProfile->household_name }}">
                                {{ strtoupper(substr($fm->familyProfile->household_name, 0, 2)) }}
                            </button>
                        </form>
                    @endif
                @endforeach

                {{-- Plus Button --}}
                <button class="fp-switcher-avatar fp-switcher-add" title="Create or Join Household" onclick="document.getElementById('createJoinModal').classList.add('open')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>
            </div>

            <h1>{{ $profile->household_name }}</h1>
            <p>{{ $profile->address ?? 'No address set' }}</p>
            @if(!empty($profile->disaster_risks))
                <div class="fp-risks">
                    @foreach($profile->disaster_risks as $risk)
                        <span class="fp-risk-chip">{{ $risk }}</span>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="fp-household-code">
            Household ID:
            <span>{{ $profile->household_code }}</span>
        </div>
        @if(auth()->user()->activeMember()?->is_owner)
        <div class="fp-header-actions">
            {{-- Edit trigger --}}
            <button class="fp-icon-btn" onclick="document.getElementById('editModal').classList.add('open')" title="Edit Household">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            {{-- Delete trigger --}}
            <form method="POST" action="{{ route('family.delete') }}"
                  onsubmit="return confirm('Delete this household? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="fp-icon-btn" style="color:#ef4444;" title="Delete Household">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- ── Two-Panel: Roles + Members ── --}}
    <div class="fp-panels">

        {{-- LEFT: Roles --}}
        <div class="fp-card">
            <div class="fp-card-header">
                <span class="fp-card-title">Roles</span>
                @if(auth()->user()->activeMember()?->is_owner)
                    <button class="fp-btn-pill" onclick="document.getElementById('addRoleModal').classList.add('open')">Add</button>
                @endif
            </div>

            {{-- Role selector --}}
            <div class="fp-role-row">
                <select class="fp-role-select" id="roleFilterSelect" onchange="filterRoleTasks(this.value)">
                    <option value="all">All</option>
                    @foreach($profile->members as $member)
                        <option value="{{ $member->id }}">{{ $member->user->username }}</option>
                    @endforeach
                </select>
                @if(auth()->user()->activeMember()?->is_owner)
                <button class="fp-edit-btn" title="Edit role" onclick="document.getElementById('addRoleModal').classList.add('open')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                @endif
            </div>

            {{-- Task list area --}}
            <div class="fp-role-task-list" id="roleTaskList" style="background: transparent; padding: 0;">
                @php $hasRoles = false; @endphp
                
                @foreach($profile->members as $member)
                    @if($member->roles->count() > 0)
                        @php $hasRoles = true; @endphp
                        
                        <div class="fp-user-role-group" data-member="{{ $member->id }}" style="background: #dbeafe; border-radius: .75rem; padding: 1rem; margin-bottom: 1rem;">
                            
                            <div class="fp-user-role-header" style="font-size: .85rem; font-weight: 600; color: #475569; margin-bottom: .75rem; padding-left: .25rem;">
                                {{ $member->user->username }}
                            </div>

                            @foreach($member->roles as $index => $role)
                                <div class="fp-role-card" style="background: #bfdbfe; border-radius: .5rem; padding: .75rem; display: flex; align-items: flex-start; margin-bottom: .5rem;">
                                    
                                    {{-- Dynamic Role Number --}}
                                    <div style="flex-shrink: 0; font-size: .75rem; color: #64748b; font-weight: 600; width: 60px; margin-top: .1rem;">
                                        Role #{{ $index + 1 }}
                                    </div>
                                    
                                    <div style="flex: 1; margin-left: .5rem;">
                                        <div style="font-size: .85rem; font-weight: 600; color: #334155; margin-bottom: .25rem;">
                                            {{ $role->event_name }}
                                        </div>
                                        <div style="font-size: .8rem; color: #475569; line-height: 1.4;">
                                            {{ $role->description ?? 'No specific description provided.' }}
                                        </div>
                                    </div>

                                    {{-- Delete Action (Now targeting the specific Role ID) --}}
                                    @if(auth()->user()->activeMember()?->is_owner)
                                    <form method="POST" action="{{ route('family.roles.remove', $role->id) }}" style="margin:0; margin-left: 1rem;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: none; border: none; cursor: pointer; color: #334155; padding: .25rem;" onclick="return confirm('Remove this specific role?')" title="Remove Role">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
                
                {{-- Empty State --}}
                @if(!$hasRoles)
                    <div style="background: #f1f5f9; border-radius: .625rem; padding: .75rem; min-height: 140px; display: flex; align-items: center; justify-content: center;">
                        <p class="fp-empty-roles" style="text-align:center; color:#94a3b8; margin: 0; padding: 0;">Start adding roles!</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Family Members --}}
        <div class="fp-card">
            <div class="fp-card-header">
                <span class="fp-card-title">Household Members</span>
                @if(auth()->user()->activeMember()?->is_owner)
                    <div style="display:flex;gap:.4rem;">
                        <button class="fp-btn-pill" onclick="document.getElementById('addMemberModal').classList.add('open')">Add</button>
                        <button class="fp-btn-pill fp-btn-pill--gray" onclick="document.getElementById('editMembersModal').classList.add('open')">Edit</button>
                    </div>
                @endif
            </div>

            {{-- Active members --}}
            @php
                $activeMembers  = $profile->members->filter(fn($m) => $m->location_sharing ?? false);
                $offlineMembers = $profile->members->filter(fn($m) => !($m->location_sharing ?? false));
            @endphp

            @if($activeMembers->count())
                <p class="fp-member-status-label">Active</p>
                @foreach($activeMembers as $member)
                    <div class="fp-member-item">
                        <div class="fp-active-dot"></div>
                        <div class="fp-member-avatar">{{ strtoupper(substr($member->user->username, 0, 1)) }}</div>
                        <div>
                            <div class="fp-member-name">
                                {{ $member->user->username }}
                                @if($member->is_owner)
                                    <span style="font-size: 0.7rem; color: #3b82f6; font-weight: 700; margin-left: 0.25rem;">(Owner)</span>
                                @endif
                            </div>
                        </div>
                        <button class="fp-location-btn" title="View location" onclick="document.getElementById('locationModal').classList.add('open')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </button>
                    </div>
                @endforeach
            @endif

            @if($offlineMembers->count())
                <p class="fp-member-status-label" style="margin-top:.75rem;">Offline</p>
                @foreach($offlineMembers as $member)
                    <div class="fp-member-item">
                        <div class="fp-offline-dot"></div>
                        <div class="fp-member-avatar fp-member-avatar--offline">{{ strtoupper(substr($member->user->username, 0, 1)) }}</div>
                        <div>
                            <div class="fp-member-name">
                                {{ $member->user->username }}
                                @if($member->is_owner)
                                    <span style="font-size: 0.7rem; color: #3b82f6; font-weight: 700; margin-left: 0.25rem;">(Owner)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════
     MODALS
══════════════════════════════════ --}}

{{-- ── Add Members Modal (Waiting Room + Invitation tabs) ── --}}
<div class="fp-modal-bg" id="addMemberModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('addMemberModal').classList.remove('open')">×</button>
        <h3>Add Members</h3>
        <div class="fp-tab-bar">
            <div class="fp-tab active" onclick="switchTab('waiting')">Waiting Room</div>
            <div class="fp-tab" onclick="switchTab('invitation')">Invitation</div>
        </div>

        {{-- Waiting Room tab --}}
        <div class="fp-tab-panel active" id="tab-waiting">
            @php $pending = $profile->invitations()->where('status','pending')->get(); @endphp
            @if($pending->isEmpty())
                <p style="text-align:center;color:#94a3b8;font-size:.83rem;padding:1.5rem 0;">No pending invitations…</p>
            @else
                @foreach($pending as $inv)
                    <div class="fp-waiting-item">
                        <div class="fp-member-avatar" style="width:28px;height:28px;font-size:.75rem;">
                            {{ strtoupper(substr($inv->invitee->username ?? '?', 0, 1)) }}
                        </div>
                        <span style="flex:1;">{{ $inv->invitee->username ?? 'Unknown' }}</span>
                        <span style="font-size:.72rem;color:#94a3b8;">Pending</span>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Invitation tab --}}
        <div class="fp-tab-panel" id="tab-invitation">
            <form method="POST" action="{{ route('family.invite') }}">
                @csrf
                <label>Find username</label>
                <input type="text" name="username" class="fp-invite-input" placeholder="Input username…" />
                @error('username')
                    <div class="fp-no-user">{{ $message }}</div>
                @enderror
                <button type="submit" class="fp-btn-full fp-btn-blue" style="border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;">
                    Send Invite
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ── Add Role Modal ── --}}
<div class="fp-modal-bg" id="addRoleModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('addRoleModal').classList.remove('open')">×</button>
        <h3>Add Roles</h3>
        <form method="POST" action="{{ route('family.members.role') ?? '#' }}">
            @csrf @method('PUT')
            <label>Member</label>
            <select name="member_id">
                @foreach($profile->members as $member)
                    <option value="{{ $member->id }}">{{ $member->user->username }}</option>
                @endforeach
            </select>
            <label>Event / Role</label>
            <input type="text" name="role" class="fp-invite-input" placeholder="e.g. Evacuation Lead" style="margin-bottom:.75rem;" />
            <label>Description</label>
            <textarea name="description" placeholder="Describe responsibilities…"></textarea>
            <button type="submit" class="fp-btn-full fp-btn-blue" style="border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;width:100%;">
                Save Role
            </button>
        </form>
    </div>
</div>

{{-- ── Edit Household Modal ── --}}
@if(auth()->user()->activeMember()?->is_owner)
<div class="fp-modal-bg" id="editModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('editModal').classList.remove('open')">×</button>
        <h3>Edit Household</h3>
        <form method="POST" action="{{ route('family.update') }}" class="fp-edit-form">
            @csrf @method('PUT')
            <label>Household Name</label>
            <input type="text" name="household_name" value="{{ old('household_name', $profile->household_name) }}" required />
            @error('household_name')<span class="fp-error-text">{{ $message }}</span>@enderror
            <label>Address</label>
            <input type="text" name="address" value="{{ old('address', $profile->address) }}" placeholder="e.g. Tacloban City, Leyte" />
            <label style="margin-top:.75rem;margin-bottom:.4rem;display:block;">Disaster Risks</label>
            <div class="fp-checkbox-group">
                @foreach(['Typhoon','Flood','Earthquake','Landslide','Volcanic Eruption','Fire'] as $risk)
                    <label>
                        <input type="checkbox" name="disaster_risks[]" value="{{ $risk }}"
                               {{ in_array($risk, old('disaster_risks', $profile->disaster_risks ?? [])) ? 'checked' : '' }}>
                        {{ $risk }}
                    </label>
                @endforeach
            </div>
            <button type="submit" class="fp-btn-full fp-btn-blue" style="margin-top:1rem;border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;">
                Save Changes
            </button>
        </form>
    </div>
</div>
@endif

{{-- ── Location Modal ── --}}
<div class="fp-modal-bg" id="locationModal">

    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('locationModal').classList.remove('open')">×</button>
        <h3>View Location</h3>
        @php
            $sharingMembers = $profile->members->filter(fn($m) => $m->location_sharing && $m->latitude && $m->longitude);
        @endphp
        @if($sharingMembers->isEmpty())
            <p style="color:#94a3b8;font-size:.85rem;text-align:center;padding:1.5rem 0;">
                No members are currently sharing their location.
            </p>
        @else
            <div id="locationMap" style="width:100%;height:280px;border-radius:.625rem;background:#e2e8f0;overflow:hidden;">
                <iframe
                    width="100%" height="280"
                    style="border:0;border-radius:.625rem;"
                    loading="lazy"
                    src="https://www.google.com/maps?q={{ $sharingMembers->first()->latitude }},{{ $sharingMembers->first()->longitude }}&output=embed">
                </iframe>
            </div>
        @endif
    </div>
</div>

{{-- ── Create / Join Household Modal ── --}}
<div class="fp-modal-bg" id="createJoinModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('createJoinModal').classList.remove('open')">×</button>
        <h3>Add a Household</h3>
        <div class="fp-tab-bar">
            <div class="fp-tab active" onclick="switchCreateJoinTab('create')">Create</div>
            <div class="fp-tab" onclick="switchCreateJoinTab('join')">Join</div>
        </div>

        {{-- Create Tab --}}
        <div class="fp-tab-panel active" id="tab-cj-create">
            <form method="POST" action="{{ route('onboarding.create') }}" class="fp-edit-form">
                @csrf
                <label>Household Name</label>
                <input type="text" name="household_name" placeholder="e.g. Dela Cruz Family" required />
                    
                <label>Home Address (Optional)</label>
                <input type="text" name="address" placeholder="e.g. Tacloban City, Leyte" />
                    
                <label>Disaster Risks</label>
                <div class="fp-checkbox-group">
                    @foreach(['Typhoon', 'Flood', 'Earthquake', 'Landslide', 'Volcanic Eruption', 'Fire'] as $risk)
                        <label>
                            <input type="checkbox" name="disaster_risks[]" value="{{ $risk }}"> {{ $risk }}
                        </label>
                    @endforeach
                </div>
                    
                <button type="submit" class="fp-btn-full fp-btn-green" style="margin-top:1rem;border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;">
                    Create Household
                </button>
            </form>
        </div>

        {{-- Join Tab --}}
        <div class="fp-tab-panel" id="tab-cj-join">
            <form method="POST" action="{{ route('onboarding.join') }}" class="fp-edit-form">
                @csrf
                <label>Household Code</label>
                <input type="text" name="household_code" placeholder="e.g. AB12CD" maxlength="6" required />
                    
                <button type="submit" class="fp-btn-full fp-btn-dark" style="margin-top:1rem;border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;">
                    Join Household
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ── Edit / Manage Members Modal ── --}}
<div class="fp-modal-bg" id="editMembersModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('editMembersModal').classList.remove('open')">×</button>
        
        {{-- Header showing total count --}}
        <h3 style="margin-bottom: 0.5rem;">Manage Members ({{ $profile->members->count() }})</h3>
        <p style="font-size: .8rem; color: #64748b; margin-top: 0; margin-bottom: 1.5rem;">Remove members from your household.</p>

        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @foreach($profile->members as $member)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: .75rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: .5rem;">
                    
                    {{-- Member Info --}}
                    <div style="display: flex; align-items: center; gap: .75rem;">
                        <div class="fp-member-avatar" style="width: 32px; height: 32px; font-size: .8rem;">
                            {{ strtoupper(substr($member->user->username, 0, 1)) }}
                        </div>
                        <div style="font-size: .85rem; font-weight: 600; color: #334155;">
                            {{ $member->user->username }}
                            @if($member->is_owner)
                                <span style="font-size: 0.7rem; color: #3b82f6; margin-left: 0.25rem;">(Owner)</span>
                            @endif
                        </div>
                    </div>

                    {{-- Remove Button (Hidden for the Owner) --}}
                    @if(!$member->is_owner)
                        <form method="POST" action="{{ route('family.member.remove', $member->id) }}" style="margin: 0;">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    style="background: #fee2e2; color: #ef4444; border: none; border-radius: .375rem; padding: .35rem .75rem; font-size: .75rem; font-weight: 600; cursor: pointer; transition: background .15s;"
                                    onmouseover="this.style.background='#fca5a5'"
                                    onmouseout="this.style.background='#fee2e2'"
                                    onclick="return confirm('Are you sure you want to remove {{ $member->user->username }} from the household?')">
                                Remove
                            </button>
                        </form>
                    @endif

                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Tab switching for Add Members modal
    function switchTab(tab) {
        document.querySelectorAll('.fp-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.fp-tab-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        const tabs = document.querySelectorAll('.fp-tab');
        if (tab === 'waiting') tabs[0].classList.add('active');
        else tabs[1].classList.add('active');
    }

    // Role task filter by member
    function filterRoleTasks(memberId) {
        document.querySelectorAll('.fp-user-role-group').forEach(group => {
            // 1. Show or hide the entire group based on the dropdown
            if (memberId === 'all' || group.dataset.member === memberId) {
                group.style.display = 'block';
            } else {
                group.style.display = 'none';
            }

            // 2. Hide the username header if a specific member is selected
            const header = group.querySelector('.fp-user-role-header');
            if (header) {
                header.style.display = (memberId === 'all') ? 'block' : 'none';
            }
        });
    }

    // Tab switching for the Create/Join modal
    function switchCreateJoinTab(tab) {
        // Remove active class from tabs and panels specifically inside this modal
        document.querySelectorAll('#createJoinModal .fp-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('#createJoinModal .fp-tab-panel').forEach(p => p.classList.remove('active'));
        
        // Add active class to the selected ones
        if (tab === 'create') {
            document.querySelectorAll('#createJoinModal .fp-tab')[0].classList.add('active');
            document.getElementById('tab-cj-create').classList.add('active');
        } else {
            document.querySelectorAll('#createJoinModal .fp-tab')[1].classList.add('active');
            document.getElementById('tab-cj-join').classList.add('active');
        }
    }

    // Close modal on backdrop click
    document.querySelectorAll('.fp-modal-bg').forEach(bg => {
        bg.addEventListener('click', e => {
            if (e.target === bg) bg.classList.remove('open');
        });
    });

    // Re-open edit modal on validation error
    @if($errors->has('household_name') || $errors->has('address'))
        document.getElementById('editModal')?.classList.add('open');
    @endif

    // Re-open Add Members modal and switch to Invitation tab if there's a username error
    @if($errors->has('username'))
        const addMemberModal = document.getElementById('addMemberModal');
        if (addMemberModal) {
            addMemberModal.classList.add('open');
            switchTab('invitation');
        }
    @endif
</script>

</x-app-layout>
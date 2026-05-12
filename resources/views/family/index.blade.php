<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-[#1e3a5f] leading-tight">
                {{ __('Household Management') }}
            </h2>
        </div>
    </x-slot>

<style>
    /* ── EXACT CSS FROM YOUR ORIGINAL COPY ── */
    .fp-wrap {
        max-width: 960px;
        margin: 2rem auto;
        padding: 0 1.25rem 3rem;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .fp-flash { padding: .6rem 1rem; border-radius: .5rem; margin-bottom: 1rem; font-size: .85rem; }
    .fp-flash--success { background:#dcfce7; color:#166534; }
    .fp-flash--error   { background:#fee2e2; color:#991b1b; }

    .fp-switcher-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .25rem; }
    .fp-switcher-avatar {
        width: 28px; height: 28px; border-radius: 50%; background: #93c5fd;
        display: flex; align-items: center; justify-content: center;
        font-size: .65rem; font-weight: 700; color: #1e3a5f;
        text-decoration: none; border: 2px solid #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,.1); transition: transform .15s; cursor: pointer; padding: 0;
    }
    .fp-switcher-avatar:hover { transform: scale(1.1); }
    .fp-switcher-add { background: #e2e8f0; color: #64748b; border: 2px dashed #cbd5e1; }
    .fp-switcher-add:hover { border-color: #3b82f6; color: #3b82f6; }

    .fp-header {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-radius: 1rem; padding: 1.5rem 1.75rem;
        display: flex; align-items: center; gap: 1.25rem;
        margin-bottom: 1.5rem; position: relative;
        box-shadow: 0 2px 10px rgba(59,130,246,.15);
    }
    .fp-avatar {
        width: 72px; height: 72px; border-radius: 50%; background: #93c5fd;
        border: 3px solid #fff; display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: #1d4ed8; flex-shrink: 0;
    }
    .fp-header-info h1 { font-size: 1.35rem; font-weight: 700; color: #1e3a5f; margin: 0 0 .25rem; }
    .fp-header-info p { font-size: .8rem; color: #475569; margin: 0; }
    .fp-household-code { margin-left: auto; text-align: right; font-size: .78rem; color: #475569; }
    .fp-household-code span { display: block; font-size: 1.1rem; font-weight: 700; color: #1d4ed8; font-family: 'Courier New', monospace; letter-spacing: .05em; }
    
    .fp-header-actions { display: flex; gap: .5rem; margin-left: .75rem; }
    .fp-icon-btn {
        background: rgba(255,255,255,.7); border: none; border-radius: .5rem;
        width: 34px; height: 34px; cursor: pointer; display: flex; align-items: center; justify-content: center;
        color: #3b82f6; transition: background .15s;
    }
    .fp-icon-btn:hover { background: #fff; }
    .fp-icon-btn svg { width:16px; height:16px; }

    .fp-panels { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
    @media (max-width: 640px) { .fp-panels { grid-template-columns: 1fr; } }

    .fp-card { background: #fff; border-radius: .875rem; box-shadow: 0 1px 6px rgba(0,0,0,.08); padding: 1.25rem; }
    .fp-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .fp-card-title { font-size: .9rem; font-weight: 600; color: #334155; }
    
    .fp-btn-pill {
        background: #3b82f6; color: #fff; border: none; border-radius: 999px;
        padding: .3rem .85rem; font-size: .75rem; cursor: pointer; transition: background .15s; font-weight: 500;
    }
    .fp-btn-pill:hover { background: #2563eb; }
    .fp-btn-pill--gray { background: #e2e8f0; color: #475569; }
    .fp-btn-pill--gray:hover { background: #cbd5e1; }

    .fp-role-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .75rem; }
    .fp-role-select {
        flex: 1; border: 1px solid #cbd5e1; border-radius: .5rem; padding: .4rem .7rem;
        font-size: .82rem; color: #334155; background: #f8fafc; appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right .6rem center; padding-right: 2rem;
    }
    .fp-edit-btn { background: none; border: none; cursor: pointer; color: #94a3b8; padding: .25rem; border-radius: .375rem; transition: color .15s; }
    .fp-edit-btn:hover { color: #3b82f6; }
    .fp-edit-btn svg { width:14px; height:14px; }

    .fp-role-task-list { background: transparent; border-radius: .625rem; padding: 0; min-height: 140px; font-size: .8rem; color: #475569; }
    .fp-empty-roles { text-align: center; color: #94a3b8; font-size: .8rem; padding-top: 2rem; }

    .fp-member-status-label { font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; margin: .5rem 0 .4rem; }
    .fp-member-item { display: flex; align-items: center; gap: .65rem; padding: .45rem .5rem; border-radius: .5rem; transition: background .15s; cursor: default; }
    .fp-member-item:hover { background: #f8fafc; }
    .fp-member-avatar { width: 34px; height: 34px; border-radius: 50%; background: #93c5fd; display: flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 600; color: #1d4ed8; flex-shrink: 0; }
    .fp-member-avatar--offline { background: #e2e8f0; color: #94a3b8; }
    .fp-member-name { font-size: .85rem; font-weight: 500; color: #334155; flex: 1; }
    
    .fp-location-btn { background: none; border: none; cursor: pointer; color: #3b82f6; padding: .2rem; border-radius: .375rem; transition: color .15s; }
    .fp-location-btn:hover { color: #1d4ed8; }
    .fp-location-btn svg { width: 15px; height: 15px; }
    .fp-active-dot { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; flex-shrink: 0; }
    .fp-offline-dot { width: 7px; height: 7px; border-radius: 50%; background: #cbd5e1; flex-shrink: 0; }

    .fp-risks { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .5rem; }
    .fp-risk-chip { background: #dbeafe; color: #1e40af; font-size: .7rem; font-weight: 500; padding: .2rem .6rem; border-radius: 999px; }

    /* Modals */
    .fp-modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.35); z-index: 50; align-items: center; justify-content: center; }
    .fp-modal-bg.open { display: flex; }
    .fp-modal { background: #fff; border-radius: 1rem; padding: 1.5rem; width: min(480px, 90vw); max-height: 80vh; overflow-y: auto; box-shadow: 0 8px 32px rgba(0,0,0,.18); position: relative; }
    .fp-modal h3 { font-size: 1rem; font-weight: 700; color: #1e3a5f; margin: 0 0 1rem; }
    .fp-modal-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 1.25rem; line-height: 1; }
    .fp-modal-close:hover { color: #475569; }
    .fp-tab-bar { display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 1rem; gap: .25rem; }
    .fp-tab { padding: .4rem .9rem; font-size: .82rem; font-weight: 500; color: #94a3b8; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: color .15s, border-color .15s; }
    .fp-tab.active { color: #3b82f6; border-bottom-color: #3b82f6; }
    .fp-tab-panel { display: none; }
    .fp-tab-panel.active { display: block; }
    .fp-waiting-item { display: flex; align-items: center; gap: .65rem; padding: .5rem 0; border-bottom: 1px solid #f1f5f9; font-size: .83rem; color: #475569; }
    .fp-waiting-item:last-child { border-bottom: none; }
    
    .fp-edit-form label { font-size: .78rem; color: #64748b; display:block; margin-bottom:.25rem; margin-top:.6rem; }
    .fp-edit-form input[type="text"] { width: 100%; border: 1px solid #e2e8f0; border-radius: .5rem; padding: .4rem .7rem; font-size: .82rem; box-sizing: border-box; background: #f8fafc; color: #334155; }
    .fp-edit-form input[type="text"]:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
    .fp-checkbox-group { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .35rem; }
    .fp-checkbox-group label { display: flex; align-items: center; gap: .3rem; font-size: .78rem; color: #475569; margin: 0; }
    
    .fp-modal select, .fp-modal textarea, .fp-invite-input {
        width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: .5rem;
        padding: .45rem .75rem; font-size: .83rem; color: #334155; margin-bottom: .75rem; background: #f8fafc;
    }
    .fp-modal textarea { min-height: 90px; resize: vertical; }
    .fp-modal label { font-size: .8rem; color: #64748b; display:block; margin-bottom: .3rem; }
</style>

<div class="fp-wrap">
    {{-- Flash Banner --}}
    <div id="familyFlash" class="fp-flash" style="display:none;"></div>
    @if(session('success'))
        <div class="fp-flash fp-flash--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="fp-flash fp-flash--error">{{ session('error') }}</div>
    @endif

    {{-- ── Household Header ── --}}
    @if(isset($profile))
    <div class="fp-header">
        <div class="fp-avatar">🏠</div>
        <div class="fp-header-info">
            <div class="fp-switcher-row">
                <div class="fp-switcher-avatar" style="background:#3b82f6;color:#fff;" title="Current Household">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                @foreach(auth()->user()->familyMembers as $fm)
                    @if($fm->familyProfile && $fm->familyProfile->id !== $profile->id)
                        <button type="button" class="fp-switcher-avatar" title="Switch to {{ $fm->familyProfile->household_name }}" onclick="handleApiAction('/api/family/switch/{{ $fm->familyProfile->id }}', 'POST')">
                            {{ strtoupper(substr($fm->familyProfile->household_name, 0, 2)) }}
                        </button>
                    @endif
                @endforeach
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
            <button class="fp-icon-btn" onclick="document.getElementById('editModal').classList.add('open')" title="Edit Household">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button type="button" class="fp-icon-btn" style="color:#ef4444;" title="Delete Household" onclick="if(confirm('Delete this household? This cannot be undone.')) handleApiAction('/api/family/delete', 'DELETE', true)">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
            </button>
        </div>
        @endif
    </div>
    @endif

    <div class="fp-panels">
        {{-- LEFT: Roles (AJAX Target) --}}
        <div class="fp-card">
            <div class="fp-card-header">
                <span class="fp-card-title">Roles</span>
                @if(auth()->user()->activeMember()?->is_owner)
                    <button class="fp-btn-pill" onclick="document.getElementById('addRoleModal').classList.add('open')">Add</button>
                @endif
            </div>

            <div class="fp-role-row">
                <select class="fp-role-select" id="roleFilterSelect" onchange="filterRoleTasks(this.value)">
                    <option value="all">All</option>
                </select>
            </div>

            <div class="fp-role-task-list" id="roleTaskList" style="background: transparent; padding: 0;">
                <div style="background: #f1f5f9; border-radius: .625rem; padding: .75rem; min-height: 140px; display: flex; align-items: center; justify-content: center;">
                    <p class="fp-empty-roles">Loading roles...</p>
                </div>
            </div>
        </div>

        {{-- RIGHT: Members (AJAX Target) --}}
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
            
            <div id="memberList">
                <p class="fp-empty-roles" style="margin-top: 1.5rem;">Loading members...</p>
            </div>
        </div>
    </div>
</div>

{{-- MODALS --}}

{{-- Add Members Modal: Waiting Room + Invitation tabs --}}
<div class="fp-modal-bg" id="addMemberModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('addMemberModal').classList.remove('open')">×</button>
        <h3>Add Members</h3>
        <div class="fp-tab-bar">
            <div class="fp-tab active" onclick="switchTab('waiting')">Waiting Room</div>
            <div class="fp-tab" onclick="switchTab('invitation')">Invitation</div>
        </div>

        <div class="fp-tab-panel active" id="tab-waiting">
            @if(isset($profile))
                @php 
                    $pending = $profile->invitations()->where('status','pending')->whereNotNull('invited_by')->get(); 
                    $joinReqs = $profile->invitations()->where('status','pending')->whereNull('invited_by')->get();
                @endphp

                @if($joinReqs->count())
                    <p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem;">Join Requests</p>
                    @foreach($joinReqs as $req)
                        <div class="fp-waiting-item" style="padding:.6rem .5rem;">
                            <div class="fp-member-avatar" style="width:28px;height:28px;font-size:.75rem;">
                                {{ strtoupper(substr($req->invitedUser->username ?? '?', 0, 1)) }}
                            </div>
                            <span style="flex:1;">{{ $req->invitedUser->username ?? 'Unknown' }} <span style="font-size:.7rem;color:#94a3b8;">(via code)</span></span>
                            <div style="display:flex;gap:.3rem;">
                                <button type="button" onclick="handleApiAction('/api/family/invitation/{{$req->id}}/approve', 'POST')" style="background:#22c55e;color:white;border:none;border-radius:.375rem;padding:.25rem .5rem;font-size:.7rem;cursor:pointer;white-space:nowrap;">Approve</button>
                                <button type="button" onclick="handleApiAction('/api/family/invitation/{{$req->id}}/decline', 'DELETE')" style="background:#ef4444;color:white;border:none;border-radius:.375rem;padding:.25rem .5rem;font-size:.7rem;cursor:pointer;white-space:nowrap;">Decline</button>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if($pending->isEmpty() && $joinReqs->isEmpty())
                    <p style="text-align:center;color:#94a3b8;font-size:.83rem;padding:1.5rem 0;">No pending invitations…</p>
                @else
                    @foreach($pending as $inv)
                        <div class="fp-waiting-item">
                            <div class="fp-member-avatar" style="width:28px;height:28px;font-size:.75rem;">
                                {{ strtoupper(substr($inv->invitedUser->username ?? '?', 0, 1)) }}
                            </div>
                            <span style="flex:1;">{{ $inv->invitedUser->username ?? 'Unknown' }}</span>
                            <span style="font-size:.72rem;color:#94a3b8;">Pending</span>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>

        <div class="fp-tab-panel" id="tab-invitation">
            <form onsubmit="submitAjaxForm(event, '/api/family/invite', 'POST')">
                <label>Find username</label>
                <input type="text" name="username" class="fp-invite-input" placeholder="Input username…" required />
                <button type="submit" style="background:#3b82f6;color:white;border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;width:100%;">
                    Send Invite
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ── Add Role Modal ── --}}
@if(isset($profile))
<div class="fp-modal-bg" id="addRoleModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('addRoleModal').classList.remove('open')">×</button>
        <h3>Add Roles</h3>
        <form onsubmit="submitAjaxForm(event, '/api/family/members/role', 'PUT')">
            <label>Member</label>
            <select name="member_id" required>
                @foreach($profile->members as $member)
                    <option value="{{ $member->id }}">{{ $member->user->username }}</option>
                @endforeach
            </select>
            <label>Event / Role</label>
            <input type="text" name="role" class="fp-invite-input" placeholder="e.g. Evacuation Lead" style="margin-bottom:.75rem;" required />
            <label>Description</label>
            <textarea name="description" placeholder="Describe responsibilities…"></textarea>
            <button type="submit" style="background:#3b82f6;color:white;border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;width:100%;">
                Save Role
            </button>
        </form>
    </div>
</div>
@endif

{{-- ── Edit Household Modal ── --}}
@if(isset($profile) && auth()->user()->activeMember()?->is_owner)
<div class="fp-modal-bg" id="editModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('editModal').classList.remove('open')">×</button>
        <h3>Edit Household</h3>
        <form onsubmit="submitAjaxForm(event, '/api/family/update', 'PUT')" class="fp-edit-form">
            <label>Household Name</label>
            <input type="text" name="household_name" value="{{ $profile->household_name }}" required />
            <label>Address</label>
            <input type="text" name="address" value="{{ $profile->address }}" placeholder="e.g. Tacloban City, Leyte" />
            <label style="margin-top:.75rem;margin-bottom:.4rem;display:block;">Disaster Risks</label>
            <div class="fp-checkbox-group">
                @foreach(['Typhoon','Flood','Earthquake','Landslide','Volcanic Eruption','Fire'] as $risk)
                    <label>
                        <input type="checkbox" name="disaster_risks[]" value="{{ $risk }}"
                               {{ in_array($risk, $profile->disaster_risks ?? []) ? 'checked' : '' }}>
                        {{ $risk }}
                    </label>
                @endforeach
            </div>
            <button type="submit" style="background:#3b82f6;color:white;margin-top:1rem;border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;width:100%;">
                Save Changes
            </button>
        </form>
    </div>
</div>
@endif

{{-- ── Create / Join Household Modal ── --}}
<div class="fp-modal-bg" id="createJoinModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('createJoinModal').classList.remove('open')">×</button>
        <h3>Add a Household</h3>
        <div class="fp-tab-bar">
            <div class="fp-tab active" onclick="switchCreateJoinTab('create')">Create</div>
            <div class="fp-tab" onclick="switchCreateJoinTab('join')">Join</div>
        </div>

        <div class="fp-tab-panel active" id="tab-cj-create">
            <form onsubmit="submitAjaxForm(event, '/api/family/create', 'POST', true)" class="fp-edit-form">
                <label>Household Name</label>
                <input type="text" name="household_name" placeholder="e.g. Dela Cruz Family" required />
                <label>Home Address (Optional)</label>
                <input type="text" name="address" placeholder="e.g. Tacloban City, Leyte" />
                <label>Disaster Risks</label>
                <div class="fp-checkbox-group">
                    @foreach(['Typhoon', 'Flood', 'Earthquake', 'Landslide', 'Volcanic Eruption', 'Fire'] as $risk)
                        <label><input type="checkbox" name="disaster_risks[]" value="{{ $risk }}"> {{ $risk }}</label>
                    @endforeach
                </div>
                <button type="submit" style="background:#22c55e;color:white;margin-top:1rem;border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;width:100%;">
                    Create Household
                </button>
            </form>
        </div>

        <div class="fp-tab-panel" id="tab-cj-join">
            <form onsubmit="submitAjaxForm(event, '/api/family/join', 'POST', true)" class="fp-edit-form">
                <label>Household Code</label>
                <input type="text" name="household_code" placeholder="e.g. AB12CD" maxlength="6" required style="text-transform:uppercase;font-family:monospace;letter-spacing:2px;text-align:center;" />
                <button type="submit" style="background:#1e293b;color:white;margin-top:1rem;border-radius:.5rem;border:none;padding:.5rem;font-size:.83rem;cursor:pointer;width:100%;">
                    Join Household
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ── Edit / Manage Members Modal ── --}}
@if(isset($profile))
<div class="fp-modal-bg" id="editMembersModal">
    <div class="fp-modal">
        <button class="fp-modal-close" onclick="document.getElementById('editMembersModal').classList.remove('open')">×</button>
        <h3 style="margin-bottom: 0.5rem;">Manage Members ({{ $profile->members->count() }})</h3>
        <p style="font-size: .8rem; color: #64748b; margin-top: 0; margin-bottom: 1.5rem;">Remove members from your household.</p>

        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @foreach($profile->members as $member)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: .75rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: .5rem;">
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
                    @if(!$member->is_owner)
                        <button type="button" 
                                style="background: #fee2e2; color: #ef4444; border: none; border-radius: .375rem; padding: .35rem .75rem; font-size: .75rem; font-weight: 600; cursor: pointer;"
                                onclick="if(confirm('Remove {{ $member->user->username }} from the household?')) handleApiAction('/api/family/members/{{$member->id}}/remove', 'DELETE')">
                            Remove
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const IS_OWNER = @json(auth()->user()->activeMember()?->is_owner ?? false);

    // ─────────────────────────────────────────────────────────────────
    // Dynamic Flash Messages
    // ─────────────────────────────────────────────────────────────────
    function showFlash(message, type = 'success') {
        const flash = document.getElementById('familyFlash');
        if (!flash) return;
        
        flash.textContent = message;
        flash.className = `fp-flash fp-flash--${type}`;
        flash.style.display = 'block';
        
        // Scroll to top so the user actually sees it
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Hide after 4 seconds
        setTimeout(() => { 
            flash.style.opacity = '0';
            setTimeout(() => {
                flash.style.display = 'none'; 
                flash.style.opacity = '1';
            }, 300);
        }, 4000);
    }

    // ─────────────────────────────────────────────────────────────────
    // Universal AJAX Actions
    // ─────────────────────────────────────────────────────────────────
    async function handleApiAction(endpoint, method, redirectOnSuccess = false) {
        try {
            const res = await fetch(endpoint, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            const data = await res.json().catch(() => ({}));
            
            if (res.ok) {
                // Store the success message before reloading so it survives the refresh
                sessionStorage.setItem('fp_flash', data.message || 'Action completed successfully.');
                
                if (redirectOnSuccess) window.location.href = '/onboarding';
                else window.location.reload();
            } else {
                showFlash(data.message || 'Action failed.', 'error');
            }
        } catch (err) {
            console.error('API Error:', err);
            showFlash('A network error occurred. Please try again.', 'error');
        }
    }

    async function submitAjaxForm(e, endpoint, method, redirectOnSuccess = false) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');
        const ogText = btn.innerText;
        btn.innerText = 'Processing...';
        btn.disabled = true;

        const formData = new FormData(form);
        const payload = {};
        formData.forEach((value, key) => {
            if (key.endsWith('[]')) {
                const k = key.slice(0, -2);
                if (!payload[k]) payload[k] = [];
                payload[k].push(value);
            } else payload[key] = value;
        });

        try {
            const res = await fetch(endpoint, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json().catch(() => ({}));
            
            if (res.ok) {
                // Store the success message before reloading
                sessionStorage.setItem('fp_flash', data.message || 'Action completed successfully.');
                
                if(redirectOnSuccess) window.location.href = '/family';
                else window.location.reload();
            } else {
                // Show errors dynamically without reloading
                showFlash(data.message || Object.values(data.errors || {})[0]?.[0] || 'Validation failed.', 'error');
            }
        } catch (err) {
            showFlash('A network error occurred. Please try again.', 'error');
        } finally {
            btn.innerText = ogText;
            btn.disabled = false;
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // Dynamic Rendering (Exact match to original CSS)
    // ─────────────────────────────────────────────────────────────────
    function renderRoles(members) {
        const container = document.getElementById('roleTaskList');
        const filterSelect = document.getElementById('roleFilterSelect');
        let html = '';
        let hasRoles = false;
        
        if(filterSelect && filterSelect.options.length <= 1) {
            filterSelect.innerHTML = '<option value="all">All</option>' + members.map(m => `<option value="${m.id}">${m.user?.username || 'Unknown'}</option>`).join('');
        }

        members.forEach(member => {
            if (member.roles && member.roles.length > 0) {
                hasRoles = true;
                const username = member.user?.username || 'Unknown User';
                
                let rolesHtml = member.roles.map((role, idx) => `
                    <div class="fp-role-card" style="background: #bfdbfe; border-radius: .5rem; padding: .75rem; display: flex; align-items: flex-start; margin-bottom: .5rem;">
                        <div style="flex-shrink: 0; font-size: .75rem; color: #64748b; font-weight: 600; width: 60px; margin-top: .1rem;">
                            Role #${idx + 1}
                        </div>
                        <div style="flex: 1; margin-left: .5rem;">
                            <div style="font-size: .85rem; font-weight: 600; color: #334155; margin-bottom: .25rem;">
                                ${role.event_name}
                            </div>
                            <div style="font-size: .8rem; color: #475569; line-height: 1.4;">
                                ${role.description || 'No specific description provided.'}
                            </div>
                        </div>
                        ${IS_OWNER ? `
                        <button type="button" style="background: none; border: none; cursor: pointer; color: #334155; padding: .25rem; margin-left: 1rem;" onclick="if(confirm('Remove this specific role?')) handleApiAction('/api/family/roles/${role.id}/remove', 'DELETE')" title="Remove Role">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/></svg>
                        </button>` : ''}
                    </div>
                `).join('');

                html += `
                    <div class="fp-user-role-group" data-member="${member.id}" style="background: #dbeafe; border-radius: .75rem; padding: 1rem; margin-bottom: 1rem;">
                        <div class="fp-user-role-header" style="font-size: .85rem; font-weight: 600; color: #475569; margin-bottom: .75rem; padding-left: .25rem;">
                            ${username}
                        </div>
                        ${rolesHtml}
                    </div>
                `;
            }
        });

        if (!hasRoles) {
            html = `<div style="background: #f1f5f9; border-radius: .625rem; padding: .75rem; min-height: 140px; display: flex; align-items: center; justify-content: center;">
                        <p class="fp-empty-roles" style="margin:0; padding:0;">Start adding roles!</p>
                    </div>`;
        }
        container.innerHTML = html;
    }

    function renderMembers(members) {
        const container = document.getElementById('memberList');
        let html = '';

        const activeMembers = members.filter(m => m.location_sharing);
        const offlineMembers = members.filter(m => !m.location_sharing);

        if (activeMembers.length > 0) {
            html += `<p class="fp-member-status-label">Active</p>`;
            activeMembers.forEach(m => {
                const name = m.user?.username || 'Unknown';
                const initial = name.charAt(0).toUpperCase();
                html += `
                    <div class="fp-member-item">
                        <div class="fp-active-dot"></div>
                        <div class="fp-member-avatar">${initial}</div>
                        <div>
                            <div class="fp-member-name">
                                ${name}
                                ${m.is_owner ? '<span style="font-size: 0.7rem; color: #3b82f6; font-weight: 700; margin-left: 0.25rem;">(Owner)</span>' : ''}
                            </div>
                        </div>
                        <button class="fp-location-btn" title="View location" onclick="document.getElementById('locationModal').classList.add('open')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </button>
                    </div>`;
            });
        }

        if (offlineMembers.length > 0) {
            html += `<p class="fp-member-status-label" style="margin-top:.75rem;">Offline</p>`;
            offlineMembers.forEach(m => {
                const name = m.user?.username || 'Unknown';
                const initial = name.charAt(0).toUpperCase();
                html += `
                    <div class="fp-member-item">
                        <div class="fp-offline-dot"></div>
                        <div class="fp-member-avatar fp-member-avatar--offline">${initial}</div>
                        <div>
                            <div class="fp-member-name">
                                ${name}
                                ${m.is_owner ? '<span style="font-size: 0.7rem; color: #3b82f6; font-weight: 700; margin-left: 0.25rem;">(Owner)</span>' : ''}
                            </div>
                        </div>
                    </div>`;
            });
        }
        container.innerHTML = html;
    }

    async function fetchFamilyData() {
        try {
            const response = await fetch('/api/family/details', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }); 
            if (!response.ok) throw new Error(`Server returned ${response.status}`);
            const data = await response.json();
            
            if(data.household && data.household.members) {
                renderMembers(data.household.members);
                renderRoles(data.household.members);
            } else {
                document.getElementById('roleTaskList').innerHTML = '<p class="fp-empty-roles">No data found.</p>';
                document.getElementById('memberList').innerHTML = '<p class="fp-empty-roles">No members found.</p>';
            }
        } catch (e) { 
            const err = `<p class="fp-empty-roles" style="color:#ef4444; font-weight: bold; margin:0; padding:0;">Connection Failed: ${e.message}</p>`;
            document.getElementById('roleTaskList').innerHTML = `<div style="background:#fee2e2; border-radius:.625rem; padding:.75rem; min-height:140px; display:flex; align-items:center; justify-content:center;">${err}</div>`;
            document.getElementById('memberList').innerHTML = err;
        }
    }

    function filterRoleTasks(memberId) {
        document.querySelectorAll('.fp-user-role-group').forEach(group => {
            group.style.display = (memberId === 'all' || group.dataset.member === memberId) ? 'block' : 'none';
            const header = group.querySelector('.fp-user-role-header');
            if (header) header.style.display = (memberId === 'all') ? 'block' : 'none';
        });
    }

    // Modal UI logic
    function switchTab(tab) {
        document.querySelectorAll('.fp-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.fp-tab-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        const tabs = document.querySelectorAll('.fp-tab');
        if (tab === 'waiting') tabs[0].classList.add('active');
        else tabs[1].classList.add('active');
    }

    function switchCreateJoinTab(tab) {
        document.querySelectorAll('#createJoinModal .fp-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('#createJoinModal .fp-tab-panel').forEach(p => p.classList.remove('active'));
        if (tab === 'create') {
            document.querySelectorAll('#createJoinModal .fp-tab')[0].classList.add('active');
            document.getElementById('tab-cj-create').classList.add('active');
        } else {
            document.querySelectorAll('#createJoinModal .fp-tab')[1].classList.add('active');
            document.getElementById('tab-cj-join').classList.add('active');
        }
    }

    document.querySelectorAll('.fp-modal-bg').forEach(bg => bg.addEventListener('click', e => { if (e.target === bg) bg.classList.remove('open'); }));
    
    // Check for Flash Messages on Page Load
    document.addEventListener('DOMContentLoaded', () => {
        fetchFamilyData();
        
        const storedFlash = sessionStorage.getItem('fp_flash');
        if (storedFlash) {
            showFlash(storedFlash, 'success');
            sessionStorage.removeItem('fp_flash');
        }
    });
</script>
</x-app-layout>
<x-app-layout>
<style>
    .ct-wrap { max-width: 960px; margin: 2rem auto; padding: 0 1.25rem 3rem; font-family: 'Segoe UI', system-ui, sans-serif; }
    .ct-flash { padding: .6rem 1rem; border-radius: .5rem; margin-bottom: 1rem; font-size: .85rem; }
    .ct-flash--success { background:#dcfce7; color:#166534; }
    .ct-flash--error   { background:#fee2e2; color:#991b1b; }
    .ct-panels { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    @media(max-width:640px){ .ct-panels { grid-template-columns: 1fr; } }
    .ct-card { background: #dbeafe; border-radius: 1rem; padding: 1.25rem; }
    .ct-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .ct-card-title { font-size: .9rem; font-weight: 700; color: #1e3a5f; }
    .ct-btn-pill { background: #3b82f6; color: #fff; border: none; border-radius: 999px; padding: .3rem .85rem; font-size: .75rem; cursor: pointer; font-weight: 500; }
    .ct-btn-pill:hover { background: #2563eb; }
    .ct-btn-pill--gray { background: #93c5fd; color: #1e3a5f; }
    .ct-btn-pill--gray:hover { background: #60a5fa; }
    .ct-contact-row { display: flex; align-items: center; justify-content: space-between; padding: .65rem .75rem; background: #bfdbfe; border-radius: .625rem; margin-bottom: .5rem; }
    .ct-contact-name { font-size: .85rem; font-weight: 600; color: #1e3a5f; }
    .ct-contact-number { font-size: .82rem; color: #334155; background: #fff; padding: .25rem .75rem; border-radius: .5rem; }
    .ct-contact-actions { display: flex; gap: .4rem; margin-left: .5rem; }
    .ct-icon-btn { background: none; border: none; cursor: pointer; color: #64748b; padding: .2rem; border-radius: .375rem; }
    .ct-icon-btn:hover { color: #3b82f6; }
    .ct-icon-btn--red:hover { color: #ef4444; }
    .ct-icon-btn svg { width: 14px; height: 14px; }
    .ct-empty { text-align: center; color: #93c5fd; font-size: .82rem; padding: 1.5rem 0; }
    .ct-select { border: none; background: #bfdbfe; border-radius: .5rem; padding: .3rem .6rem; font-size: .82rem; color: #1e3a5f; font-weight: 600; cursor: pointer; }
    .ct-hotline-row { display: flex; align-items: center; justify-content: space-between; padding: .65rem .75rem; background: #bfdbfe; border-radius: .625rem; margin-bottom: .5rem; }
    .ct-hotline-name { font-size: .85rem; font-weight: 600; color: #1e3a5f; }
    .ct-hotline-number { font-size: .82rem; color: #334155; background: #fff; padding: .25rem .75rem; border-radius: .5rem; }
    /* Modal */
    .ct-modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.35); z-index: 50; align-items: center; justify-content: center; }
    .ct-modal-bg.open { display: flex; }
    .ct-modal { background: #fff; border-radius: 1rem; padding: 1.5rem; width: min(420px, 90vw); box-shadow: 0 8px 32px rgba(0,0,0,.18); position: relative; }
    .ct-modal h3 { font-size: 1rem; font-weight: 700; color: #1e3a5f; margin: 0 0 1rem; }
    .ct-modal-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 1.25rem; }
    .ct-modal-close:hover { color: #475569; }
    .ct-input { width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: .5rem; padding: .45rem .75rem; font-size: .83rem; color: #334155; margin-bottom: .75rem; }
    .ct-input:focus { outline: none; border-color: #3b82f6; }
    .ct-label { font-size: .8rem; color: #64748b; display: block; margin-bottom: .3rem; }
    .ct-submit { width: 100%; background: #3b82f6; color: #fff; border: none; border-radius: .5rem; padding: .5rem; font-size: .83rem; cursor: pointer; font-weight: 500; }
    .ct-submit:hover { background: #2563eb; }
</style>

<div class="ct-wrap">

    @if(session('success'))
        <div class="ct-flash ct-flash--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="ct-flash ct-flash--error">{{ session('error') }}</div>
    @endif

    <div class="ct-panels">

        {{-- LEFT: Personal Contacts --}}
        <div class="ct-card">
            <div class="ct-card-header">
                <span class="ct-card-title">Personal Contacts</span>
                <div style="display:flex;gap:.4rem;">
                    <button class="ct-btn-pill"
                            onclick="openModal('addPersonalModal')">Add</button>
                    @if($personalContacts->count())
                        <button class="ct-btn-pill ct-btn-pill--gray"
                                onclick="openModal('editPersonalModal')">Edit</button>
                    @endif
                </div>
            </div>

            @forelse($personalContacts as $contact)
                <div class="ct-contact-row">
                    <span class="ct-contact-name">{{ $contact->name }}</span>
                    <span class="ct-contact-number">{{ $contact->contact_number }}</span>
                </div>
            @empty
                <p class="ct-empty">No personal contacts yet.</p>
            @endforelse

            {{-- Household Contacts --}}
            @if(auth()->user()->active_family_profile_id && $householdContacts->count())
                <div style="margin-top:1rem;">
                    <p style="font-size:.78rem;font-weight:600;color:#1e40af;margin-bottom:.5rem;">
                        Household Contacts
                    </p>
                    @foreach($householdContacts as $contact)
                        <div class="ct-contact-row">
                            <span class="ct-contact-name">{{ $contact->name }}</span>
                            <span class="ct-contact-number">{{ $contact->contact_number }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(auth()->user()->active_family_profile_id)
                <button class="ct-btn-pill" style="margin-top:.75rem;width:100%;"
                        onclick="openModal('addHouseholdModal')">
                    + Add Household Contact
                </button>
            @endif
        </div>

        {{-- RIGHT: Emergency Hotlines --}}
        <div class="ct-card">
            <div class="ct-card-header">
                <span class="ct-card-title">Emergency Hotlines</span>
                <form method="GET" action="{{ route('contacts.hotlines') }}" style="margin:0;">
                    <select name="location" class="ct-select" onchange="this.form.submit()">
                        <option value="">— Select Location —</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}"
                                {{ (session('selected_location') ?? $location) === $loc ? 'selected' : '' }}>
                                {{ $loc }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @php
                $displayHotlines = session('filtered_hotlines') ?? $hotlines;
            @endphp

            @forelse($displayHotlines as $hotline)
                <div class="ct-hotline-row">
                    <span class="ct-hotline-name">{{ $hotline->name }}</span>
                    <span class="ct-hotline-number">{{ $hotline->contact_number }}</span>
                </div>
            @empty
                <p class="ct-empty">
                    @if(auth()->user()->active_family_profile_id)
                        No hotlines found for your area. Try selecting a location above.
                    @else
                        Select a location to see hotlines.
                    @endif
                </p>
            @endforelse
        </div>
    </div>
</div>

{{-- Add Personal Contact Modal --}}
<div class="ct-modal-bg" id="addPersonalModal">
    <div class="ct-modal">
        <button class="ct-modal-close" onclick="closeModal('addPersonalModal')">×</button>
        <h3>Add Personal Contact</h3>
        <form method="POST" action="{{ route('contacts.personal.store') }}">
            @csrf
            <label class="ct-label">Name</label>
            <input type="text" name="name" class="ct-input" placeholder="e.g. Mother" required />
            @error('name') <span style="color:red;font-size:.75rem;">{{ $message }}</span> @enderror

            <label class="ct-label">Contact Number</label>
            <input type="text" name="contact_number" class="ct-input" placeholder="e.g. 09171234567" required />
            @error('contact_number') <span style="color:red;font-size:.75rem;">{{ $message }}</span> @enderror

            <button type="submit" class="ct-submit">Add Contact</button>
        </form>
    </div>
</div>

{{-- Add Household Contact Modal --}}
<div class="ct-modal-bg" id="addHouseholdModal">
    <div class="ct-modal">
        <button class="ct-modal-close" onclick="closeModal('addHouseholdModal')">×</button>
        <h3>Add Household Contact</h3>
        <form method="POST" action="{{ route('contacts.household.store') }}">
            @csrf
            <label class="ct-label">Name</label>
            <input type="text" name="name" class="ct-input" placeholder="e.g. Barangay Captain" required />
            @error('name') <span style="color:red;font-size:.75rem;">{{ $message }}</span> @enderror

            <label class="ct-label">Contact Number</label>
            <input type="text" name="contact_number" class="ct-input" placeholder="e.g. 09171234567" required />
            @error('contact_number') <span style="color:red;font-size:.75rem;">{{ $message }}</span> @enderror

            <button type="submit" class="ct-submit">Add Contact</button>
        </form>
    </div>
</div>

{{-- Edit Personal Contacts Modal --}}
<div class="ct-modal-bg" id="editPersonalModal">
    <div class="ct-modal" style="max-height:80vh;overflow-y:auto;">
        <button class="ct-modal-close" onclick="closeModal('editPersonalModal')">×</button>
        <h3>Edit Personal Contacts</h3>
        @foreach($personalContacts as $contact)
            <form method="POST" action="{{ route('contacts.update', $contact) }}"
                  style="background:#f8fafc;border-radius:.5rem;padding:.75rem;margin-bottom:.75rem;">
                @csrf @method('PUT')
                <input type="text" name="name" value="{{ $contact->name }}"
                       class="ct-input" required />
                <input type="text" name="contact_number" value="{{ $contact->contact_number }}"
                       class="ct-input" required />
                <div style="display:flex;gap:.5rem;">
                    <button type="submit" class="ct-submit" style="flex:1;">Save</button>
                    <form method="POST" action="{{ route('contacts.destroy', $contact) }}"
                          onsubmit="return confirm('Delete this contact?')" style="flex:1;margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit"
                                style="width:100%;background:#fee2e2;color:#ef4444;border:none;border-radius:.5rem;padding:.5rem;font-size:.83rem;cursor:pointer;">
                            Delete
                        </button>
                    </form>
                </div>
            </form>
        @endforeach
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }
    document.querySelectorAll('.ct-modal-bg').forEach(bg => {
        bg.addEventListener('click', e => {
            if (e.target === bg) bg.classList.remove('open');
        });
    });

    // Reopen modals on validation errors
    @if($errors->has('name') || $errors->has('contact_number'))
        openModal('addPersonalModal');
    @endif
</script>

</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-[#1e3a5f] leading-tight">
                {{ __('Contacts Directory') }}
            </h2>
        </div>
    </x-slot>

<style>
    .ct-wrap { max-width: 960px; margin: 2rem auto; padding: 0 1.25rem 3rem; font-family: 'Segoe UI', system-ui, sans-serif; }
    .ct-flash { padding: .6rem 1rem; border-radius: .5rem; margin-bottom: 1rem; font-size: .85rem; transition: opacity .4s; }
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
    
    .ct-empty { text-align: center; color: #93c5fd; font-size: .82rem; padding: 1.5rem 0; }
    .ct-select { border: none; background: #bfdbfe; border-radius: .5rem; padding: .3rem .6rem; font-size: .82rem; color: #1e3a5f; font-weight: 600; cursor: pointer; outline: none; }
    
    .ct-hotline-row { display: flex; align-items: center; justify-content: space-between; padding: .65rem .75rem; background: #bfdbfe; border-radius: .625rem; margin-bottom: .5rem; }
    .ct-hotline-name { font-size: .85rem; font-weight: 600; color: #1e3a5f; }
    .ct-hotline-number { font-size: .82rem; color: #334155; background: #fff; padding: .25rem .75rem; border-radius: .5rem; }

    .ct-modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.35); z-index: 50; align-items: center; justify-content: center; }
    .ct-modal-bg.open { display: flex; }
    .ct-modal { background: #fff; border-radius: 1rem; padding: 1.5rem; width: min(420px, 90vw); box-shadow: 0 8px 32px rgba(0,0,0,.18); position: relative; }
    .ct-modal h3 { font-size: 1rem; font-weight: 700; color: #1e3a5f; margin: 0 0 1rem; }
    .ct-modal-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 1.25rem; line-height: 1; }
    .ct-modal-close:hover { color: #475569; }
    
    .ct-input { width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: .5rem; padding: .45rem .75rem; font-size: .83rem; color: #334155; margin-bottom: .75rem; }
    .ct-input:focus { outline: none; border-color: #3b82f6; }
    .ct-label { font-size: .8rem; color: #64748b; display: block; margin-bottom: .3rem; }
    .ct-submit { width: 100%; background: #3b82f6; color: #fff; border: none; border-radius: .5rem; padding: .5rem; font-size: .83rem; cursor: pointer; font-weight: 500; }
    .ct-submit:hover { background: #2563eb; }
    .ct-err { color: #ef4444; font-size: .75rem; margin-top: -0.5rem; margin-bottom: 0.5rem; }
</style>

<div class="ct-wrap">
    <div id="flashBanner" class="ct-flash" style="display:none;"></div>

    <div class="ct-panels">
        {{-- LEFT: Personal & Household Contacts --}}
        <div class="ct-card">
            <div class="ct-card-header">
                <span class="ct-card-title">Personal Contacts</span>
                <button class="ct-btn-pill" onclick="openModal('addPersonalModal')">Add</button>
            </div>

            <div id="personalContactsList">
                @forelse($personalContacts as $contact)
                    <div class="ct-contact-row" id="pc-{{ $contact->id }}">
                        <span class="ct-contact-name">{{ $contact->name }}</span>
                        <div style="display:flex;align-items:center;gap:.5rem;margin-left:auto;">
                            <button class="ct-btn-pill ct-btn-pill--gray" style="flex-shrink:0;"
                                    onclick="openEditContact({{ $contact->id }}, '{{ addslashes($contact->name) }}', '{{ $contact->contact_number }}', false)">
                                Edit
                            </button>
                            <span class="ct-contact-number">{{ $contact->contact_number }}</span>
                        </div>
                    </div>
                @empty
                    <p class="ct-empty" id="pcEmpty">No personal contacts yet.</p>
                @endforelse
            </div>

            {{-- Household Contacts --}}
            @if(auth()->user()->active_family_profile_id)
            <div style="margin-top:1.5rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;">
                    <p style="font-size:.78rem;font-weight:600;color:#1e40af;margin:0;">Household Contacts</p>
                    @if(auth()->user()->activeMember()?->is_owner)
                        <button class="ct-btn-pill" onclick="openModal('addHouseholdModal')">Add</button>
                    @endif
                </div>

                <div id="householdContactsList">
                    @forelse($householdContacts as $contact)
                        <div class="ct-contact-row" id="hc-{{ $contact->id }}">
                            <span class="ct-contact-name">{{ $contact->name }}</span>
                            <div style="display:flex;align-items:center;gap:.5rem;margin-left:auto;">
                                @if(auth()->user()->activeMember()?->is_owner)
                                    <button class="ct-btn-pill ct-btn-pill--gray" style="flex-shrink:0;"
                                            onclick="openEditContact({{ $contact->id }}, '{{ addslashes($contact->name) }}', '{{ $contact->contact_number }}', true)">
                                        Edit
                                    </button>
                                @endif
                                <span class="ct-contact-number">{{ $contact->contact_number }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="ct-empty" id="hcEmpty">No household contacts yet.</p>
                    @endforelse
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Emergency Hotlines --}}
        <div class="ct-card">
            <div class="ct-card-header">
                <span class="ct-card-title">Emergency Hotlines</span>
                <select id="locationSelect" class="ct-select" onchange="filterHotlines(this.value)">
                    <option value="">— Select Location —</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc }}">{{ $loc }}</option>
                    @endforeach
                </select>
            </div>

            <div id="hotlinesList">
                <p class="ct-empty">Select a location to see hotlines.</p>
            </div>
        </div>
    </div>
</div>

{{-- MODALS --}}

{{-- Add Personal --}}
<div class="ct-modal-bg" id="addPersonalModal">
    <div class="ct-modal">
        <button class="ct-modal-close" onclick="closeModal('addPersonalModal')">×</button>
        <h3>Add Personal Contact</h3>
        <label class="ct-label">Name</label>
        <input type="text" id="addPName" class="ct-input" placeholder="e.g. Mother" />
        <div class="ct-err" id="addPNameErr"></div>
        <label class="ct-label">Contact Number</label>
        <input type="text" id="addPNumber" class="ct-input" placeholder="e.g. 09171234567" />
        <div class="ct-err" id="addPNumberErr"></div>
        <button class="ct-submit" onclick="submitAddContact('personal')">Add Contact</button>
    </div>
</div>

{{-- Add Household --}}
<div class="ct-modal-bg" id="addHouseholdModal">
    <div class="ct-modal">
        <button class="ct-modal-close" onclick="closeModal('addHouseholdModal')">×</button>
        <h3>Add Household Contact</h3>
        <label class="ct-label">Name</label>
        <input type="text" id="addHName" class="ct-input" placeholder="e.g. Barangay Captain" />
        <div class="ct-err" id="addHNameErr"></div>
        <label class="ct-label">Contact Number</label>
        <input type="text" id="addHNumber" class="ct-input" placeholder="e.g. 09171234567" />
        <div class="ct-err" id="addHNumberErr"></div>
        <button class="ct-submit" onclick="submitAddContact('household')">Add Contact</button>
    </div>
</div>

{{-- Edit Contact Modal --}}
<div class="ct-modal-bg" id="editContactModal">
    <div class="ct-modal">
        <button class="ct-modal-close" onclick="closeModal('editContactModal')">×</button>
        <h3>Edit Contact</h3>
        <input type="hidden" id="editContactId" />
        <input type="hidden" id="editContactIsHousehold" />
        <label class="ct-label">Name</label>
        <input type="text" id="editContactName" class="ct-input" />
        <div class="ct-err" id="editContactNameErr"></div>
        <label class="ct-label">Contact Number</label>
        <input type="text" id="editContactNumber" class="ct-input" />
        <div class="ct-err" id="editContactNumberErr"></div>
        <button class="ct-submit" onclick="submitUpdateContact()">Save Changes</button>
        <button type="button" onclick="submitDeleteContact()" style="width:100%;background:#fee2e2;color:#ef4444;border:none;border-radius:.5rem;padding:.5rem;font-size:.83rem;cursor:pointer;margin-top:0.5rem;">
            Delete Contact
        </button>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const IS_OWNER = @json(auth()->user()->activeMember()?->is_owner ?? false);

function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function flash(msg, type = 'success') {
    const el = document.getElementById('flashBanner');
    el.className = `ct-flash ct-flash--${type}`;
    el.textContent = msg;
    el.style.display = 'block';
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => { el.style.display = 'none'; el.style.opacity = '1'; }, 400); }, 3000);
}

async function apiFetch(url, method = 'GET', body = null) {
    const opts = { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }};
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch(url, opts);
    const data = await res.json();
    if (!res.ok) throw data;
    return data;
}

function buildContactRow(contact, isHousehold) {
    const div = document.createElement('div');
    div.className = 'ct-contact-row';
    div.id = isHousehold ? `hc-${contact.id}` : `pc-${contact.id}`;
    
    const editBtn = (!isHousehold || IS_OWNER) 
        ? `<button class="ct-btn-pill ct-btn-pill--gray" onclick="openEditContact(${contact.id}, '${contact.name.replace(/'/g,"\\'")}', '${contact.contact_number}', ${isHousehold})">Edit</button>` 
        : '';

    div.innerHTML = `
        <span class="ct-contact-name">${contact.name}</span>
        <div style="display:flex;align-items:center;gap:.5rem;margin-left:auto;">
            ${editBtn}
            <span class="ct-contact-number">${contact.contact_number}</span>
        </div>`;
    return div;
}

async function submitAddContact(type) {
    const isH = type === 'household';
    const name = document.getElementById(isH ? 'addHName' : 'addPName').value;
    const num = document.getElementById(isH ? 'addHNumber' : 'addPNumber').value;
    
    try {
        const endpoint = isH ? '/api/contacts/household' : '/api/contacts/personal';
        const data = await apiFetch(endpoint, 'POST', { name, contact_number: num });
        
        const list = document.getElementById(isH ? 'householdContactsList' : 'personalContactsList');
        const empty = document.getElementById(isH ? 'hcEmpty' : 'pcEmpty');
        if (empty) empty.remove();
        
        list.appendChild(buildContactRow(data.contact, isH));
        closeModal(isH ? 'addHouseholdModal' : 'addPersonalModal');
        flash(data.message);
    } catch (err) {
        alert(err.message || "Failed to add contact");
    }
}

function openEditContact(id, name, number, isHousehold) {
    document.getElementById('editContactId').value = id;
    document.getElementById('editContactIsHousehold').value = isHousehold ? '1' : '0';
    document.getElementById('editContactName').value = name;
    document.getElementById('editContactNumber').value = number;
    openModal('editContactModal');
}

async function submitUpdateContact() {
    const id = document.getElementById('editContactId').value;
    const isH = document.getElementById('editContactIsHousehold').value === '1';
    const name = document.getElementById('editContactName').value;
    const number = document.getElementById('editContactNumber').value;

    try {
        const data = await apiFetch(`/api/contacts/${id}`, 'PUT', { name, contact_number: number });
        const row = document.getElementById(isH ? `hc-${id}` : `pc-${id}`);
        if (row) row.replaceWith(buildContactRow(data.contact, isH));
        closeModal('editContactModal');
        flash(data.message);
    } catch (err) { alert(err.message); }
}

async function submitDeleteContact() {
    if (!confirm('Delete this contact?')) return;
    const id = document.getElementById('editContactId').value;
    const isH = document.getElementById('editContactIsHousehold').value === '1';

    try {
        const data = await apiFetch(`/api/contacts/${id}`, 'DELETE');
        document.getElementById(isH ? `hc-${id}` : `pc-${id}`).remove();
        closeModal('editContactModal');
        flash(data.message);
    } catch (err) { alert(err.message); }
}

async function filterHotlines(location) {
    const list = document.getElementById('hotlinesList');
    list.innerHTML = '<p class="ct-empty">Loading...</p>';
    try {
        const data = await apiFetch(`/api/hotlines?location=${encodeURIComponent(location)}`);
        if (!data.hotlines.length) {
            list.innerHTML = '<p class="ct-empty">No hotlines found.</p>';
            return;
        }
        list.innerHTML = data.hotlines.map(h => `
            <div class="ct-hotline-row">
                <span class="ct-hotline-name">${h.name}</span>
                <span class="ct-hotline-number">${h.contact_number}</span>
            </div>`).join('');
    } catch (err) { list.innerHTML = '<p class="ct-empty">Error loading hotlines.</p>'; }
}

document.querySelectorAll('.ct-modal-bg').forEach(bg => bg.addEventListener('click', e => { if (e.target === bg) bg.classList.remove('open'); }));
</script>
</x-app-layout>
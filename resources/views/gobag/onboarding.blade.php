<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-[#1e3a5f] leading-tight">
                {{ __('Household Setup') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-lg mx-auto py-8 px-4">

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Welcome to SafeGrid</h1>
        <p class="text-gray-500 text-sm mb-8">You are not part of a household yet. Create one or join an existing one.</p>

        <div id="flashMessage" class="hidden px-4 py-2 rounded mb-4 text-sm font-medium"></div>

        {{-- Pending Invitations --}}
        @if($invitations->count())
            <div class="bg-white border rounded-xl p-6 mb-6 shadow-sm" id="invitationsContainer">
                <h2 class="font-semibold text-gray-700 mb-4">Pending Invitations</h2>
                @foreach($invitations as $invitation)
                    <div class="flex items-center justify-between py-2 border-b last:border-0 invitation-row" id="invite-{{ $invitation->id }}">
                        <span class="text-sm text-gray-700">{{ $invitation->familyProfile->household_name }}</span>
                        <div class="flex gap-2">
                            <button type="button" onclick="handleInvitation({{ $invitation->id }}, 'accept')" class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors">Accept</button>
                            <button type="button" onclick="handleInvitation({{ $invitation->id }}, 'decline')" class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded hover:bg-gray-300 transition-colors">Decline</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Create Household --}}
        <div class="bg-white border rounded-xl p-6 mb-6 shadow-sm">
            <h2 class="font-semibold text-gray-700 mb-4">Create a Household</h2>
            <form id="createHouseholdForm" onsubmit="submitOnboardingForm(event, '/api/family/create', 'createBtn')">
                <div class="mb-3">
                    <label class="text-sm text-gray-600">Household Name</label>
                    <input type="text" name="household_name" 
                           class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                           placeholder="e.g. Dela Cruz Family" required />
                    <span id="err-household_name" class="text-red-500 text-xs mt-1 block error-msg"></span>
                </div>
                
                <div class="mb-3">
                    <label class="text-sm text-gray-600">Home Address</label>
                    <input type="text" name="address" 
                           class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                           placeholder="e.g. Tacloban City, Leyte" />
                    <span id="err-address" class="text-red-500 text-xs mt-1 block error-msg"></span>
                </div>
                
                <div class="mb-4">
                    <label class="text-sm text-gray-600 block mb-2">Disaster Risks in your area</label>
                    <div class="flex flex-wrap gap-x-4 gap-y-2">
                        @foreach(['Typhoon', 'Flood', 'Earthquake', 'Landslide', 'Volcanic Eruption', 'Fire'] as $risk)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="disaster_risks[]" value="{{ $risk }}" class="rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                                <span class="text-sm text-gray-600 ml-1">{{ $risk }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <button type="submit" id="createBtn" class="w-full bg-blue-500 text-white font-bold py-2 rounded text-sm hover:bg-blue-600 transition-colors">
                    Create Household
                </button>
            </form>
        </div>

        {{-- Join Household --}}
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-gray-700 mb-4">Join a Household</h2>
            <form id="joinHouseholdForm" onsubmit="submitOnboardingForm(event, '/api/family/join', 'joinBtn')">
                <div class="mb-3">
                    <label class="text-sm text-gray-600 mb-1 block">Household Code</label>
                    <input type="text" name="household_code" 
                           class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none tracking-widest font-mono uppercase"
                           placeholder="e.g. AB12CD" maxlength="6" required />
                    <span id="err-household_code" class="text-red-500 text-xs mt-1 block error-msg"></span>
                </div>
                <button type="submit" id="joinBtn" class="w-full bg-blue-500 text-white font-bold py-2 rounded text-sm hover:bg-blue-600 transition-colors">
                    Join Household
                </button>
            </form>
        </div>

    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

        function showFlash(message, isError = false) {
            const flash = document.getElementById('flashMessage');
            flash.textContent = message;
            flash.className = `px-4 py-3 rounded mb-6 text-sm font-medium shadow-sm block ${isError ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200'}`;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ─────────────────────────────────────────────────────────────────
        // AJAX: Accept/Decline Invitations
        // ─────────────────────────────────────────────────────────────────
        async function handleInvitation(id, action) {
            try {
                // Note: Make sure your api.php has a decline route implemented, currently I only see 'accept' and 'cancel' in your api.php file!
                const endpoint = action === 'accept' 
                    ? `/api/family/invitation/${id}/accept` 
                    : `/api/family/invitation/${id}/decline`; 

                const res = await fetch(endpoint, {
                    method: action === 'accept' ? 'POST' : 'DELETE', // Adjust method based on your API setup
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await res.json();
                
                if (!res.ok) throw data;

                if (action === 'accept') {
                    window.location.href = "{{ route('family.index') ?? '/family' }}";
                } else {
                    document.getElementById(`invite-${id}`).remove();
                    showFlash('Invitation declined successfully.');
                    
                    if (!document.querySelectorAll('.invitation-row').length) {
                        document.getElementById('invitationsContainer').style.display = 'none';
                    }
                }
            } catch (err) {
                showFlash(err.message || `Failed to ${action} invitation.`, true);
            }
        }

        // ─────────────────────────────────────────────────────────────────
        // AJAX: Create or Join Form Submissions
        // ─────────────────────────────────────────────────────────────────
        async function submitOnboardingForm(e, endpoint, btnId) {
            e.preventDefault();
            const form = e.target;
            const btn = document.getElementById(btnId);
            const originalText = btn.innerText;

            form.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
            
            btn.disabled = true;
            btn.innerText = 'Processing...';
            btn.classList.add('opacity-75');

            const formData = new FormData(form);
            const payload = {};
            formData.forEach((value, key) => {
                if (key.endsWith('[]')) {
                    const cleanKey = key.slice(0, -2);
                    if (!payload[cleanKey]) payload[cleanKey] = [];
                    payload[cleanKey].push(value);
                } else {
                    payload[key] = value;
                }
            });

            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (!res.ok) {
                    if (res.status === 422 && data.errors) {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const errEl = form.querySelector(`#err-${field}`);
                            if (errEl) errEl.textContent = messages[0];
                        }
                    } else {
                        showFlash(data.message || 'An error occurred.', true);
                    }
                } else {
                    showFlash(data.message || 'Success! Redirecting...');
                    setTimeout(() => {
                        window.location.href = "{{ route('family.index') ?? '/family' }}";
                    }, 800);
                }
            } catch (err) {
                console.error(err);
                showFlash('Network error. Please try again.', true);
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
                btn.classList.remove('opacity-75');
            }
        }
    </script>
</x-app-layout>
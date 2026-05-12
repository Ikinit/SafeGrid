<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-[#1e3a5f] leading-tight">
                {{ __('Household Setup') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold text-gray-800 mb-1">Welcome to SafeGrid</h1>
        <p class="text-gray-500 text-sm mb-6">You are not part of a household yet. Create one or join an existing one.</p>

        {{-- Dynamic Flash Message Container --}}
        <div id="flashMessage" class="hidden px-4 py-3 rounded-lg mb-6 text-sm font-medium shadow-sm"></div>

        {{-- Pending Invitations --}}
        @if($invitations->count())
            <div class="bg-white border rounded-xl p-6 mb-6 shadow-sm" id="invitationsContainer">
                <h2 class="font-semibold text-gray-700 mb-4">Pending Invitations</h2>
                @foreach($invitations as $invitation)
                    <div class="flex items-center justify-between py-2 border-b last:border-0 invitation-row" id="invite-{{ $invitation->id }}">
                        <span class="text-sm font-medium text-gray-700">{{ $invitation->familyProfile->household_name }}</span>
                        <div class="flex gap-2">
                            <button type="button" 
                                    onclick="handleNavInvitation({{ $invitation->id }}, 'accept')" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1.5 px-4 rounded text-sm transition">
                                Accept
                            </button>
                            
                            <button type="button" 
                                    onclick="handleNavInvitation({{ $invitation->id }}, 'decline')" 
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1.5 px-4 rounded border border-gray-300 text-sm transition">
                                Decline
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pending Join Requests --}}
        @if($pendingRequests->count())
            <div class="bg-white border border-yellow-200 rounded-xl p-6 mb-6 shadow-sm" id="requestsContainer">
                <h2 class="font-semibold text-gray-700 mb-2">Pending Join Requests</h2>
                <p class="text-xs text-gray-500 mb-4">You have sent join requests to these households. Waiting for owner approval...</p>
                @foreach($pendingRequests as $request)
                    <div class="flex items-center justify-between py-2 border-b last:border-0 request-row" id="req-{{ $request->id }}">
                        <span class="text-sm font-medium text-gray-700">{{ $request->familyProfile->household_name }}</span>
                        <button type="button" onclick="handleApiAction('/api/family/invitation/{{ $request->id }}/cancel', 'DELETE', 'req-{{ $request->id }}')" class="text-xs font-bold bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-md hover:bg-red-100 transition-colors">Cancel Request</button>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Side by Side: Create + Join --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Create Household --}}
            <div class="bg-white border rounded-xl p-6 shadow-sm">
                <h2 class="font-semibold text-gray-700 mb-4">Create a Household</h2>
                <form id="createHouseholdForm" onsubmit="submitOnboardingForm(event, '/api/family/create', 'createBtn')">
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-600">Household Name</label>
                        <input type="text" name="household_name" 
                               class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm mt-1 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g. Dela Cruz Family" required />
                        <span id="err-household_name" class="text-red-500 text-xs font-medium mt-1 block error-msg"></span>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-600">Home Address</label>
                        <input type="text" name="address" 
                               class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm mt-1 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g. Tacloban City, Leyte" />
                        <span id="err-address" class="text-red-500 text-xs font-medium mt-1 block error-msg"></span>
                    </div>
                    
                    <div class="mb-6">
                        <label class="text-sm font-semibold text-gray-600 block mb-3">Disaster Risks in your area</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['Typhoon', 'Flood', 'Earthquake', 'Landslide', 'Volcanic Eruption', 'Fire'] as $risk)
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="disaster_risks[]" value="{{ $risk }}"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-600">{{ $risk }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <button type="submit" id="createBtn" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                        Create Household
                    </button>
                </form>
            </div>

            {{-- Join Household --}}
            <div class="bg-white border rounded-xl p-6 shadow-sm flex flex-col">
                <h2 class="font-semibold text-gray-700 mb-2">Join a Household</h2>
                <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                    Ask your household owner for the 6-character household code and enter it below to join their household.
                </p>
                
                <form id="joinHouseholdForm" class="flex flex-col flex-1" onsubmit="submitOnboardingForm(event, '/api/family/join', 'joinBtn')">
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-600">Household Code</label>
                        <input type="text" name="household_code" 
                               class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm mt-1 focus:border-blue-500 focus:ring-blue-500 tracking-widest font-mono uppercase text-center"
                               placeholder="AB12CD" maxlength="6" required />
                        <span id="err-household_code" class="text-red-500 text-xs font-medium mt-1 block error-msg"></span>
                    </div>
                    
                    <div class="mt-auto pt-4">
                        <button type="submit" id="joinBtn" class="w-full bg-gray-800 text-white font-bold py-2.5 rounded-lg text-sm hover:bg-gray-900 transition-colors">
                            Join Household
                        </button>
                    </div>
                </form>

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-8">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">or wait for an invitation</span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500">
                        Ask a household owner to invite you by your username:<br>
                        <span class="font-bold text-blue-600 text-sm block mt-1">{{ auth()->user()->username ?? 'Your Username' }}</span>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

        function showFlash(message, isError = false) {
            const flash = document.getElementById('flashMessage');
            flash.textContent = message;
            flash.className = `px-4 py-3 rounded-lg mb-6 text-sm font-medium shadow-sm block ${isError ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200'}`;
            flash.style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Handles Accept/Decline/Cancel actions for Invitations and Join Requests
        async function handleApiAction(endpoint, method, rowId, redirectOnSuccess = false) {
            if (method === 'DELETE' && !confirm('Are you sure you want to perform this action?')) return;

            try {
                const res = await fetch(endpoint, {
                    method: method,
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await res.json();
                
                if (!res.ok) throw data;

                if (redirectOnSuccess) {
                    window.location.href = "{{ route('family.index') ?? '/family' }}";
                } else {
                    document.getElementById(rowId).remove();
                    showFlash(data.message || 'Action completed successfully.');
                    
                    // Cleanup empty containers visually
                    if (!document.querySelectorAll('.invitation-row').length && document.getElementById('invitationsContainer')) {
                        document.getElementById('invitationsContainer').style.display = 'none';
                    }
                    if (!document.querySelectorAll('.request-row').length && document.getElementById('requestsContainer')) {
                        document.getElementById('requestsContainer').style.display = 'none';
                    }
                }
            } catch (err) {
                showFlash(err.message || 'Failed to complete action.', true);
            }
        }

        // Handles Create Household and Join Household forms
        async function submitOnboardingForm(e, endpoint, btnId) {
            e.preventDefault();
            const form = e.target;
            const btn = document.getElementById(btnId);
            const originalText = btn.innerText;

            // Clear old error messages
            form.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
            
            btn.disabled = true;
            btn.innerText = 'Processing...';
            btn.classList.add('opacity-75');

            // Parse Form Data to JSON (handling checkbox arrays)
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
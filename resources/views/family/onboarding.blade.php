<x-app-layout>
    <div class="max-w-4xl mx-auto mt-16 px-4 pb-12">

        <h1 class="text-2xl font-bold text-gray-800 mb-1">Welcome to SafeGrid</h1>
        <p class="text-gray-500 text-sm mb-6">You are not part of a household yet. Create one or join an existing one.</p>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">{{ session('success') }}</div>
        @endif

        {{-- Pending Invitations --}}
        @if($invitations->count())
            <div class="bg-white border rounded-xl p-6 mb-6 shadow-sm">
                <h2 class="font-semibold text-gray-700 mb-4">Pending Invitations</h2>
                @foreach($invitations as $invitation)
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <span class="text-sm text-gray-700">{{ $invitation->familyProfile->household_name }}</span>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('family.invitation.accept', $invitation) }}">
                                @csrf
                                <button class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Accept</button>
                            </form>
                            <form method="POST" action="{{ route('family.invitation.decline', $invitation) }}">
                                @csrf
                                <button class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded hover:bg-gray-300">Decline</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pending Join Requests --}}
        @if($pendingRequests->count())
            <div class="bg-white border border-yellow-200 rounded-xl p-6 mb-6 shadow-sm">
                <h2 class="font-semibold text-gray-700 mb-4">Pending Join Requests</h2>
                <p class="text-xs text-gray-500 mb-4">You have sent join requests to these households. Waiting for owner approval...</p>
                @foreach($pendingRequests as $request)
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <span class="text-sm text-gray-700">{{ $request->familyProfile->household_name }}</span>
                        <form method="POST" action="{{ route('family.invitation.cancel', $request) }}" onsubmit="return confirm('Cancel this join request?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded hover:bg-gray-300">Cancel Request</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Side by Side: Create + Join --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Create Household --}}
            <div class="bg-white border rounded-xl p-6 shadow-sm">
                <h2 class="font-semibold text-gray-700 mb-4">Create a Household</h2>
                <form method="POST" action="{{ route('onboarding.create') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="text-sm text-gray-600">Household Name</label>
                        <input type="text" name="household_name" value="{{ old('household_name') }}"
                               class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-2 focus:ring-blue-400"
                               placeholder="e.g. Dela Cruz Family" required />
                        @error('household_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="text-sm text-gray-600">Home Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-2 focus:ring-blue-400"
                               placeholder="e.g. Tacloban City, Leyte" />
                    </div>
                    <div class="mb-4">
                        <label class="text-sm text-gray-600 block mb-2">Disaster Risks in your area</label>
                        <div class="grid grid-cols-2 gap-1">
                            @foreach(['Typhoon', 'Flood', 'Earthquake', 'Landslide', 'Volcanic Eruption', 'Fire'] as $risk)
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="disaster_risks[]" value="{{ $risk }}"
                                           class="rounded text-blue-500"
                                           {{ in_array($risk, old('disaster_risks', [])) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-600">{{ $risk }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded text-sm hover:bg-blue-600 mt-2">
                        Create Household
                    </button>
                </form>
            </div>

            {{-- Join Household --}}
            <div class="bg-white border rounded-xl p-6 shadow-sm flex flex-col">
                <h2 class="font-semibold text-gray-700 mb-4">Join a Household</h2>
                <p class="text-xs text-gray-400 mb-6">
                    Ask your household owner for the 6-character household code and enter it below to join their household.
                </p>
                <form method="POST" action="{{ route('onboarding.join') }}" class="flex flex-col flex-1">
                    @csrf
                    <div class="mb-4">
                        <label class="text-sm text-gray-600">Household Code</label>
                        <input type="text" name="household_code" value="{{ old('household_code') }}"
                               class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-2 focus:ring-blue-400 tracking-widest font-mono uppercase"
                               placeholder="e.g. AB12CD" maxlength="6" required />
                        @error('household_code')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-auto">
                        <button type="submit" class="w-full bg-gray-700 text-white py-2 rounded text-sm hover:bg-gray-800">
                            Join Household
                        </button>
                    </div>
                </form>

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="text-xs text-gray-400">or wait for an invitation</span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                <p class="text-xs text-gray-400 text-center">
                    Ask a household owner to invite you by your username
                    <span class="font-medium text-blue-500">{{ auth()->user()->username }}</span>
                </p>
            </div>

        </div>
    </div>
</x-app-layout>
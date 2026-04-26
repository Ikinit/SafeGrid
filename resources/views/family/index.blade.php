<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 px-4">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">{{ session('error') }}</div>
        @endif

        {{-- Household Info --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h1 class="text-xl font-bold text-gray-800">{{ $profile->household_name }}</h1>
            <p class="text-sm text-gray-500">{{ $profile->address ?? 'No address set' }}</p>
            <p class="text-sm mt-2">Household Code: <span class="font-mono font-bold text-blue-600">{{ $profile->household_code }}</span></p>

            {{-- Edit / Delete — only for owner --}}
            @if(auth()->user()->activeMember()?->is_owner)
                <div class="mt-4 border-t pt-4">

                    {{-- Edit Form --}}
                    <details class="mb-3">
                        <summary class="text-sm text-blue-600 cursor-pointer hover:underline">Edit Household</summary>
                        <form method="POST" action="{{ route('family.update') }}" class="mt-3">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="text-sm text-gray-600">Household Name</label>
                                <input type="text" name="household_name"
                                       value="{{ old('household_name', $profile->household_name) }}"
                                       class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-2 focus:ring-blue-400"
                                       required />
                                @error('household_name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="text-sm text-gray-600">Address</label>
                                <input type="text" name="address"
                                       value="{{ old('address', $profile->address) }}"
                                       class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-2 focus:ring-blue-400"
                                       placeholder="e.g. Tacloban City, Leyte" />
                            </div>
                            <div class="mb-3">
                                <label class="text-sm text-gray-600 block mb-2">Disaster Risks</label>
                                @foreach(['Typhoon', 'Flood', 'Earthquake', 'Landslide', 'Volcanic Eruption', 'Fire'] as $risk)
                                    <label class="inline-flex items-center mr-4 mb-2">
                                        <input type="checkbox" name="disaster_risks[]" value="{{ $risk }}"
                                               class="rounded text-blue-500"
                                               {{ in_array($risk, old('disaster_risks', $profile->disaster_risks ?? [])) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-600 ml-1">{{ $risk }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                                Save Changes
                            </button>
                        </form>
                    </details>

                    {{-- Delete Form --}}
                    <form method="POST" action="{{ route('family.delete') }}"
                          onsubmit="return confirm('Are you sure you want to delete this household? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">
                            Delete Household
                        </button>
                    </form>

                </div>
            @endif
        </div>

        {{-- Members --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-gray-700 mb-3">Members</h2>
            @foreach($profile->members as $member)
                <div class="py-2 border-b last:border-0">
                    <p class="text-sm text-gray-800">{{ $member->user->username }}</p>
                    <p class="text-xs text-gray-500">{{ $member->role ?? 'No role' }}</p>
                </div>
            @endforeach
        </div>

        {{-- Invite --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-gray-700 mb-3">Invite a Member</h2>
            <form method="POST" action="{{ route('family.invite') }}">
                @csrf
                <input type="text" name="username" placeholder="Enter username"
                       class="w-full border rounded px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-400" />
                @error('username')
                    <span class="text-red-500 text-xs block mb-2">{{ $message }}</span>
                @enderror
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded text-sm hover:bg-blue-600">
                    Send Invite
                </button>
            </form>
        </div>

        {{-- Create Another Household --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-gray-700 mb-3">Create Another Household</h2>
            <form method="POST" action="{{ route('onboarding.create') }}">
                @csrf
                <input type="text" name="household_name" placeholder="Household name"
                       class="w-full border rounded px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-400" required />
                @error('household_name')
                    <span class="text-red-500 text-xs block mb-2">{{ $message }}</span>
                @enderror
                <input type="text" name="address" placeholder="Address (optional)"
                       class="w-full border rounded px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-400" />
                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded text-sm hover:bg-green-600">
                    Create Household
                </button>
            </form>
        </div>

        {{-- Join Another Household --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-3">Join Another Household</h2>
            <form method="POST" action="{{ route('onboarding.join') }}">
                @csrf
                <input type="text" name="household_code" placeholder="Enter household code"
                       class="w-full border rounded px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-400"
                       maxlength="6" required />
                @error('household_code')
                    <span class="text-red-500 text-xs block mb-2">{{ $message }}</span>
                @enderror
                <button type="submit" class="w-full bg-gray-700 text-white py-2 rounded text-sm hover:bg-gray-800">
                    Join Household
                </button>
            </form>
        </div>

    </div>
</x-app-layout>
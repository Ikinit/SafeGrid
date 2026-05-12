<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // Update Profile Info
        async function submitProfileUpdate(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            
            btn.disabled = true;
            btn.innerText = 'Saving...';

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('/api/user/profile', {
                    method: 'PATCH',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (res.ok) {
                    const savedMsg = document.getElementById('profileSavedMsg');
                    if (savedMsg) {
                        savedMsg.style.display = 'block';
                        setTimeout(() => savedMsg.style.display = 'none', 2000);
                    }
                } else {
                    const errData = await res.json();
                    alert(errData.message || 'Failed to update profile.');
                }
            } catch (err) {
                console.error(err);
                alert('A network error occurred.');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }

        // Delete Account
        async function submitProfileDelete(e) {
            e.preventDefault();
            const form = e.target;
            const passwordInput = form.querySelector('input[name="password"]').value;
            const errorDiv = document.getElementById('deletePasswordError');
            
            if(errorDiv) errorDiv.innerText = ''; // Clear old errors

            try {
                const res = await fetch('/api/user/profile', {
                    method: 'DELETE',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password: passwordInput })
                });

                if (res.ok) {
                    window.location.href = '/'; 
                } else {
                    const errData = await res.json();
                    if(errorDiv) {
                        errorDiv.innerText = errData.message || Object.values(errData.errors || {})[0][0] || 'Incorrect password.';
                    } else {
                        alert('Incorrect password');
                    }
                }
            } catch (err) {
                console.error(err);
                if(errorDiv) errorDiv.innerText = 'A network error occurred. Please try again.';
            }
        }
    </script>
</x-app-layout>
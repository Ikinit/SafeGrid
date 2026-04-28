<x-guest-layout>
    <div class="relative min-h-screen w-full min-w-[670px] flex items-center justify-center overflow-hidden bg-white">
        
        <img rel="preload" src="{{ asset('Vector.png') }}" alt="" class="absolute left-0 top-0 h-screen object-contain z-0" />
        
        <div class="absolute top-8 right-12 flex space-x-6">
            <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Guest</a>
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Login</a>
        </div>

        <div class="w-full max-w-7xl flex flex-col min-[1250px]:flex-row items-center justify-center gap-12 min-[1250px]:gap-32 px-6 lg:px-12">
            
            <div class="max-w-lg mb-12 min-[1250px]:mb-0 z-10 flex flex-col items-start w-full">
                
                <div class="w-full flex justify-center mb-8">
                    <img src="{{ asset('logo.png') }}" alt="SafeGrid Logo" class="h-20 md:h-24 w-auto object-contain drop-shadow-sm" />
                </div>

                <h1 class="text-6xl lg:text-7xl font-extrabold text-[#1e3a5f] mb-6 tracking-tight leading-tight text-left">
                    Welcome to <br class="hidden lg:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#3b82f6] to-[#7BF0FF]">SafeGrid!</span>
                </h1>
            
                <p class="text-sm md:text-base text-gray-600 font-medium leading-relaxed w-full text-justify">
                    A comprehensive disaster preparedness platform designed to help Filipino families proactively organize, manage, and secure their emergency plans. Build your go-bags, assign roles, and stay connected so you are always ready when it matters most.
                </p>
            </div>

            <div class="bg-white rounded-[1rem] p-8 w-full max-w-sm shadow-[0_2px_10px_rgba(59,130,246,0.15)] border border-blue-50">
                <h2 class="text-center text-3xl font-bold mb-6 text-[#1e3a5f]" style="font-family: 'Segoe UI', system-ui, sans-serif;">Register</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="username" :value="__('Enter Username:')" class="text-[#64748b] text-sm font-medium" />
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Choose a username"
                               class="w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-lg mt-1 px-3 py-2 text-sm text-[#334155] focus:ring-[3px] focus:ring-blue-500/15 focus:border-blue-500 outline-none transition-all box-border" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Enter Email:')" class="text-[#64748b] text-sm font-medium" />
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@example.com"
                               class="w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-lg mt-1 px-3 py-2 text-sm text-[#334155] focus:ring-[3px] focus:ring-blue-500/15 focus:border-blue-500 outline-none transition-all box-border" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Enter Password:')" class="text-[#64748b] text-sm font-medium" />
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Create a password"
                               class="w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-lg mt-1 px-3 py-2 text-sm text-[#334155] focus:ring-[3px] focus:ring-blue-500/15 focus:border-blue-500 outline-none transition-all box-border" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password:')" class="text-[#64748b] text-sm font-medium" />
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password"
                               class="w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-lg mt-1 px-3 py-2 text-sm text-[#334155] focus:ring-[3px] focus:ring-blue-500/15 focus:border-blue-500 outline-none transition-all box-border" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-[#7BF0FF] to-[#7E98FF] text-white font-bold text-sm py-3 rounded-full shadow-md hover:opacity-90 tracking-wide transition-opacity mt-6">
                        SIGN UP
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
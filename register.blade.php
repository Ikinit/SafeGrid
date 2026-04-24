<x-guest-layout>
    <div class="relative min-h-screen w-full min-w-[670px] flex items-center justify-center overflow-hidden bg-white">
        
        <img rel="preload" src="{{ asset('Vector.png') }}" alt="" class="absolute left-0 top-0 h-screen h-full object-contain z-0" />
        
        <div class="absolute top-8 right-12 flex space-x-6">
            <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-900">Guest</a>
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Login</a>
        </div>

        <div class="w-full max-w-5xl flex flex-col min-[1250px]:flex-row items-center justify-between px-6 lg:px-12">
            
            <div class="max-w-md mb-12 min-[1250px]:mb-0 ml-0 min-[1250px]:ml-12 z-10">
                <h1 class="text-7xl font-extrabold text-black mb-6 tracking-tight">SafeGrid</h1>
                <p class="text-sm text-black font-bold leading-normal">
                    - a disaster preparedness<br>
                    website, designed to help<br>
                    Filipino families organize and<br>
                    manage their disaster<br>
                    preparedness plans.
                </p>
            </div>

            <div class="bg-[#FAF3E0] rounded-2xl p-8 w-full max-w-sm shadow-sm">
                <h2 class="text-center text-4xl mb-6 text-black" style="font-family: 'Poppins', cursive;">Register</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="username" :value="__('Enter Username:')" />
                        <input id="username" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="Type here"
                               class="w-full bg-white border-none rounded mt-1 px-3 py-1.5 text-xs text-gray-900 focus:ring-2 focus:ring-blue-500 shadow-sm" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Enter Email:')" />
                        <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Type here"
                               class="w-full bg-white border-none rounded mt-1 px-3 py-1.5 text-xs text-gray-900 focus:ring-2 focus:ring-blue-500 shadow-sm" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Enter Password:')" />
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Type here"
                               class="w-full bg-white border-none rounded mt-1 px-3 py-1.5 text-xs text-gray-900 focus:ring-2 focus:ring-blue-500 shadow-sm" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password:')" />
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Type here"
                               class="w-full bg-white border-none rounded mt-1 px-3 py-1.5 text-xs text-gray-900 focus:ring-2 focus:ring-blue-500 shadow-sm" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-[#7BF0FF] to-[#7E98FF] text-white font-bold text-xs py-3 rounded-full shadow-md hover:opacity-90 tracking-wide">
                        SIGN UP
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

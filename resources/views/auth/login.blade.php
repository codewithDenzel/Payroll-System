<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black uppercase italic tracking-tighter">Welcome <span class="text-indigo-600">User!</span></h2>
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Please login your account</p>
    </div>

    <x-auth-session-status class="mb-4 font-bold text-emerald-600 uppercase text-xs" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label class="block text-xs font-black uppercase mb-1 tracking-widest">Email Address</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                class="w-full border-4 border-black p-3 bg-slate-50 focus:bg-white focus:ring-0 focus:border-indigo-600 font-bold transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] focus:shadow-none">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <label class="block text-xs font-black uppercase mb-1 tracking-widest">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full border-4 border-black p-3 bg-slate-50 focus:bg-white focus:ring-0 focus:border-indigo-600 font-bold transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] focus:shadow-none">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        

        <div class="mt-8 space-y-4">
            <button class="w-full bg-indigo-600 text-white border-4 border-black py-4 font-black uppercase italic text-lg shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                {{ __('Login') }}
            </button>


            <div class="mt-8 text-center border-t border-gray-200 pt-6">
    <p class="text-sm text-gray-600 mb-4">Are you an employee checking your records?</p>
    <a href="{{ route('spectator.list') }}" style="background-color: #0dcaf0; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; display: block; text-align: center;">
        👔 Enter Employee Mode
    </a>
</div>



            <div class="flex items-center justify-between pt-4 border-t-2 border-slate-100">
                @if (Route::has('password.request'))
                    <a class="text-xs font-black uppercase tracking-tighter text-slate-400 hover:text-black underline transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif
                
                <a class="text-xs font-black uppercase tracking-tighter text-indigo-600 hover:text-black transition-colors" href="{{ route('register') }}">
                    {{ __('Create Account') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
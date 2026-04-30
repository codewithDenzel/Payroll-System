<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black uppercase italic tracking-tighter">New <span class="text-indigo-600">Account</span></h2>
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Join the payroll network</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label class="block text-xs font-black uppercase mb-1 tracking-widest">Full Name</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus 
                class="w-full border-4 border-black p-3 bg-slate-50 focus:bg-white focus:ring-0 focus:border-indigo-600 font-bold transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] focus:shadow-none">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label class="block text-xs font-black uppercase mb-1 tracking-widest">Email Address</label>
            <input id="email" type="email" name="email" :value="old('email')" required
                class="w-full border-4 border-black p-3 bg-slate-50 focus:bg-white focus:ring-0 focus:border-indigo-600 font-bold transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] focus:shadow-none">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        

        <div class="mt-4">
            <label class="block text-xs font-black uppercase mb-1 tracking-widest">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full border-4 border-black p-3 bg-slate-50 focus:bg-white focus:ring-0 focus:border-indigo-600 font-bold transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] focus:shadow-none">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label class="block text-xs font-black uppercase mb-1 tracking-widest">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full border-4 border-black p-3 bg-slate-50 focus:bg-white focus:ring-0 focus:border-indigo-600 font-bold transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] focus:shadow-none">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-8 space-y-4">
            <button class="w-full bg-black text-white border-4 border-black py-4 font-black uppercase italic text-lg shadow-[6px_6px_0px_0px_rgba(79,70,229,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                {{ __('Complete Registration') }}
            </button>

            <div class="text-center pt-4 border-t-2 border-slate-100">
                <a class="text-xs font-black uppercase tracking-tighter text-slate-400 hover:text-indigo-600 transition-colors" href="{{ route('login') }}">
                    {{ __('Already have an account? Log in') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
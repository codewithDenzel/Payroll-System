<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <img src="{{ asset('images/Payroll logo.png') }}" alt="Logo" 
                        class="h-24 w-auto border-4 border-black shadow-[6px_6px_0px_0px_rgba(79,70,229,1)] bg-white p-2 transition-transform hover:-rotate-3">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-10 px-8 py-10 bg-white border-4 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
                {{ $slot }}
            </div>
            
            <p class="mt-6 text-xs font-black uppercase tracking-widest text-slate-400 italic">Payroll Management System v1.0</p>
        </div>
    </body>
</html>
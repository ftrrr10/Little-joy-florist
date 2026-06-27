@extends('layouts.app')

@section('body')
<div class="flex min-h-screen flex-col items-center justify-center bg-brandBackground px-4 py-12 font-sans antialiased">
    {{-- Logo Brand Mark --}}
    <div class="mb-8 transform hover:scale-105 transition-transform duration-200">
        <a href="{{ route('home') }}">
            <x-app-logo variant="dark" className="h-14 w-auto" />
        </a>
    </div>

    {{-- Premium Form Card --}}
    <div class="w-full sm:max-w-md bg-white border border-brandOutline-soft/30 rounded-2xl shadow-xl shadow-primary/5 px-8 py-10 transition-all duration-200">
        @yield('content')
    </div>
</div>
@endsection

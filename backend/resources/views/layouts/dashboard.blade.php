@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        <!-- Header -->
        @include('partials.header')

        <!-- Dashboard Content -->
        <div class="p-8">
            @yield('dashboard-content')
        </div>
    </main>
</div>
@endsection

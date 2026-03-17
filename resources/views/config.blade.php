@extends('layouts.app')
@section('page-content')
    <div class="mb-6">
        <h1 class="text-slate-800">Configuration</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Dashboard</a>
            <div>/</div>
            <div>Config</div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <a href="{{ route('users.index') }}" class="pallet">
            <div class="flex items-center space-x-3">
                <i class="bi bi-person-circle text-lg bg-teal-50 p-1 rounded"></i>
                <p>Users</p>
            </div>
            <p class="text-xs md:text-sm text-slate-400 mt-1">Manage user accounts and permissions</p>
        </a>
        <a href="{{ route('subjects.index') }}" class="pallet">
            <div class="flex items-center space-x-3">
                <i class="bx bx-book text-lg bg-teal-50 p-1 rounded"></i>
                <p>Subjects</p>
            </div>
            <p class="text-xs md:text-sm text-slate-400 mt-1">All subjects — being taugt at different grades</p>
        </a>
        <a href="{{ route('sections.index') }}" class="pallet">
            <div class="flex items-center space-x-3">
                <i class="bi bi-layers text-lg bg-teal-50 p-1 rounded"></i>
                <p>Classes</p>
            </div>
            <p class="text-xs md:text-sm text-slate-400 mt-1">Manage classes and their students</p>
        </a>
        <a href="{{ route('class-schedule') }}" class="pallet">
            <div class="flex items-center space-x-3">
                <i class="bi-clock text-lg bg-teal-50 p-1 rounded"></i>
                <p>Schedule</p>
            </div>
            <p class="text-xs md:text-sm text-slate-400 mt-1">Create and preview time table</p>
        </a>
        <a href="{{ route('tasks.index') }}" class="pallet">
            <div class="flex items-center space-x-3">
                <i class="bi bi-calendar-event text-lg bg-teal-50 p-1 rounded"></i>
                <p>Tasks</p>
            </div>
            <p class="text-xs md:text-sm text-slate-400 mt-1">Assign task and approve its completion status</p>
        </a>

    </div>
@endsection

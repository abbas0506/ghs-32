<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop"
    class="fixed inset-0 bg-black/50 z-[9998] md:hidden opacity-0 invisible transition-all duration-300 ease-out"
    onclick="closeSidebar()"></div>

<aside aria-label="Sidebar" id='sidebar'
    class="fixed top-0 inset-y-0 left-0 w-full md:w-60 bg-white text-slate-100 h-screen flex flex-col shadow-lg border-r border-slate-50 z-[9999] transform md:transform-none transition-all duration-300 ease-out -translate-x-full md:translate-x-0">

    <!-- Logo Section with Close Button -->
    <div class="fixed right-2 top-2 z-[10000]">
        <!-- Close Button for Mobile -->
        <button id="close-sidebar-btn" onclick="closeSidebar()"
            class="md:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors duration-200 text-slate-500">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
    </div>

    <!-- User Profile Section -->
    <div class="px-4 py-6">
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
            @php
                $fullName = Auth::user()->profile->name ?? (Auth::user()->name ?? 'User');
                $parts = preg_split('/\s+/', trim($fullName));
                $initials = '';
                if (!empty($parts)) {
                    $initials = strtoupper(mb_substr($parts[0], 0, 1));
                    if (count($parts) > 1) {
                        $initials .= strtoupper(mb_substr(end($parts), 0, 1));
                    }
                }
                $currentRole = session('role') ?? Auth::user()->roles->first()->name;
            @endphp
            
            <div class="flex items-center gap-3 mb-4">
                <!-- <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    {{ $initials }}
                </div> -->
                <div class="w-10 h-10" >
                    <img src="{{ asset('images/logo/ghs-32.png') }}" alt="logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ $fullName }}</p>
                    <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">{{ ucfirst($currentRole) }}</p>
                </div>
            </div>

            {{-- Role Switcher --}}
            @if(Auth::user()->roles->count() > 1)
            <div class="space-y-1">
                <p class="text-[9px] uppercase tracking-tighter text-slate-400 font-bold mb-1">Switch Role</p>
                <div class="flex flex-wrap gap-1">
                    @foreach (Auth::user()->roles as $role)
                        @php $isActive = ($role->name == $currentRole); @endphp
                        <a href="{{ url('switch/as', $role->name) }}" 
                           class="px-2 py-1 rounded text-[10px] font-bold transition-all {{ $isActive ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200' }}">
                            {{ ucfirst($role->name) }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Navigation Section -->
    <nav class="flex-1 overflow-y-auto px-4 py-2">
        <!-- Main Menu Section -->
        <div>
            <h2 class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Navigation</h2>
            <div class="space-y-1">
                <!-- Home -->
                <a href="{{ url('/') }}"
                    class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->is('dashboard') || request()->is('/') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-house-door text-lg mr-3"></i>
                    <span>Home</span>
                </a>

                <!-- Attendance -->
                <a href="{{ route('attendance.summary') }}"
                    class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('attendance.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-person-check text-lg mr-3"></i>
                    <span>Attendance</span>
                </a>

                <!-- Assessment -->
                <a href="{{ route('tests.index') }}"
                    class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('tests.*') || request()->routeIs('class-tests.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-journal-check text-lg mr-3"></i>
                    <span>Assessment</span>
                </a>
                
                <!-- Faculty -->
                <a href="{{ route('faculty.index') }}"
                    class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('faculty.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-person-badge text-lg mr-3"></i>
                    <span>Faculty</span>
                </a>

                <!-- Alumni -->
                <a href="{{ route('alumni.index') }}"
                    class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('alumni.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-people text-lg mr-3"></i>
                    <span>Alumni</span>
                </a>

                <!-- Gallery -->
                <a href="{{ route('gallary.index') }}"
                    class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('gallary.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-images text-lg mr-3"></i>
                    <span>Gallery</span>
                </a>

                <!-- Fee -->
                <a href="{{ route('fee-vouchers.index') }}"
                    class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('fee-vouchers.*') || request()->routeIs('bulk-invoices.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-receipt text-lg mr-3"></i>
                    <span>Fee</span>
                </a>

                @role('head')
                    <!-- <a href="{{ route('salaries.index') }}"
                        class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('salaries.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="bi-cash-stack text-lg mr-3"></i>
                        <span>Salaries</span>
                    </a>
                    <a href="{{ route('expenses.index') }}"
                        class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('expenses.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="bi-wallet2 text-lg mr-3"></i>
                        <span>Expenses</span>
                    </a>
                    <a href="{{ route('ledger.index') }}"
                        class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('ledger.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="bi-journal-album text-lg mr-3"></i>
                        <span>Accounts</span>
                    </a> -->
                    <a href="{{ route('config') }}"
                        class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->is('config') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="bi-gear text-lg mr-3"></i>
                        <span>Configuration</span>
                    </a>
                @endrole

                @role('head|admin')
                    <a href="{{ route('syllabi.index') }}"
                        class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('syllabi.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="bi-book text-lg mr-3"></i>
                        <span>Syllabus</span>
                    </a>
                    <a href="{{ route('lessons.index') }}"
                        class="group flex items-center px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-200 relative {{ request()->routeIs('lessons.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="bi-journal-text text-lg mr-3"></i>
                        <span>Lesson Plan</span>
                    </a>
                @endrole
            </div>
        </div>
    </nav>

    {{-- Mobile Signout - Bottom only --}}
    <div class="md:hidden px-4 py-6 border-t border-slate-50">
        <a href="{{ route('signout') }}" class="flex items-center justify-center gap-2 p-4 bg-red-50 text-red-600 rounded-2xl font-bold text-sm hover:bg-red-100 transition-colors">
            <i class="bi bi-box-arrow-right text-xl"></i>
            <span>Sign out</span>
        </a>
    </div>

</aside>

<script>
    function openSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (sidebar) sidebar.classList.remove('-translate-x-full');
        if (backdrop) backdrop.classList.remove('opacity-0', 'invisible');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (sidebar) sidebar.classList.add('-translate-x-full');
        if (backdrop) backdrop.classList.add('opacity-0', 'invisible');
    }

    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 770) {
                    closeSidebar();
                }
            });
        });
    });
</script>

<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop"
    class="fixed inset-0 bg-black/50 z-[9998] md:hidden opacity-0 invisible transition-all duration-300 ease-out"
    onclick="closeSidebar()"></div>

<aside aria-label="Sidebar" id='sidebar'
    class="fixed top-0 inset-y-0 left-0 w-full md:w-56 bg-white text-slate-100 h-screen flex flex-col shadow-lg border-r border-slate-50 z-[9999] transform md:transform-none transition-all duration-300 ease-out -translate-x-full md:translate-x-0">

    <!-- Logo Section with Close Button -->
    <div class="fixed right-2 top-2 z-[10000]">
        <!-- Close Button for Mobile -->
        <button id="close-sidebar-btn" onclick="closeSidebar()"
            class="md:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors duration-200 text-slate-500">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
    </div>

    <!-- User Profile Section -->
    <div class="px-3.5 py-4">
        <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 shadow-xs">
            @php
                $fullName = Auth::user()->profile->name ?? (Auth::user()->name ?? 'User');
                $currentRole = session('role') ?? Auth::user()->roles->first()->name;
                $userRoles = Auth::user()->roles;
            @endphp
            
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 shrink-0">
                    <img src="{{ asset('images/logo/ghs-32.png') }}" alt="logo" class="w-full h-full object-cover rounded-lg shadow-xs">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] font-black text-slate-800 truncate" title="{{ $fullName }}">{{ $fullName }}</p>
                    
                    {{-- Active Role Indicator & Switch Trigger --}}
                    @if($userRoles->count() > 1)
                        <button type="button" onclick="openRoleModal()"
                            class="inline-flex items-center gap-1 px-1.5 py-0.5 mt-0.5 bg-indigo-50 hover:bg-indigo-100/90 text-indigo-700 border border-indigo-200/60 rounded-full text-[8px] font-extrabold uppercase tracking-wider transition-all cursor-pointer group"
                            title="Click to switch active role">
                            <span class="w-1 h-1 rounded-full bg-indigo-600"></span>
                            <span>{{ ucfirst($currentRole) }}</span>
                            <i class="bi bi-chevron-down text-[7px] text-indigo-500 group-hover:translate-y-0.5 transition-transform"></i>
                        </button>
                    @else
                        <span class="inline-block text-[8px] uppercase tracking-widest text-slate-400 font-bold mt-0.5">
                            {{ ucfirst($currentRole) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Section -->
    <nav class="flex-1 overflow-y-auto px-3 py-1">
        <div>
            <h2 class="px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Navigation</h2>
            <div class="space-y-0.5">
                
                <!-- Home -->
                <a href="{{ url('/') }}"
                    class="group flex items-center px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all duration-200 relative {{ request()->is('dashboard') || request()->is('/') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-house-door text-sm mr-2.5"></i>
                    <span>Home</span>
                </a>

                <!-- Students -->
                <a href="{{ route('sections.index') }}"
                    class="group flex items-center px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all duration-200 relative {{ request()->routeIs('sections.*') || request()->routeIs('section.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-people text-sm mr-2.5"></i>
                    <span>Students</span>
                </a>

                <!-- Attendance -->
                <a href="{{ route('attendance.summary') }}"
                    class="group flex items-center px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all duration-200 relative {{ request()->routeIs('attendance.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-person-check text-sm mr-2.5"></i>
                    <span>Attendance</span>
                </a>

                <!-- Assessment -->
                <a href="{{ route('tests.index') }}"
                    class="group flex items-center px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all duration-200 relative {{ request()->routeIs('tests.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-journal-check text-sm mr-2.5"></i>
                    <span>Assessment</span>
                </a>

                <!-- Finance -->
                <a href="{{ route('finance.index') }}"
                    class="group flex items-center px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all duration-200 relative {{ request()->routeIs('finance.*') || request()->routeIs('grants.*') || request()->routeIs('accounts.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-wallet2 text-sm mr-2.5"></i>
                    <span>Finance</span>
                </a>

                <!-- Schedule -->
                <a href="{{ route('class-schedule') }}"
                    class="group flex items-center px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all duration-200 relative {{ request()->routeIs('class-schedule*') || request()->routeIs('user-schedule*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="bi-clock-history text-sm mr-2.5"></i>
                    <span>Schedule</span>
                </a>

                <!-- Lesson Plan -->
                @role('admin|teacher')
                    <a href="{{ route('lesson-plans.index') }}"
                        class="group flex items-center px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all duration-200 relative {{ request()->routeIs('lesson-plans.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="bi-journal-text text-sm mr-2.5"></i>
                        <span>Lesson Plan</span>
                    </a>
                @endrole

            </div>
        </div>
    </nav>

    {{-- Mobile Signout - Bottom only --}}
    <div class="md:hidden px-3 py-4 border-t border-slate-50">
        <a href="{{ route('signout') }}" class="flex items-center justify-center gap-2 p-3 bg-red-50 text-red-600 rounded-xl font-bold text-xs hover:bg-red-100 transition-colors">
            <i class="bi bi-box-arrow-right text-base"></i>
            <span>Sign out</span>
        </a>
    </div>

</aside>

{{-- ══════════════════════════════════════════════════════
     INNOVATIVE ROLE SWITCHER MODAL
══════════════════════════════════════════════════════ --}}
@if($userRoles->count() > 1)
<div id="roleSwitcherModal" class="fixed inset-0 z-[10001] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div id="roleSwitcherCard" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">
        
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
            <div class="flex items-center gap-2">
                <i class="bi bi-person-badge text-base"></i>
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider">Switch Account Role</h3>
                    <p class="text-[9px] text-indigo-100">Select active role view</p>
                </div>
            </div>
            <button type="button" onclick="closeRoleModal()" class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-white/20 text-white transition">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>

        {{-- Body: Roles List --}}
        <div class="p-4 space-y-2">
            @foreach ($userRoles as $role)
                @php 
                    $isActive = ($role->name == $currentRole);
                    $iconClass = match(strtolower($role->name)) {
                        'admin' => 'bi-shield-lock-fill text-indigo-500',
                        'principal' => 'bi-person-workspace text-amber-500',
                        'teacher' => 'bi-person-badge-fill text-emerald-500',
                        'accountant' => 'bi-calculator-fill text-teal-500',
                        default => 'bi-person-circle text-slate-400'
                    };
                @endphp
                <a href="{{ url('switch/as', $role->name) }}"
                   class="flex items-center justify-between p-3 rounded-xl border transition-all duration-200 group {{ $isActive ? 'bg-indigo-50/60 border-indigo-200 shadow-xs' : 'bg-slate-50/40 border-slate-100 hover:bg-slate-50 hover:border-slate-200' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white shadow-xs border border-slate-100 flex items-center justify-center text-sm">
                            <i class="bi {{ $iconClass }}"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-800">{{ ucfirst($role->name) }}</p>
                            <p class="text-[8px] text-slate-400">Switch context to {{ ucfirst($role->name) }}</p>
                        </div>
                    </div>
                    @if ($isActive)
                        <span class="px-2 py-0.5 bg-indigo-600 text-white rounded-full text-[8px] font-black uppercase tracking-wider">
                            Active
                        </span>
                    @else
                        <span class="text-slate-300 group-hover:text-indigo-600 transition-colors">
                            <i class="bi bi-arrow-right-short text-lg"></i>
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

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

    function openRoleModal() {
        const modal = document.getElementById('roleSwitcherModal');
        const card  = document.getElementById('roleSwitcherCard');
        if (!modal) return;
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeRoleModal() {
        const modal = document.getElementById('roleSwitcherModal');
        const card  = document.getElementById('roleSwitcherCard');
        if (!modal) return;
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
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

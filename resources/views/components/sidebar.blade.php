<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop"
    class="fixed inset-0 bg-black/50 z-30 md:hidden opacity-0 invisible transition-all duration-300 ease-out"
    onclick="closeSidebar()"></div>

<aside aria-label="Sidebar" id='sidebar'
    class="fixed top-0 inset-y-0 left-0 max-w-xs md:max-w-none md:w-60 bg-white text-slate-100 h-screen flex flex-col shadow-lg border-r border-slate-50 z-50 transform md:transform-none transition-all duration-300 ease-out -translate-x-full md:translate-x-0">

    <!-- Logo Section with Close Button -->
    <div class="flex items-center justify-between gap-1 px-6 py-6">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo/ghs-32.png') }}" alt="logo" class="w-10 h-10">
            <h1 class="text-lg font-bold text-slate-900">GHS 32/2L</h1>
        </div>
        <!-- Close Button for Mobile -->
        <button id="close-sidebar-btn" onclick="closeSidebar()"
            class="md:hidden p-2 rounded-lg hover:bg-slate-200 transition-colors duration-200 text-slate-600 hover:text-slate-900">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>

    <!-- Navigation Section -->
    <nav class="flex-1 overflow-y-auto px-4 py-6">
        <!-- Main Menu Section -->
        <div class="mb-1">
            <h2 class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Main Menu</h2>
            <div class="space-y-0">
                <!-- Home -->
                <a href="{{ url('/') }}"
                    class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ request()->path() === 'dashboard' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    <div
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ request()->path() === 'dashboard' ? 'opacity-100' : '' }} transition-opacity duration-300">
                    </div>
                    <i class="bi-house text-base"></i>
                    <span class="ml-3">Home</span>
                </a>

                <!-- Attendance -->
                <a href="{{ route('attendance.summary') }}"
                    class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'attendance.summary' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    <div
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'attendance.summary' ? 'opacity-100' : '' }} transition-opacity duration-300">
                    </div>
                    <i class="bi bi-person-check text-base"></i>
                    <span class="ml-3">Attendance</span>
                </a>

                <!-- Assessment -->
                <a href="{{ route('tests.index') }}"
                    class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'tests.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    <div
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'tests.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                    </div>
                    <i class="bi-clipboard-check text-base"></i>
                    <span class="ml-3">Assessment</span>
                </a>

                <!-- Fee -->
                <a href="{{ route('bulk-invoices.index') }}"
                    class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'bulk-invoices.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    <div
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'bulk-invoices.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                    </div>
                    <i class="bi-receipt text-base"></i>
                    <span class="ml-3">Fee</span>
                </a>
            </div>
        </div>

        <!-- Configuration Section -->
        @role('head')
            <div class="mb-8">
                <h2 class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Configuration</h2>
                <div class="space-y-1">
                    <!-- Users -->
                    <a href="{{ route('users.index') }}"
                        class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'users.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'users.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                        </div>
                        <i class="bi bi-person-circle text-base"></i>
                        <span class="ml-3">Users</span>
                    </a>

                    <!-- Subjects -->
                    <a href="{{ route('subjects.index') }}"
                        class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'subjects.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'subjects.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                        </div>
                        <i class="bx bx-book text-base"></i>
                        <span class="ml-3">Subjects</span>
                    </a>

                    <!-- Classes -->
                    <a href="{{ route('sections.index') }}"
                        class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'sections.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'sections.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                        </div>
                        <i class="bi bi-layers text-base"></i>
                        <span class="ml-3">Classes</span>
                    </a>

                    <!-- Schedule -->
                    <a href="{{ route('class-schedule') }}"
                        class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'class-schedule' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'class-schedule' ? 'opacity-100' : '' }} transition-opacity duration-300">
                        </div>
                        <i class="bi-clock text-base"></i>
                        <span class="ml-3">Schedule</span>
                    </a>

                    <!-- Tasks Control -->
                    <a href="{{ route('tasks.index') }}"
                        class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'tasks.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'tasks.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                        </div>
                        <i class="bi bi-calendar-event text-base"></i>
                        <span class="ml-3">Tasks Control</span>
                    </a>

                    <!-- Salaries -->
                    <a href="{{ route('salaries.index') }}"
                        class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'salaries.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'salaries.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                        </div>
                        <i class="bi-receipt text-base"></i>
                        <span class="ml-3">Salaries</span>
                    </a>

                    <!-- Expenses -->
                    <a href="{{ route('expenses.index') }}"
                        class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'expenses.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'expenses.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                        </div>
                        <i class="bi-coin text-base"></i>
                        <span class="ml-3">Expenses</span>
                    </a>

                    <!-- Accounts -->
                    <a href="{{ route('ledger.index') }}"
                        class="group flex items-center px-4 py-1 rounded-lg font-medium text-sm transition-all duration-300 ease-out relative {{ Route::currentRouteName() === 'ledger.index' ? 'bg-gradient-to-r from-teal-500 to-green-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-gradient-to-b from-teal-500 to-green-500 rounded-full opacity-0 {{ Route::currentRouteName() === 'ledger.index' ? 'opacity-100' : '' }} transition-opacity duration-300">
                        </div>
                        <i class="bi-receipt text-base"></i>
                        <span class="ml-3">Accounts</span>
                    </a>
                </div>
            </div>
        @endrole
    </nav>

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

    // Expose functions to global scope
    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;

    // Close sidebar when clicking on a link (only on mobile)
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

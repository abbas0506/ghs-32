<header class="sticky-header">
    <div class="flex flex-wrap w-full h-16 items-center justify-between shadow-sm bg-white px-5">
        <div class="flex items-center space-x-2">
            <a href="{{ url('/') }}" class="flex justify-center">
                <img alt="logo" src="{{ asset('images/logo/ghs-32.png') }}" class="w-8 h-8 md:w-10 md:h-10 md:hidden">
            </a>
        </div>

        <div class="flex items-center space-x-4">
            {{-- Desktop Signout --}}
            <div class="hidden md:flex items-center">
                <a href="{{ route('signout') }}" class="text-slate-600 hover:text-red-600 transition-colors flex items-center gap-2 text-sm font-medium">
                    <i class="bi bi-box-arrow-right text-lg"></i>
                    <span>Sign out</span>
                </a>
            </div>

            {{-- Mobile Menu Trigger --}}
            <div id='menu' class="flex md:hidden cursor-pointer p-2 hover:bg-slate-100 rounded-lg transition-colors" onclick="openSidebar()">
                <i class="bi bi-list text-2xl"></i>
            </div>
        </div>
    </div>
</header>

<header class="sticky-header">
    <div class="flex flex-wrap w-full h-16 items-center justify-between shadow-sm bg-white px-5">
        <div class="flex items-center space-x-2">
            <a href="{{ url('/') }}" class="flex justify-center">
                <img alt="logo" src="{{ asset('images/logo/ghs-32.png') }}" class="w-8 h-8 md:w-10 md:h-10 md:hidden">
            </a>
            {{-- <div class="hidden md:block text-base font-semibold">GHS 32/2L</div> --}}
        </div>

        <div id="current-user-area" class="flex items-center space-x-3 relative">
            @php
                $currentRole = session('role') ?? Auth::user()->roles->first()->name;
            @endphp

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
            @endphp

            <div class="relative">
                <button id="userAvatarBtn" aria-haspopup="true" aria-expanded="false" type="button"
                    class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 text-white rounded-full flex items-center justify-center shadow-md focus:outline-none z-50">
                    <span class="font-semibold">{{ $initials }}</span>
                </button>

                <div id="userDropdown"
                    class="hidden origin-top left-1/2 transform -translate-x-1/2 top-full mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg py-3 z-90 relative">
                    <div id="userDropdownArrow"
                        class="hidden md:block absolute -top-2 w-3 h-3 bg-white rotate-45 border-l border-t border-gray-200 shadow-sm"
                        aria-hidden="true"></div>
                    <button id="userCloseBtn" type="button"
                        class="md:hidden absolute top-4 right-4 p-2 rounded-full bg-white text-gray-600 shadow-sm border border-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="lg:px-8 px-4 py-2 text-sm text-gray-700 font-medium">{{ $fullName }}</div>

                    {{-- Roles list: vertical for all screens; simple style; active dot at upper-right of the role label --}}
                    <div class="mt-3 px-4 flex flex-col items-start gap-2 w-full">
                        @foreach (Auth::user()->roles as $role)
                            @php $isActive = ($role->name == $currentRole); @endphp
                            <a href="{{ url('switch/as', $role->name) }}"
                                class="relative block w-full text-left px-4 py-1 text-sm text-gray-700 hover:bg-gray-50">
                                <span class="inline-block">{{ ucfirst($role->name) }}</span>
                                @if ($isActive)
                                    <span
                                        class="absolute top-2 right-3 w-3 h-3 bg-green-500 rounded-full ring-2 ring-white"></span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 my-3"></div>

                    <div class="px-4">
                        <a href="{{ route('signout') }}"
                            class="flex items-center gap-2 w-full px-4 py-2 justify-center text-sm text-gray-700 bg-white hover:bg-gray-50 rounded-md">
                            <i class="bi bi-box-arrow-right text-lg"></i>
                            <span>Sign out</span>
                        </a>
                    </div>
                </div>
            </div>

            <div id='menu' class="flex md:hidden">
                <i class="bi bi-list"></i>
            </div>
        </div>
    </div>
</header>

<!-- Mobile overlay for panels -->
<div id="mobileOverlay" class="hidden fixed inset-0 bg-black bg-opacity-40 z-40"></div>

<script>
    (function() {
        const avatarBtn = document.getElementById('userAvatarBtn');
        const userDropdown = document.getElementById('userDropdown');
        const userDropdownArrow = document.getElementById('userDropdownArrow');

        function isMobile() {
            return window.matchMedia('(max-width: 767px)').matches;
        }

        function setMobilePanel(drop, side) {
            // side: 'left' or 'right' — prepare dropdown to be a fullscreen fixed panel
            drop.classList.remove('absolute', 'left-1/2', 'transform', '-translate-x-1/2', 'top-full', 'mt-2',
                'w-64', 'rounded-lg');
            drop.classList.add('fixed', 'inset-0', 'z-50', 'bg-white', 'p-6', 'flex', 'flex-col', 'items-center',
                'justify-center', 'transform', 'transition-transform', 'duration-300');
            if (side === 'left') {
                drop.classList.add('-translate-x-full');
            } else {
                drop.classList.add('translate-x-full');
            }
        }

        function clearMobilePanel(drop) {
            drop.classList.remove('fixed', 'inset-0', 'z-50', 'bg-white', 'p-6', 'flex', 'flex-col', 'items-center',
                'justify-center', 'transform', 'transition-transform', 'duration-300', '-translate-x-full',
                'translate-x-full', 'translate-x-0');
            drop.classList.add('absolute', 'left-1/2', 'transform', '-translate-x-1/2', 'top-full', 'mt-2', 'w-64',
                'rounded-lg');
        }

        function openMobile(drop, side) {
            // For mobile we will clone the dropdown into a fixed body panel
            // to avoid touching the original DOM (which was causing layout shifts).
            if (!drop) return;
            // If already cloned, don't recreate
            if (drop._mobileCloneId && document.getElementById(drop._mobileCloneId)) return;

            const clone = document.createElement('div');
            const cloneId = drop.id + '-mobile-clone';
            clone.id = cloneId;
            clone.className =
                'fixed inset-0 z-50 bg-white p-6 flex flex-col items-center justify-center overflow-auto';
            clone.innerHTML = drop.innerHTML;
            // append to body
            document.body.appendChild(clone);
            drop._mobileCloneId = cloneId;
            // show overlay and prevent body scroll
            showOverlay();

            // animate in: use class-based transform
            clone.classList.add('transform', 'transition-transform', 'duration-300');
            if (side === 'left') clone.classList.add('-translate-x-full');
            else clone.classList.add('translate-x-full');
            // allow initial frame
            requestAnimationFrame(() => {
                clone.classList.remove(side === 'left' ? '-translate-x-full' : 'translate-x-full');
                clone.classList.add('translate-x-0');
            });

            // wire close button inside clone
            const closeBtn = clone.querySelector('#userCloseBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeMobile(drop);
                });
            }

            // Ensure links inside clone navigate normally
            const links = clone.querySelectorAll('a');
            links.forEach(a => a.addEventListener('click', () => {
                /* allow navigation */
            }));
        }

        function closeMobile(drop) {
            if (!drop) return;
            const cloneId = drop._mobileCloneId;
            if (cloneId) {
                const clone = document.getElementById(cloneId);
                if (clone) {
                    // animate out
                    clone.classList.remove('translate-x-0');
                    if (drop._mobileSide === 'left') clone.classList.add('-translate-x-full');
                    else clone.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (clone.parentNode) clone.parentNode.removeChild(clone);
                        hideOverlay();
                    }, 300);
                } else {
                    hideOverlay();
                }
                delete drop._mobileCloneId;
                delete drop._mobileSide;
            } else {
                // fallback: hide original element if used
                if (drop.classList.contains('translate-x-0')) {
                    drop.classList.remove('translate-x-0');
                }
                drop.classList.add('hidden');
                clearMobilePanel(drop);
                hideOverlay();
            }
        }

        const overlay = document.getElementById('mobileOverlay');

        function showOverlay() {
            if (overlay) {
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function hideOverlay() {
            if (overlay) {
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function closeDropdown(drop, btn) {
            if (!drop) return;
            if (isMobile() && (drop.classList.contains('fixed') || drop.classList.contains('transform'))) {
                closeMobile(drop);
            } else if (!drop.classList.contains('hidden')) {
                drop.classList.add('hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        }

        // roles are now merged into the user dropdown; no separate role button

        if (avatarBtn && userDropdown) {
            avatarBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const open = !userDropdown.classList.contains('hidden');
                if (isMobile()) {
                    if (!open) {
                        userDropdown._mobileSide = 'right';
                        openMobile(userDropdown, 'right');
                    } else {
                        closeMobile(userDropdown);
                    }
                } else {
                    // desktop: position the dropdown using fixed coordinates anchored to avatar bottom-center
                    if (!open) {
                        // ensure it's visible for measurement
                        userDropdown.classList.remove('hidden');
                        // clear any prior inline positioning
                        userDropdown.style.position = 'fixed';
                        userDropdown.style.transform = 'none';
                        userDropdown.style.left = '';
                        userDropdown.style.top = '';

                        const ddWidth = userDropdown.offsetWidth || 220;
                        const avatarRect = avatarBtn.getBoundingClientRect();
                        let left = Math.round(avatarRect.left + avatarRect.width / 2 - ddWidth / 2);
                        // clamp within viewport with small margin
                        const margin = 8;
                        left = Math.max(margin, Math.min(left, window.innerWidth - ddWidth - margin));
                        const top = Math.round(avatarRect.bottom + 8);

                        userDropdown.style.left = left + 'px';
                        userDropdown.style.top = top + 'px';
                        userDropdown.dataset.positioned = 'fixed';
                        // position arrow relative to dropdown
                        if (userDropdownArrow) {
                            const arrowW = userDropdownArrow.offsetWidth || 12;
                            let arrowLeft = Math.round(avatarRect.left + avatarRect.width / 2 - left -
                                arrowW / 2);
                            // clamp arrow within dropdown bounds
                            arrowLeft = Math.max(8, Math.min(arrowLeft, (ddWidth - 8 - arrowW)));
                            userDropdownArrow.style.left = arrowLeft + 'px';
                            userDropdownArrow.style.top = '-6px';
                            userDropdownArrow.classList.remove('hidden');
                        }
                    } else {
                        // closing: remove inline styles and hide
                        if (userDropdown.dataset.positioned === 'fixed') {
                            userDropdown.style.position = '';
                            userDropdown.style.left = '';
                            userDropdown.style.top = '';
                            userDropdown.style.transform = '';
                            delete userDropdown.dataset.positioned;
                            if (userDropdownArrow) {
                                userDropdownArrow.classList.add('hidden');
                                userDropdownArrow.style.left = '';
                                userDropdownArrow.style.top = '';
                            }
                        }
                        userDropdown.classList.add('hidden');
                    }
                }
                const expanded = avatarBtn.getAttribute('aria-expanded') === 'true';
                avatarBtn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            });
            const userCloseBtn = document.getElementById('userCloseBtn');
            if (userCloseBtn) userCloseBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeMobile(userDropdown);
            });
        }

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            if (userDropdown && !userDropdown.contains(e.target) && e.target !== avatarBtn) closeDropdown(
                userDropdown, avatarBtn);
        });

        // Overlay click closes panels
        if (overlay) {
            overlay.addEventListener('click', function() {
                closeMobile(userDropdown);
            });
        }

        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropdown(userDropdown, avatarBtn);
            }
        });
    })();
</script>

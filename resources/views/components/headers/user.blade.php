<header class="sticky-header">
    <div class="flex flex-wrap w-full h-16 items-center justify-between shadow-sm bg-white px-5">
        <div class="flex items-center space-x-2">
            <a href="{{ url('/') }}" class="flex justify-center">
                <img alt="logo" src="{{ asset('images/logo/ghs-32.png') }}" class="w-8 h-8 md:w-10 md:h-10 md:hidden">
            </a>
            @php
                $currentSession = \App\Models\AcademicSession::current();
                $allSessions    = \App\Models\AcademicSession::orderBy('start_date', 'desc')->get();
            @endphp
            @if($currentSession)
                <div class="pl-1 md:pl-2">
                    <button type="button" onclick="openSessionHeaderModal()"
                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 hover:bg-emerald-100/80 text-emerald-700 rounded-full text-[9px] font-extrabold uppercase tracking-wider border border-emerald-200/60 shadow-sm transition-all cursor-pointer group"
                        title="Click to view all sessions and edit opening balances">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Session: {{ $currentSession->name }}</span>
                        <i class="bi bi-chevron-down text-[8px] text-emerald-500 group-hover:translate-y-0.5 transition-transform"></i>
                    </button>
                </div>
            @endif
        </div>

        <div class="flex items-center">
            {{-- Desktop Signout --}}
            <div class="hidden md:flex items-center">
                <a href="{{ route('signout') }}" class="text-slate-600 hover:text-red-600 transition-colors flex items-center gap-2 text-sm font-medium">
                    <i class="bi bi-box-arrow-right text-lg"></i>
                    <span>Sign out</span>
                </a>
            </div>

            {{-- Mobile Menu Trigger --}}
            <div id='menu' class="flex md:hidden cursor-pointer p-1.5 hover:bg-slate-100 rounded-lg transition-colors" onclick="openSidebar()">
                <i class="bi bi-list text-2xl"></i>
            </div>
        </div>
    </div>
</header>

{{-- ══════════════════════════════════════════════════════
     HEADER SESSION SWITCHER & OPENING BALANCE MODAL
══════════════════════════════════════════════════════ --}}
@if($currentSession)
<div id="headerSessionModal" class="fixed inset-0 z-[10001] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div id="headerSessionCard" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">
        
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-5 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-700 text-white">
            <div class="flex items-center gap-2">
                <i class="bi bi-calendar-event text-base"></i>
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider">Academic Sessions & Opening Balances</h3>
                    <p class="text-[9px] text-emerald-100 mt-0.5">Switch active session or update opening balances</p>
                </div>
            </div>
            <button type="button" onclick="closeSessionHeaderModal()" class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-white/20 text-white transition">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>

        <div class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">
            
            {{-- Active Session Opening Balances Form --}}
            <div class="bg-teal-50/50 rounded-xl p-4 border border-teal-100">
                <div class="flex items-center justify-between border-b border-teal-100/80 pb-2 mb-3">
                    <span class="text-[9px] font-black uppercase tracking-wider text-teal-800">
                        Opening Balances for Active Session ({{ $currentSession->name }})
                    </span>
                    <span class="px-2 py-0.5 bg-emerald-500 text-white rounded-full text-[8px] font-extrabold uppercase">Active</span>
                </div>

                <form action="{{ route('academic-sessions.update', $currentSession->id) }}" method="POST" class="space-y-3">
                    @csrf @method('PUT')
                    <input type="hidden" name="name" value="{{ $currentSession->name }}">
                    <input type="hidden" name="start_date" value="{{ $currentSession->start_date ? $currentSession->start_date->format('Y-m-d') : '' }}">
                    <input type="hidden" name="end_date" value="{{ $currentSession->end_date ? $currentSession->end_date->format('Y-m-d') : '' }}">
                    <input type="hidden" name="is_current" value="1">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[8px] font-bold text-slate-500 uppercase mb-1">
                                FTF Opening Balance (PKR)
                            </label>
                            <input type="number" name="ftf_start" value="{{ old('ftf_start', $currentSession->ftf_start) }}" min="0"
                                class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                        </div>
                        <div>
                            <label class="block text-[8px] font-bold text-slate-500 uppercase mb-1">
                                NSB / Grant Opening (PKR)
                            </label>
                            <input type="number" name="nsb_start" value="{{ old('nsb_start', $currentSession->nsb_start) }}" min="0"
                                class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-[9px] font-bold rounded-lg shadow transition flex items-center gap-1">
                            <i class="bi bi-check-lg"></i> Update Opening Balances
                        </button>
                    </div>
                </form>
            </div>

            {{-- All Sessions List & Switcher --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-[9px] font-black uppercase tracking-wider text-slate-600">All Academic Sessions</h4>
                    <a href="{{ route('academic-sessions.index') }}" class="text-[9px] font-bold text-teal-600 hover:underline">Manage All Sessions →</a>
                </div>

                <div class="space-y-1.5">
                    @foreach ($allSessions as $sess)
                        @php $isActive = ($sess->id == $currentSession->id); @endphp
                        <div class="flex items-center justify-between p-2.5 rounded-xl border {{ $isActive ? 'bg-emerald-50/40 border-emerald-200' : 'bg-slate-50/50 border-slate-100 hover:bg-slate-50' }}">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black text-slate-800">{{ $sess->name }}</span>
                                    @if ($isActive)
                                        <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-[7px] font-extrabold uppercase rounded">Current Active</span>
                                    @endif
                                </div>
                                <p class="text-[8px] text-slate-400 mt-0.5">
                                    FTF Start: {{ number_format($sess->ftf_start) }} PKR • NSB Start: {{ number_format($sess->nsb_start) }} PKR
                                </p>
                            </div>

                            @if (!$isActive)
                                <form action="{{ route('academic-sessions.switch', $sess->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 text-[8px] font-extrabold bg-slate-200/80 hover:bg-teal-600 hover:text-white text-slate-700 rounded-lg transition">
                                        Switch to Active
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] text-emerald-600 font-bold"><i class="bi bi-check-circle-fill"></i> Selected</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openSessionHeaderModal() {
        const modal = document.getElementById('headerSessionModal');
        const card  = document.getElementById('headerSessionCard');
        if (!modal) return;
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeSessionHeaderModal() {
        const modal = document.getElementById('headerSessionModal');
        const card  = document.getElementById('headerSessionCard');
        if (!modal) return;
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
</script>
@endif

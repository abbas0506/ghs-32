@extends(auth()->check() ? 'layouts.app' : 'layouts.basic')

@section('header')
    @if(auth()->check())
        <x-headers.user></x-headers.user>
    @else
        <x-header></x-header>
    @endif
@endsection

@section('style')
    <style>
        .alumni-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .alumni-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
    </style>
@endsection

@section(auth()->check() ? 'page-content' : 'body')
    <div class="{{ auth()->check() ? '' : 'px-6 md:px-24 mt-20' }} min-h-screen">
        <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-10 gap-4">
            <div class="text-center md:text-left mt-16">
                <h1 class="heading-section">Our Distinguished <span class="text-teal-600">Alumni</span></h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-[0.2em] mt-1 font-semibold">Celebrating our heritage and shared success</p>
            </div>
            <div class="flex gap-3">
                @unless(auth()->check() && auth()->user()->hasRole('head'))
                    <a href="{{ route('alumni.create') }}" class="bg-white text-teal-600 border border-teal-100 px-4 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-teal-50 transition-colors flex items-center gap-2">
                        <i class="bi bi-person-plus"></i> Register as Alumni
                    </a>
                @endunless
                @auth
                    {{-- Admin only button or just rely on the above one --}}
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-3 bg-teal-50 border border-teal-100 text-teal-700 text-xs rounded-xl flex items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($alumni as $person)
                <div class="alumni-card rounded-2xl p-5 relative overflow-hidden group {{ !$person->is_approved ? 'border-amber-200 bg-amber-50/30' : '' }}">
                    @auth
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1.5 z-20">
                            @if(!$person->is_approved)
                                <form action="{{ route('alumni.approve', $person->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Approve Profile" class="p-1.5 bg-white/80 text-emerald-600 rounded-lg hover:bg-emerald-50 shadow-sm transition-colors border border-emerald-100">
                                        <i class="bi bi-check-lg text-[10px]"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('alumni.edit', $person->id) }}" class="p-1.5 bg-white/80 text-blue-600 rounded-lg hover:bg-blue-50 shadow-sm transition-colors border border-blue-100">
                                <i class="bi bi-pencil-square text-[10px]"></i>
                            </a>
                            <form action="{{ route('alumni.destroy', $person->id) }}" method="POST" onsubmit="return confirm('Permanently delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-white/80 text-red-600 rounded-lg hover:bg-red-50 shadow-sm transition-colors border border-red-100">
                                    <i class="bi bi-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    @endauth

                    <div class="flex flex-col items-center">
                        <div class="relative mb-4">
                            <div class="absolute inset-0 bg-teal-200 rounded-full blur-2xl opacity-10 group-hover:opacity-30 transition-opacity animate-pulse"></div>
                            @if($person->photo)
                                <img src="{{ asset('storage/' . $person->photo) }}" alt="{{ $person->name }}" class="relative w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover shadow-lg border-2 border-white bg-white">
                            @else
                                <div class="relative w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-slate-50 flex items-center justify-center border-2 border-white shadow-lg text-slate-300">
                                    <i class="bi bi-person text-3xl"></i>
                                </div>
                            @endif
                            <div class="absolute -bottom-2 -right-2 bg-white px-2 py-0.5 rounded-full shadow-sm border border-slate-100 text-[9px] font-bold text-teal-600">
                                #{{ $person->session }}
                            </div>
                            @if(!$person->is_approved)
                                <div class="absolute -top-2 -left-2 bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full shadow-sm border border-amber-200 text-[8px] font-bold uppercase tracking-tighter">
                                    Pending
                                </div>
                            @endif
                        </div>

                        <h3 class="text-xs font-bold text-slate-800 text-center uppercase tracking-tight">{{ $person->prefix }} {{ $person->name }}</h3>
                        
                        @if($person->phone)
                            <div class="mt-2 flex items-center gap-1.5 text-slate-400">
                                <i class="bi bi-telephone text-[9px]"></i>
                                <span class="text-[10px] font-medium">{{ $person->phone }}</span>
                            </div>
                        @endif

                        @if($person->introduction)
                            <div class="mt-4 relative px-2">
                                <i class="bi bi-quote absolute -top-2 -left-1 text-teal-100 text-xl"></i>
                                <p class="text-[10px] text-slate-600 text-center leading-relaxed line-clamp-3 italic">
                                    {{ $person->introduction }}
                                </p>
                            </div>
                        @endif

                        @if($person->address)
                            <div class="mt-4 pt-4 border-t border-slate-100 w-full flex items-start justify-center gap-1.5 text-slate-400">
                                <i class="bi bi-geo-alt text-[9px]"></i>
                                <span class="text-[9px] text-center max-w-[150px] leading-tight">{{ $person->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                        <i class="bi bi-people text-3xl"></i>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">No alumni profiles yet</p>
                    <p class="text-[10px] text-slate-400 mt-1">Check back later for updates</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

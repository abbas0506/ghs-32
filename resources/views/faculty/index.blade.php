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
        .faculty-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.5);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .faculty-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border-color: rgba(13, 148, 136, 0.2);
        }
        .bps-badge {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        }
    </style>
@endsection

@section(auth()->check() ? 'page-content' : 'body')
    <div class="{{ auth()->check() ? '' : 'px-6 md:px-24 mt-20' }} min-h-screen pb-20">
        <!-- Hero Section -->
        <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-16 gap-6 section-reveal">
            <div class="text-center md:text-left max-w-2xl mt-16">
                <h1 class="heading-section">
                    Our Highly Skilled <span class="text-teal-600">Faculty</span>
                </h1>
                <p class="text-sm md:text-base text-slate-500 mt-4 leading-relaxed font-light">
                    Our educators are selected for their expertise and dedication to activity-based learning, ensuring every student receives a modern, comprehensive education.
                </p>
                <div class="flex items-center gap-2 mt-4 justify-center md:justify-start">
                    <div class="h-1 w-12 bg-teal-600 rounded-full"></div>
                    <span class="heading-label !mb-0">Excellence in Teaching</span>
                </div>
            </div>
            @unless(auth()->check() && auth()->user()->hasRole('head'))
                <a href="{{ route('faculty.create') }}" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-teal-600/20 hover:bg-teal-700 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="bi bi-person-plus text-sm"></i> {{ auth()->check() ? 'Add Faculty' : 'Register as Faculty' }}
                </a>
            @endunless
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-teal-50 border border-teal-100 text-teal-700 text-xs rounded-2xl flex items-center gap-3 animate-fade-in">
                <i class="bi bi-check-circle-fill text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Faculty Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($users as $user)
                <div class="faculty-card rounded-[2.5rem] p-6 relative group overflow-hidden">
                    <!-- Admin Actions -->
                    @auth
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2 z-20">
                            @if($user->profile->status == 0)
                                <form action="{{ route('faculty.approve', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Approve Profile" class="p-2 bg-white/90 text-emerald-600 rounded-xl hover:bg-emerald-50 shadow-sm border border-emerald-100 transition-all">
                                        <i class="bi bi-check-lg text-xs"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('faculty.edit', $user->id) }}" class="p-2 bg-white/90 text-blue-600 rounded-xl hover:bg-blue-50 shadow-sm border border-blue-100 transition-all">
                                <i class="bi bi-pencil-square text-xs"></i>
                            </a>
                            <form action="{{ route('faculty.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Remove this faculty member permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-white/90 text-red-600 rounded-xl hover:bg-red-50 shadow-sm border border-red-100 transition-all">
                                    <i class="bi bi-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    @endauth

                    <div class="flex flex-col items-center">
                        <!-- Photo Section -->
                        <div class="relative mb-6">
                            <div class="absolute inset-0 bg-teal-200 rounded-full blur-2xl opacity-10 group-hover:opacity-30 transition-opacity"></div>
                            @if($user->profile->photo)
                                <img src="{{ asset('storage/' . $user->profile->photo) }}" alt="{{ $user->profile->name }}" class="relative w-28 h-28 rounded-[2rem] object-cover shadow-xl border-4 border-white transition-transform group-hover:scale-105">
                            @else
                                <div class="relative w-28 h-28 rounded-[2rem] bg-slate-50 flex items-center justify-center border-4 border-white shadow-xl text-slate-300">
                                    <i class="bi bi-person-fill text-5xl"></i>
                                </div>
                            @endif
                            @if($user->profile->status == 0)
                                <div class="absolute -top-2 -left-2 bg-amber-100 text-amber-700 px-3 py-1 rounded-full shadow-lg border-2 border-white text-[9px] font-black uppercase tracking-tighter z-30">
                                    Pending
                                </div>
                            @endif
                            <div class="absolute -bottom-2 -right-2 bps-badge text-white px-3 py-1 rounded-full text-[9px] font-black shadow-lg border-2 border-white">
                                BPS-{{ $user->profile->bps }}
                            </div>
                        </div>

                        <!-- Info Section -->
                        <div class="text-center w-full">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight leading-tight mb-1">
                                {{ $user->profile->prefix }} {{ $user->profile->name }}
                            </h3>
                            <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mb-4">
                                {{ $user->profile->designation }}
                            </p>

                            <div class="space-y-2.5">
                                @if($user->phone)
                                    <div class="flex items-center justify-center gap-2 text-slate-500 bg-slate-50 py-1.5 rounded-xl border border-slate-100/50">
                                        <i class="bi bi-telephone text-[10px]"></i>
                                        <span class="text-[10px] font-semibold">{{ $user->phone }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-center gap-2 text-slate-400">
                                    <i class="bi bi-mortarboard text-[11px]"></i>
                                    <span class="text-[10px] font-medium italic">{{ $user->profile->qualification ?? 'Qualified Educator' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 text-slate-200 shadow-sm">
                        <i class="bi bi-people text-4xl"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Faculty profiles coming soon</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('page-content')
    <div class="space-y-8 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Classes</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-mortarboard text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 leading-none mb-1">Academic Classes</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Manage academic sections and enrollments</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @can('create', App\Models\Section::class)
                    <a href="{{ route('sections.create') }}" 
                       class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                       <i class="bi-plus-lg"></i> New Class
                    </a>
                @endcan
            </div>
        </div>

        <!-- Quick Summary Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <!-- Metric 1: Total Classes -->
            <div class="bg-teal-600 rounded-3xl p-6 text-white shadow-xl shadow-teal-100 flex flex-col justify-center relative overflow-hidden hover:-translate-y-1 transition-transform">
                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute top-4 right-4 w-6 h-6 bg-white/10 rounded-full"></div>
                <div class="flex items-center justify-between mb-1 relative z-10">
                    <p class="text-[9px] md:text-[10px] font-black text-teal-100 uppercase tracking-widest">Total Classes</p>
                    <i class="bi-mortarboard text-white opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-2 relative z-10">
                    <h2 class="text-xl md:text-2xl font-black text-white">{{ $sections->count() }}</h2>
                </div>
            </div>

            <!-- Metric 2: Total Students -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center hover:-translate-y-1 transition-transform">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Students</p>
                    <i class="bi bi-people text-teal-600 opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-1">
                    <h2 class="text-xl md:text-2xl font-black text-slate-800">{{ number_format($studentsCount) }}</h2>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Enrolled</span>
                </div>
            </div>
        </div>

        <!-- Search & Filter Area -->
        <div class="flex items-center justify-between gap-4 max-w-xl mx-auto">
            <div class="relative flex-1 group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors"></i>
                <input type="text" 
                    id="classSearch"
                    onkeyup="searchClasses(event)"
                    placeholder="Search by class name..." 
                    class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-slate-600 font-medium focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none"
                >
            </div>
        </div>

        <!-- Classes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-8" id="classesGrid">
            @foreach ($sections->sortBy('grade_id') as $section)
                <div class="class-card group" data-name="{{ strtolower($section->name) }}">
                    <a href="{{ route('sections.show', $section) }}" class="block">
                        <div class="bg-white rounded-[1.25rem] border border-slate-100 p-4 hover:shadow-xl hover:shadow-teal-900/5 hover:border-teal-200 transition-all duration-300 relative hover:-translate-y-1 flex items-center gap-4 w-full">
                            
                            <!-- Class Initial Avatar -->
                            <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center text-2xl group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors font-black shrink-0">
                                {{ substr($section->name, 0, 1) }}
                            </div>
                            
                            <!-- Primary Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-black text-slate-800 group-hover:text-teal-700 transition-colors leading-none mb-1.5 truncate">{{ $section->name }}</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 truncate">
                                    {{ $section->students->count() }} Enrolled
                                </p>
                            </div>

                            <!-- Right Indicators -->
                            <div class="flex items-center shrink-0">
                                @php $newCount = $section->newAdmissions()->count(); @endphp
                                @if($newCount > 0)
                                    <span class="bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded border border-emerald-100 mr-2">
                                        +{{ $newCount }}
                                    </span>
                                @endif
                                <i class="bi bi-chevron-right text-slate-300 group-hover:text-teal-500 transition-colors"></i>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200">
            <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="bi bi-search text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-700">No classes found</h3>
            <p class="text-slate-400 max-w-xs mx-auto mt-2">Try adjusting your search to find the academic section you're looking for.</p>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function searchClasses(event) {
            const query = event.target.value.toLowerCase();
            const cards = document.querySelectorAll('.class-card');
            const grid = document.getElementById('classesGrid');
            const emptyState = document.getElementById('emptyState');
            let found = 0;

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(query)) {
                    card.classList.remove('hidden');
                    found++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (found === 0) {
                grid.classList.add('hidden');
                emptyState.classList.remove('hidden');
            } else {
                grid.classList.remove('hidden');
                emptyState.classList.add('hidden');
            }
        }
    </script>
@endsection

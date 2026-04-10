@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">My Class Tests</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-mortarboard text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 leading-none mb-1">Individual Assessments</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Manage and analyze your own subject-specific tests</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('class-tests.analysis') }}" 
                   class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all">
                   <i class="bi-graph-up-arrow"></i> Comparative Analysis
                </a>
                <a href="{{ route('class-tests.create') }}" 
                   class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                   <i class="bi-plus-lg"></i> New Class Test
                </a>
            </div>
        </div>

        <!-- Tests Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tests as $test)
                @php 
                    $ts = $test->testSubjects->first(); 
                    $submitted = $ts?->hasBeenSubmitted();
                @endphp
                <div class="group relative bg-white rounded-[2rem] border border-slate-100 p-6 hover:shadow-xl hover:shadow-slate-200/50 hover:border-teal-100 transition-all duration-500">
                    <div class="flex flex-col h-full">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $ts?->section?->name ?? 'Mixed' }}</span>
                                <h3 class="font-black text-slate-800 leading-tight group-hover:text-teal-600 transition-colors">{{ $test->title }}</h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl {{ $submitted ? 'bg-teal-50 text-teal-600' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center transition-colors">
                                <i class="bi {{ $submitted ? 'bi-check-circle-fill' : 'bi-hourglass-split' }} text-lg"></i>
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Subject</span>
                                <span class="text-xs font-black text-slate-600">{{ $ts?->subject?->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Max Marks</span>
                                <span class="px-2 py-1 bg-slate-50 rounded-lg text-xs font-black text-slate-700">{{ $test->max_marks }}</span>
                            </div>
                             <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Status</span>
                                <span class="text-[10px] font-black uppercase {{ $submitted ? 'text-teal-600' : 'text-slate-400 italic' }}">
                                    {{ $submitted ? 'Result Submitted' : 'Pending Entry' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                            <span class="text-[9px] font-medium text-slate-400">{{ $test->created_at->format('d M Y') }}</span>
                            <div class="flex items-center gap-2">
                                @if($test->user_id == auth()->id() || auth()->user()->hasAnyRole(['admin', 'head']))
                                <form action="{{ route('class-tests.destroy', $test) }}" method="POST" class="inline" onsubmit="return confirm('Delete this test?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition-colors text-xs">
                                        <i class="bi-trash3"></i>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('class-tests.show', $test) }}" 
                                   class="px-4 py-2 bg-slate-800 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-teal-600 transition-all">
                                   View Results
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4 text-slate-300">
                        <i class="bi-journals text-3xl"></i>
                    </div>
                    <h3 class="font-black text-slate-400 uppercase tracking-widest">No individual tests found</h3>
                    <p class="text-slate-400 text-xs mt-2 italic">Start by creating your first subject-specific test</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

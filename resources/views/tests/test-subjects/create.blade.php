@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">

        {{-- ── Header ── --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3 flex-wrap">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('tests.show', $test) }}" class="hover:text-teal-600 transition-colors">{{ $test->title }}</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Add Subject</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100 shrink-0">
                        <i class="bi-plus-circle-fill text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 leading-none mb-2">Add Subject Allocation</h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-[9px] font-black uppercase tracking-widest rounded-full border border-teal-100">
                            <i class="bi-journal-check text-[8px]"></i> {{ $test->title }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('tests.show', $test) }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all self-start shrink-0">
                <i class="bi-arrow-left"></i> Back
            </a>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Hidden form — submitted by JS on card click --}}
        <form action="{{ route('test.test-subjects.store', $test) }}" method="POST" id="allocForm">
            @csrf
            <input type="hidden" id="allocation_id" name="allocation_id" value="">
        </form>

        {{-- ── Allocation Grid ── --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">

            <div class="px-6 md:px-8 py-5 border-b border-slate-50 bg-slate-50/40">
                <p class="text-sm font-black text-slate-700">Select a Subject Allocation</p>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Click any row to instantly add it to this test.</p>
            </div>

            @if($unallocated->count())
                <div class="divide-y divide-slate-50">
                    @foreach ($unallocated->sortBy('section.id') as $allocation)
                        <button type="button"
                                onclick="submitAllocation('{{ $allocation->id }}')"
                                class="allocation w-full flex items-center gap-4 px-6 md:px-8 py-4 text-left hover:bg-teal-50/40 hover:border-l-4 hover:border-teal-500 transition-all group border-l-4 border-transparent">

                            {{-- Icon --}}
                            <div class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center shrink-0 group-hover:bg-teal-100 group-hover:text-teal-600 transition-colors border border-slate-100">
                                <i class="bi-book text-lg"></i>
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black text-slate-800 group-hover:text-teal-700 transition-colors leading-tight">
                                    {{ $allocation->subject->name }}
                                </p>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tight">
                                        <i class="bi-collection mr-1"></i>{{ $allocation->section->name }}
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <span class="text-[9px] font-bold text-teal-600/80 uppercase tracking-tight">
                                        <i class="bi-person mr-1"></i>{{ $allocation->user->profile->name }}
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">
                                        Lecture #{{ $allocation->lecture_no }}
                                    </span>
                                </div>
                            </div>

                            {{-- Arrow --}}
                            <div class="shrink-0 w-8 h-8 rounded-xl border border-slate-100 flex items-center justify-center text-slate-300 group-hover:text-teal-600 group-hover:border-teal-200 group-hover:bg-white transition-all group-hover:translate-x-0.5">
                                <i class="bi-arrow-right text-sm"></i>
                            </div>
                        </button>
                    @endforeach
                </div>
            @else
                <div class="py-20 text-center">
                    <i class="bi-check-all text-5xl text-teal-200 mb-4 block"></i>
                    <p class="text-sm font-black text-slate-500">All allocations already added.</p>
                    <p class="text-xs text-slate-400 font-medium mt-1">Every subject schedule has been linked to this test.</p>
                    <a href="{{ route('tests.show', $test) }}"
                       class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 transition-all">
                        <i class="bi-arrow-left"></i> Back to Test
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
<script>
    function submitAllocation(id) {
        document.getElementById('allocation_id').value = id;
        document.getElementById('allocForm').submit();
    }
</script>
@endsection

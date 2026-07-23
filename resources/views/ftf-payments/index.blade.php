@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div>
            <div class="flex items-center gap-2 text-slate-400 text-xs uppercase tracking-widest font-bold mb-2">
                <a href="/" class="hover:text-teal-600">Home</a>
                <i class="bi-chevron-right text-[10px]"></i>
                <a href="{{ route('ftf-vouchers.index') }}" class="hover:text-teal-600">Vouchers</a>
                <i class="bi-chevron-right text-[10px]"></i>
                <a href="{{ route('ftf-vouchers.show', $voucher) }}" class="hover:text-teal-600">Details</a>
                <i class="bi-chevron-right text-[10px]"></i>
                <span class="text-teal-600">Section Payments</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-800 flex items-center justify-center text-white shadow-lg">
                        <i class="bi-people text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 leading-tight">{{ $section->name }}</h1>
                        <p class="text-slate-400 text-sm font-medium">{{ $voucher->description }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @role('head|admin')
                        <a href="{{ route('ftf-voucher.section.payments.import', [$voucher, $section]) }}" 
                           class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                            <i class="bi-plus-circle text-teal-600"></i> Import Missing
                        </a>
                        <form action="{{ route('ftf-voucher.section.payments.clean', [$voucher, $section]) }}" method="post" onsubmit="return confirm('Destructive Action: Are you sure?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="flex items-center gap-2 bg-white border border-red-50 px-4 py-2 rounded-xl text-xs font-bold text-red-600 hover:bg-red-50 transition-all shadow-sm">
                                <i class="bi-trash"></i> Clean
                            </button>
                        </form>
                    @endrole
                </div>
            </div>
        </div>

        <!-- Section Quick Stats -->
        @php
            $totalStudents = $fees->count();
            $paidStudents = $fees->whereNotNull('payment_date')->count();
            $pendingStudents = $totalStudents - $paidStudents;
            $percent = $totalStudents > 0 ? round(($paidStudents / $totalStudents) * 100) : 0;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center font-bold">
                    {{ $totalStudents }}
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Students</p>
                    <p class="text-xs font-bold text-slate-700">In Section</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center font-bold">
                    {{ $paidStudents }}
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paid</p>
                    <p class="text-xs font-bold text-green-600">Collected</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-400 flex items-center justify-center font-bold">
                    {{ $pendingStudents }}
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pending</p>
                    <p class="text-xs font-bold text-red-400">Not Paid</p>
                </div>
            </div>
            <div class="bg-teal-600 p-4 rounded-2xl shadow-lg shadow-teal-100 flex flex-col justify-center">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-[10px] font-bold text-teal-100 uppercase tracking-widest">Progress</span>
                    <span class="text-[10px] font-bold text-white">{{ $percent }}%</span>
                </div>
                <div class="w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-white h-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>

        <!-- message -->
        <div class="md:w-full">
            <x-message></x-message>
        </div>

        <!-- Main List Section -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-4 md:p-6 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/30">
                <div class="relative w-full md:w-1/3">
                    <input type="text" id='searchby' placeholder="Search by student name..." class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 text-sm focus:border-teal-400 focus:ring-teal-400 transition-all shadow-sm"
                        oninput="search(event)">
                    <i class="bi-search absolute left-4 top-2.5 text-slate-300"></i>
                </div>
                <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Paid
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span> Unpaid
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-[10px] font-bold uppercase tracking-widest text-slate-400 border-b border-gray-100">
                            <th class="w-16">Roll#</th>
                            <th class="w-40 py-1">Student Info</th>
                            <th class="w-24 py-1 text-center">Status</th>
                            <th class="w-24 py-1 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fees->sortBy('student.rollno') as $fee)
                            @php
                                $firstNameLetter = substr($fee->student->name, 0, 1);
                                $isPaid = (bool)$fee->payment_date;
                            @endphp
                            <tr class="tr group hover:bg-slate-50 transition-all border-b border-gray-50 last:border-0">
                                <td class="px-3 py-1">
                                    <span class="text-sm font-bold text-slate-400">{{ $fee->student->rollno }}</span>
                                </td>
                                <td class="px-3 py-1 text-search">
                                    <div class="flex items-center text-left">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700 leading-none mb-1">{{ $fee->student->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium">{{ $fee->student->father_name }}</p>
                                        </div>
                                    </div>
                                </td>
                               <td class="px-8 py-1 text-center">
                                    @if ($isPaid)
                                        <div class="inline-flex flex-col items-center">
                                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-widest border border-green-100">Paid</span>
                                            <p class="text-[9px] text-slate-400 mt-1 font-bold">{{ $fee->payment_date->format('d M, Y') }}</p>
                                        </div>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest border border-slate-100">Unpaid</span>
                                    @endif
                                </td>
                                <td class="px-3 py-1">
                                    <div class="flex items-center justify-end">
                                        <form action="{{ route('ftf-voucher.section.payments.update', [$voucher, $section, $fee]) }}" method="post" class="flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $isPaid ? 0 : 1 }}">
                                            <input type="hidden" name="payment_date" value="{{ now()->toDateString() }}">
                                            
                                            @if ($isPaid)
                                                <button class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Undo Payment">
                                                    <i class="bi-arrow-counterclockwise text-lg"></i>
                                                </button>
                                            @else
                                                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-slate-800 hover:shadow-lg hover:shadow-teal-100 transition-all shadow-md shadow-teal-50">
                                                    Pay Now <i class="bi-check2-circle text-sm leading-none"></i>
                                                </button>
                                            @endif
                                        </form>
                                        
                                        <a href="{{ route('ftf-voucher.section.payments.edit', [$voucher, $section, $fee]) }}" class="flex w-10 h-10 items-center justify-center rounded-xl bg-white border border-gray-100 text-slate-400 hover:text-teal-600 hover:bg-slate-50 transition-all shadow-sm ml-1" title="Edit Settings">
                                            <i class="bi-gear"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($fees->isEmpty())
                <div class="py-20 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                        <i class="bi-people text-3xl text-slate-200"></i>
                    </div>
                    <p class="text-slate-400 font-medium italic">No students added to this section yet.</p>
                </div>
            @endif
        </div>
    </div>

    <script type="text/javascript">
        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            $('.tr').each(function() {
                var studentName = $(this).find('.text-search').text().toLowerCase();
                if (!(studentName.includes(searchtext))) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }
    </script>
@endsection

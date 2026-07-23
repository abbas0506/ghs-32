@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-xs uppercase tracking-widest font-bold mb-2">
                    <a href="/" class="hover:text-teal-600">Home</a>
                    <i class="bi-chevron-right text-[10px]"></i>
                    <a href="{{ route('ftf-vouchers.index') }}" class="hover:text-teal-600">Vouchers</a>
                    <i class="bi-chevron-right text-[10px]"></i>
                    <span class="text-teal-600">Voucher Details</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 flex items-center justify-center text-white shadow-lg shadow-teal-100">
                        <i class="bi-receipt text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 leading-tight">{{ $feeVoucher->name }}</h1>
                        <p class="text-slate-400 text-sm font-medium">Month: {{ date('F', mktime(0, 0, 0, $feeVoucher->month, 10)) }} {{ $feeVoucher->year }} | Due: {{ $feeVoucher->due_date->format('d M, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($feeVoucher->isOpen())
                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-tighter border border-green-100 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Open
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-tighter border border-slate-100">
                        Closed
                    </span>
                @endif

                <a href="{{ route('ftf-vouchers.edit', $feeVoucher) }}" class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                    <i class="bx bx-pencil text-teal-600"></i> Edit
                </a>
                
                <form action="{{ route('ftf-vouchers.destroy', $feeVoucher) }}" method="post" onsubmit="return confirmDel(event)" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="flex items-center gap-2 bg-white border border-red-100 px-4 py-2 rounded-xl text-xs font-bold text-red-600 hover:bg-red-50 transition-all shadow-sm">
                        <i class="bx bx-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- message -->
        <div class="md:w-full">
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $payable = $feeVoucher->sumOfPayableAmount();
                $paid = $feeVoucher->sumOfPaidAmount();
                $pending = $payable - $paid;
                $percentage = $payable > 0 ? round(($paid / $payable) * 100) : 0;
            @endphp
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-teal-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="relative">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Expected</p>
                    <h3 class="text-xl font-bold text-slate-800">Rs. {{ number_format($payable) }}</h3>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="text-[10px] font-bold text-teal-600 bg-teal-50 px-2 py-0.5 rounded-lg">Target Revenue</span>
                    </div>
                </div>
            </div>

            <div class="bg-teal-600 p-6 rounded-2xl shadow-xl shadow-teal-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="relative">
                    <p class="text-xs font-bold text-teal-100 uppercase tracking-widest mb-1">Total Collected</p>
                    <h3 class="text-2xl font-bold text-white">Rs. {{ number_format($paid) }}</h3>
                    <div class="mt-4">
                        <div class="flex justify-between text-[10px] font-bold text-teal-100 mb-1">
                            <span>Collection Progress</span>
                            <span>{{ $percentage }}%</span>
                        </div>
                        <div class="w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-white h-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="relative">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pending Amount</p>
                    <h3 class="text-xl font-bold text-slate-800">Rs. {{ number_format($pending) }}</h3>
                    <div class="mt-4 flex items-center gap-2">
                        <i class="bi-exclamation-circle-fill text-orange-400 text-xs"></i>
                        <span class="text-[10px] font-bold text-orange-600 italic">Needs Attention</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Table Section -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-slate-50/30">
                <div>
                    <h3 class="font-bold text-slate-800">Section-wise Breakdown</h3>
                    <p class="text-xs text-slate-400">Manage payments for each class section</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Paid Today</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-fixed w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-[10px] font-bold uppercase tracking-widest text-slate-400 border-b border-gray-100">
                            <th class="w-24 px-5 py-2">Class</th>
                            <th class="w-40 px-5 py-2">Status</th>
                            <th class="w-40 px-5 py-2">Collection %</th>
                            <th class="w-40 px-5 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sections as $section)
                            @php
                                $paidCount = $feeVoucher->studentsWhoHavePaid($section->id)->count();
                                $totalCount = $feeVoucher->studentsFromSection($section->id)->count();
                                $paidToday = $feeVoucher->studentsWhoHavePaidToday($section->id)->count();
                                $secPercentage = $totalCount > 0 ? round(($paidCount / $totalCount) * 100) : 0;
                            @endphp
                            <tr class="group hover:bg-slate-50 transition-all border-b border-gray-50 last:border-0">
                                <td class="px-8 py-2">
                                   <div>
                                        <p class="text-sm font-bold text-slate-700 leading-none mb-1">{{ $section->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-medium">Class</p>
                                    </div>
                                </td>
                                <td class="px-8 py-2">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-bold text-slate-700">{{ $paidCount }} <span class="text-slate-300 font-medium">/ {{ $totalCount }}</span></p>
                                        @if ($paidToday)
                                            <span class="flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-green-50 text-green-600 text-[10px] font-bold border border-green-100 animate-bounce">
                                                <i class="bi-lightning-fill text-[8px]"></i> +{{ $paidToday }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-medium">Students Paid</p>
                                </td>
                                <td class="px-8 py-2">
                                    <div class="flex flex-col items-center justify-center w-full">
                                        <div class="w-32 bg-slate-100 h-1 rounded-full overflow-hidden mb-1">
                                            <div class="h-full bg-teal-500 rounded-full transition-all" style="width: {{ $secPercentage }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-500">{{ $secPercentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-8 py-2 text-right">
                                    <a href="{{ route('ftf-voucher.section.payments.index', [$feeVoucher, $section]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-teal-600 hover:shadow-lg hover:shadow-teal-100 transition-all">
                                        Manage <i class="bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($sections->isEmpty())
                <div class="py-20 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                        <i class="bi-inbox text-3xl text-slate-200"></i>
                    </div>
                    <p class="text-slate-400 font-medium italic">No sections assigned to this voucher.</p>
                </div>
            @endif
        </div>

        <!-- Warning Footer -->
        <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100 border-dashed flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                <i class="bi-exclamation-triangle"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-orange-800 uppercase tracking-widest">Destructive Activity Warning</h4>
                <p class="text-xs text-orange-600 leading-relaxed max-w-2xl">Deleting this voucher will permanently remove all associated payment records for every student across all sections. This action cannot be undone. Please ensure you have backed up any necessary data before proceeding.</p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function confirmDel(event) {
            event.preventDefault();
            var form = event.target;

            Swal.fire({
                title: 'Delete Voucher?',
                text: "All payment records for all students will be permanently lost!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete Everything',
                cancelButtonText: 'Cancel',
                padding: '2rem',
                customClass: {
                    container: 'modern-swal-container',
                    popup: 'modern-swal-popup rounded-3xl',
                    title: 'font-bold text-slate-800',
                    confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3',
                    cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection

@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-xs uppercase tracking-widest font-bold mb-2">
                    <a href="/" class="hover:text-teal-600">Home</a>
                    <i class="bi-chevron-right text-[10px]"></i>
                    <a href="{{ route('fee-vouchers.index') }}" class="hover:text-teal-600">Vouchers</a>
                    <i class="bi-chevron-right text-[10px]"></i>
                    <span class="text-teal-600">Edit Voucher</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 flex items-center justify-center text-white shadow-lg shadow-teal-100">
                        <i class="bi-pencil-square text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-800 leading-tight">Edit Fee Voucher</h1>
                        <p class="text-slate-400 text-sm font-medium">Voucher #{{ $feeVoucher->id }} - {{ $feeVoucher->description }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('fee-vouchers.show', $feeVoucher) }}" class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                    <i class="bx bx-arrow-back text-teal-600"></i> Back to Details
                </a>
            </div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Details -->
            <div class="lg:col-span-2">
                <form action="{{ route('fee-vouchers.update', $feeVoucher) }}" method="post" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                        <div class="flex items-center gap-2 text-teal-600 mb-2">
                            <i class="bi-info-circle-fill"></i>
                            <h2 class="text-sm font-black uppercase tracking-wider">Basic Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Description / Title</label>
                                <input type="text" name='description' class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-50/50 transition-all outline-none font-medium text-slate-700" 
                                    placeholder="Describe fee voucher" value="{{ old('description', $feeVoucher->description) }}" required>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Amount (Rs.)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rs.</span>
                                    <input type="number" name='amount' class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-50/50 transition-all outline-none font-black text-slate-700" 
                                        placeholder="0.00" value="{{ old('amount', $feeVoucher->amount) }}" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Due Date</label>
                                <input type="date" name='due_date' class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-50/50 transition-all outline-none font-medium text-slate-700" 
                                    value="{{ old('due_date', $feeVoucher->due_date->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Sections Selection -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-50 bg-slate-50/30 flex items-center justify-between">
                            <div>
                                <h3 class="font-black text-slate-800">Assign Sections</h3>
                                <p class="text-xs text-slate-400">Select classes this voucher applies to</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <label for="check_all" class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" id="check_all" class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-teal-600 transition-colors">Select All</span>
                                </label>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($sections as $section)
                                    @php
                                        $isAssigned = in_array($section->id, $assignedSectionIds);
                                    @endphp
                                    <label class="premium-checkable-row flex items-center justify-between px-4 py-3 rounded-2xl border border-gray-100 hover:border-teal-200 hover:bg-teal-50/30 cursor-pointer transition-all group {{ $isAssigned ? 'active bg-teal-50 border-teal-200' : '' }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-100 flex items-center justify-center group-[.active]:border-teal-200 transition-all shadow-sm">
                                                <i class="bi-mortarboard-fill text-slate-400 group-[.active]:text-teal-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700 leading-none mb-1 group-[.active]:text-teal-900 transition-all">{{ $section->name }}</p>
                                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">Class / Section</p>
                                            </div>
                                        </div>
                                        <div class="relative w-6 h-6">
                                            <input type="checkbox" name="section_ids_array[]" value="{{ $section->id }}" 
                                                class="peer hidden" {{ $isAssigned ? 'checked' : '' }}>
                                            <div class="w-6 h-6 rounded-full border-2 border-slate-200 peer-checked:border-teal-500 peer-checked:bg-teal-500 flex items-center justify-center transition-all">
                                                <i class="bi-check-lg text-white scale-0 peer-checked:scale-100 transition-transform"></i>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('fee-vouchers.show', $feeVoucher) }}" class="px-6 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition-all uppercase tracking-widest">Cancel</a>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-teal-600 text-white text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-xl hover:shadow-teal-100 transition-all">
                            Save Changes <i class="bi-check2-all ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Info/Help -->
            <div class="space-y-6">
                <div class="bg-slate-800 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full"></div>
                    <div class="relative space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-500 flex items-center justify-center">
                            <i class="bi-lightning-charge-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-black leading-tight">Fast Sync Logic</h3>
                        <p class="text-sm text-slate-400 leading-relaxed font-medium">When you add a section, the system automatically creates payment records for all students in that class. Unchecking a section will remove unpaid records from that class.</p>
                        <div class="pt-4 border-t border-white/10">
                            <div class="flex items-center gap-2 text-xs font-bold text-teal-400">
                                <i class="bi-shield-check"></i>
                                <span>Paid records are never deleted</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest border-b border-slate-50 pb-3">Status Overview</h3>
                    <div class="space-y-4">
                        @php
                            $paid = $feeVoucher->feePayments()->whereNotNull('payment_date')->count();
                            $total = $feeVoucher->feePayments()->count();
                        @endphp
                        <div class="flex justify-between items-end">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Collection Rate</p>
                            <span class="text-lg font-black text-slate-800">{{ $total > 0 ? round(($paid/$total)*100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-500 h-full transition-all" style="width: {{ $total > 0 ? ($paid/$total)*100 : 0 }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium italic">Cannot change month/year directly. They are derived from the due date.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        // Individual interaction
        $('.premium-checkable-row input').change(function() {
            if ($(this).prop('checked')) {
                $(this).closest('.premium-checkable-row').addClass('active bg-teal-50 border-teal-200');
            } else {
                $(this).closest('.premium-checkable-row').removeClass('active bg-teal-50 border-teal-200');
            }
        });

        // Select All interaction
        $('#check_all').change(function() {
            const isChecked = $(this).prop('checked');
            $('.premium-checkable-row input').each(function() {
                $(this).prop('checked', isChecked).trigger('change');
            });
        });
    </script>
@endsection

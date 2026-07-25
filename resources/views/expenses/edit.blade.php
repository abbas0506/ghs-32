@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        {{-- Minimal Header Section --}}
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ request('redirect_to', route('expenses.index')) }}" class="text-slate-400 hover:text-teal-600 text-xs transition-colors" title="Back">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase">Edit School Expense</span>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @endif

        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            @method('PUT')

            @if(request()->has('redirect_to'))
                <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
            @endif

            {{-- Main Form Fields --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 space-y-4">
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider border-b border-slate-50 pb-2">Expense Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Fund Source & Grants --}}
                        <input type="hidden" name="fund_type" id="fund_type" value="{{ $expense->fund_type }}">
                        <input type="hidden" name="grant_id" value="{{ $expense->grant_id }}">

                        {{-- Expense Category --}}
                        <div>
                            <label for="expense_account_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Expense Category <span class="text-red-500">*</span></label>
                            <select name="expense_account_id" id="expense_account_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                                <option value="">-- Select Expense Type --</option>
                                @foreach ($expenseAccounts as $expenseAccount)
                                    <option value="{{ $expenseAccount->id }}" {{ old('expense_account_id', $expense->expense_account_id) == $expenseAccount->id ? 'selected' : '' }}>
                                        {{ $expenseAccount->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Expense Type --}}
                        <div>
                            <label for="expense_type" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Expense Type <span class="text-red-500">*</span></label>
                            <select name="expense_type" id="expense_type" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                                <option value="purchase" {{ old('expense_type', $expense->expense_type) == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                <option value="service" {{ old('expense_type', $expense->expense_type) == 'service' ? 'selected' : '' }}>Service</option>
                                <option value="utility" {{ old('expense_type', $expense->expense_type) == 'utility' ? 'selected' : '' }}>Utility</option>
                                <option value="other" {{ old('expense_type', $expense->expense_type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        {{-- Expense Date --}}
                        <div>
                            <label for="expense_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Expense Date <span class="text-red-500">*</span></label>
                            <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->created_at ? $expense->created_at->format('Y-m-d') : date('Y-m-d')) }}"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Description / Detail</label>
                            <input type="text" id="description" name="description" value="{{ old('description', $expense->description) }}"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                                placeholder="e.g. Purchased lab stationery">
                        </div>

                        {{-- Net Amount --}}
                        <div>
                            <label for="amount" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Net Amount Paid (PKR) <span class="text-red-500">*</span></label>
                            <input type="number" id="amount" name="amount" value="{{ old('amount', $expense->net_amount) }}" min="1"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                                placeholder="e.g. 7650" required>
                        </div>

                        {{-- Receipt Number --}}
                        <div>
                            <label for="receipt_no" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Receipt Number <span class="text-red-500">*</span></label>
                            <input type="text" id="receipt_no" name="receipt_no" value="{{ old('receipt_no', $expense->receipt_no) }}"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                                placeholder="e.g. REC-5912" required>
                        </div>

                        {{-- Resolution Number --}}
                        <div>
                            <label for="school_resolution_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Resolution Number</label>
                            <div class="flex items-center gap-1.5">
                                <select id="school_resolution_id" name="school_resolution_id"
                                    class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition bg-white">
                                    <option value="" data-date="">Select Resolution</option>
                                    @foreach ($resolutions as $res)
                                        <option value="{{ $res->id }}" data-date="{{ $res->date->format('Y-m-d') }}" {{ old('school_resolution_id', $expense->school_resolution_id) == $res->id ? 'selected' : '' }}>
                                            {{ $res->number }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" id="btn_add_resolution_modal"
                                    class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs flex items-center justify-center transition border border-slate-200" title="Add New Resolution">
                                    <i class="bi bi-plus-lg font-bold"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Resolution Date --}}
                        <div>
                            <label for="resolution_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Resolution Date</label>
                            <input type="date" id="resolution_date" value="{{ $expense->schoolResolution ? $expense->schoolResolution->date->format('Y-m-d') : '' }}"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg bg-slate-50 text-slate-500 outline-none transition" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Ledger Cashbook Breakdown --}}
            <div class="space-y-4">
                {{-- Tax Settings Section --}}
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 space-y-4" id="tax_section">
                    <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                        <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">Withholding Tax Config</h3>
                        <span id="ftf_tax_badge" class="hidden px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold uppercase">
                            Non-Taxable
                        </span>
                    </div>

                    {{-- Tax Type Selector --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Transaction Tax Category</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="relative flex flex-col items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition" id="label_tax_none">
                                <input type="radio" name="tax_type" value="none" class="sr-only" {{ old('tax_type', $expense->tax_type) == 'none' ? 'checked' : '' }}>
                                <span class="text-xs font-bold text-slate-700">No Tax</span>
                                <span class="text-[8px] text-slate-400 mt-0.5">0% Deduction</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition" id="label_tax_purchase">
                                <input type="radio" name="tax_type" value="purchase" class="sr-only" {{ old('tax_type', $expense->tax_type) == 'purchase' ? 'checked' : '' }}>
                                <span class="text-xs font-bold text-slate-700">Purchase</span>
                                <span class="text-[8px] text-slate-400 mt-0.5">GST + IT</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition" id="label_tax_service">
                                <input type="radio" name="tax_type" value="service" class="sr-only" {{ old('tax_type', $expense->tax_type) == 'service' ? 'checked' : '' }}>
                                <span class="text-xs font-bold text-slate-700">Labour / Service</span>
                                <span class="text-[8px] text-slate-400 mt-0.5">PST + IT</span>
                            </label>
                        </div>
                    </div>

                    {{-- Editable Rates Inputs --}}
                    <div class="grid grid-cols-3 gap-3 pt-2" id="rates_wrapper">
                        {{-- GST Rate --}}
                        <div id="gst_rate_container">
                            <label for="gst_rate" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">GST Rate (%)</label>
                            <input type="number" step="0.01" id="gst_rate" name="gst_rate" value="{{ old('gst_rate', $expense->gst_rate ?? '19.00') }}" min="0" max="100"
                                class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition">
                        </div>

                        {{-- PST Rate --}}
                        <div id="pst_rate_container">
                            <label for="pst_rate" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">PST Rate (%)</label>
                            <input type="number" step="0.01" id="pst_rate" name="pst_rate" value="{{ old('pst_rate', $expense->pst_rate ?? '20.00') }}" min="0" max="100"
                                class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition">
                        </div>

                        {{-- IT Rate --}}
                        <div id="it_rate_container">
                            <label for="it_rate" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Income Tax (%)</label>
                            <input type="number" step="0.01" id="it_rate" name="it_rate" value="{{ old('it_rate', $expense->it_rate ?? '11.00') }}" min="0" max="100"
                                class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition">
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-xl shadow-md p-5 flex flex-col justify-between min-h-[280px]">
                    <div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5 mb-3">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Cashbook Preview</span>
                            <span id="fund_badge" class="px-2 py-0.5 bg-amber-500 text-white rounded text-[8px] font-bold uppercase tracking-wider">{{ strtoupper($expense->fund_type ?? 'NSB') }}</span>
                        </div>
                        
                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between text-slate-300">
                                <span>Net Paid Amount:</span>
                                <span class="font-bold text-white"><span id="preview_net">0</span> PKR</span>
                            </div>
                            
                            {{-- Taxes List --}}
                            <div class="space-y-1.5 pl-2 border-l border-white/10 my-2" id="taxes_list_preview">
                                <div class="flex items-center justify-between text-[11px] text-slate-400" id="preview_gst_row">
                                    <span>GST Withheld (<span id="preview_gst_pct">18</span>%):</span>
                                    <span>+ <span id="preview_gst_val">0</span> PKR</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-400" id="preview_pst_row">
                                    <span>PST Withheld (<span id="preview_pst_pct">20</span>%):</span>
                                    <span>+ <span id="preview_pst_val">0</span> PKR</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-400" id="preview_it_row">
                                    <span>Income Tax (<span id="preview_it_pct">5.5</span>%):</span>
                                    <span>+ <span id="preview_it_val">0</span> PKR</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-emerald-300" id="preview_total_tax_row">
                                <span>Total Taxes Added:</span>
                                <span class="font-bold">+ <span id="preview_total_tax">0</span> PKR</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-white/10 pt-4 mt-6">
                        <div class="flex items-baseline justify-between mb-4">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Gross Bill Amount:</span>
                            <span class="text-base font-black text-amber-400"><span id="preview_gross">0</span> <span class="text-[9px] text-amber-500 font-bold">PKR</span></span>
                        </div>
                        
                        <button type="submit" class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-lg transition shadow-md flex items-center justify-center gap-1.5">
                            <i class="bi bi-file-earmark-check-fill text-sm"></i>
                            Update Cashbook
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const fundTypeSelect = document.getElementById('fund_type');
            const taxTypeInputs = document.querySelectorAll('input[name="tax_type"]');
            
            const gstRateInput = document.getElementById('gst_rate');
            const pstRateInput = document.getElementById('pst_rate');
            const itRateInput = document.getElementById('it_rate');

            const gstRateContainer = document.getElementById('gst_rate_container');
            const pstRateContainer = document.getElementById('pst_rate_container');
            const itRateContainer = document.getElementById('it_rate_container');

            const ftfTaxBadge = document.getElementById('ftf_tax_badge');
            const taxSection = document.getElementById('tax_section');
            const fundBadge = document.getElementById('fund_badge');

            const previewGross = document.getElementById('preview_gross');
            
            const previewGstRow = document.getElementById('preview_gst_row');
            const previewGstPct = document.getElementById('preview_gst_pct');
            const previewGstVal = document.getElementById('preview_gst_val');

            const previewPstRow = document.getElementById('preview_pst_row');
            const previewPstPct = document.getElementById('preview_pst_pct');
            const previewPstVal = document.getElementById('preview_pst_val');

            const previewItRow = document.getElementById('preview_it_row');
            const previewItPct = document.getElementById('preview_it_pct');
            const previewItVal = document.getElementById('preview_it_val');

            const previewTotalTaxRow = document.getElementById('preview_total_tax_row');
            const previewTotalTax = document.getElementById('preview_total_tax');
            const previewNet = document.getElementById('preview_net');

            function recalculate() {
                const amount = parseFloat(amountInput.value) || 0;
                const fundType = fundTypeSelect.value;
                
                let selectedTaxType = 'none';
                taxTypeInputs.forEach(input => {
                    if (input.checked) {
                        selectedTaxType = input.value;
                    }
                });

                fundBadge.textContent = fundType.toUpperCase();

                if (fundType === 'ftf') {
                    const noneInput = document.querySelector('input[name="tax_type"][value="none"]');
                    if (noneInput) noneInput.checked = true;
                    selectedTaxType = 'none';

                    ftfTaxBadge.classList.remove('hidden');
                    taxSection.classList.add('opacity-60', 'pointer-events-none');
                } else {
                    ftfTaxBadge.classList.add('hidden');
                    taxSection.classList.remove('opacity-60', 'pointer-events-none');
                }

                let gstRate = 0.00;
                let pstRate = 0.00;
                let itRate = 0.00;

                if (selectedTaxType === 'none') {
                    gstRateContainer.classList.add('hidden');
                    pstRateContainer.classList.add('hidden');
                    itRateContainer.classList.add('hidden');

                    previewGstRow.classList.add('hidden');
                    previewPstRow.classList.add('hidden');
                    previewItRow.classList.add('hidden');
                    previewTotalTaxRow.classList.add('hidden');
                } else if (selectedTaxType === 'purchase') {
                    gstRateContainer.classList.remove('hidden');
                    itRateContainer.classList.remove('hidden');
                    pstRateContainer.classList.add('hidden');

                    previewGstRow.classList.remove('hidden');
                    previewItRow.classList.remove('hidden');
                    previewTotalTaxRow.classList.remove('hidden');
                    previewPstRow.classList.add('hidden');

                    gstRate = parseFloat(gstRateInput.value) || 0;
                    itRate = parseFloat(itRateInput.value) || 0;
                } else if (selectedTaxType === 'service') {
                    pstRateContainer.classList.remove('hidden');
                    itRateContainer.classList.remove('hidden');
                    gstRateContainer.classList.add('hidden');

                    previewPstRow.classList.remove('hidden');
                    previewItRow.classList.remove('hidden');
                    previewTotalTaxRow.classList.remove('hidden');
                    previewGstRow.classList.add('hidden');

                    pstRate = parseFloat(pstRateInput.value) || 0;
                    itRate = parseFloat(itRateInput.value) || 0;
                }

                taxTypeInputs.forEach(input => {
                    const labelId = 'label_tax_' + input.value;
                    const labelEl = document.getElementById(labelId);
                    if (labelEl) {
                        if (input.checked) {
                            labelEl.classList.add('border-teal-500', 'bg-teal-50/20', 'ring-1', 'ring-teal-500');
                        } else {
                            labelEl.classList.remove('border-teal-500', 'bg-teal-50/20', 'ring-1', 'ring-teal-500');
                        }
                    }
                });
                const gstVal = Math.round((amount * gstRate) / 100);
                const pstVal = Math.round((amount * pstRate) / 100);
                const itVal = Math.round((amount * itRate) / 100);
                const totalTax = gstVal + pstVal + itVal;
                const calculatedGross = amount + totalTax;

                previewGross.textContent = calculatedGross.toLocaleString();
                
                previewGstPct.textContent = gstRate;
                previewGstVal.textContent = gstVal.toLocaleString();
                
                previewPstPct.textContent = pstRate;
                previewPstVal.textContent = pstVal.toLocaleString();
                
                previewItPct.textContent = itRate;
                previewItVal.textContent = itVal.toLocaleString();

                previewTotalTax.textContent = totalTax.toLocaleString();
                previewNet.textContent = amount.toLocaleString();
            }

            taxTypeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.checked) {
                        if (this.value === 'purchase') {
                            gstRateInput.value = '18.00';
                            itRateInput.value = '5.50';
                        } else if (this.value === 'service') {
                            pstRateInput.value = '20.00';
                            itRateInput.value = '5.50';
                        }
                        recalculate();
                    }
                });
            });

            [amountInput, fundTypeSelect, gstRateInput, pstRateInput, itRateInput].forEach(elem => {
                if (elem) {
                    elem.addEventListener('input', recalculate);
                    elem.addEventListener('change', recalculate);
                }
            });

            recalculate();
        });
    </script>

    {{-- MODAL: ADD RESOLUTION --}}
    <div id="addResolutionModal" class="fixed inset-0 z-[60] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="addResolutionCard"
            class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 bg-slate-50 border-b border-slate-200">
                <div>
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">Add School Resolution</h3>
                    <p class="text-[9px] text-slate-400 mt-0.5">Create a new resolution to link expenses</p>
                </div>
                <button type="button" onclick="closeModal('addResolutionModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form id="addResolutionForm" class="p-5 space-y-4">
                @csrf
                {{-- Resolution Number --}}
                <div>
                    <label for="new_resolution_no" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Resolution Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="new_resolution_no" name="number" placeholder="e.g. RES-15"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition" required>
                </div>

                {{-- Resolution Date --}}
                <div>
                    <label for="new_resolution_date" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Resolution Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="new_resolution_date" name="date" value="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition" required>
                </div>

                <div class="flex gap-2.5 pt-2">
                    <button type="button" onclick="closeModal('addResolutionModal')"
                        class="flex-1 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" id="btn_submit_resolution"
                        class="flex-1 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-lg transition shadow-md">
                        Save Resolution
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            /* Modal helpers */
            window.openModal = function(id) {
                const modal = document.getElementById(id);
                const card = document.getElementById('addResolutionCard');
                modal.classList.remove('hidden');
                requestAnimationFrame(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                });
            };

            window.closeModal = function(id) {
                const modal = document.getElementById(id);
                const card = document.getElementById('addResolutionCard');
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => modal.classList.add('hidden'), 200);
            };

            const addResolutionModalEl = document.getElementById('addResolutionModal');
            if (addResolutionModalEl) {
                addResolutionModalEl.addEventListener('click', function (e) {
                    if (e.target === this) closeModal('addResolutionModal');
                });
            }

            const btnAddResolution = document.getElementById('btn_add_resolution_modal');
            if (btnAddResolution) {
                btnAddResolution.addEventListener('click', function() {
                    openModal('addResolutionModal');
                });
            }

            const resolutionDropdown = document.getElementById('school_resolution_id');
            if (resolutionDropdown) {
                resolutionDropdown.addEventListener('change', function() {
                    const selectedOpt = this.options[this.selectedIndex];
                    const dateVal = selectedOpt ? (selectedOpt.getAttribute('data-date') || '') : '';
                    const dateInput = document.getElementById('resolution_date');
                    if (dateInput) dateInput.value = dateVal;
                });
            }

            // Form Ajax submit
            const addResolutionForm = document.getElementById('addResolutionForm');
            if (addResolutionForm) {
                addResolutionForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const numberInput = document.getElementById('new_resolution_no');
                    const dateInput = document.getElementById('new_resolution_date');
                    const submitBtn = document.getElementById('btn_submit_resolution');
                    
                    const number = numberInput.value;
                    const date = dateInput.value;
                    
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Saving...';
                    
                    fetch("{{ route('school-resolutions.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ number: number, date: date })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Resolution';
                        
                        if (res.status === 200 && res.body.success) {
                            const resolution = res.body.resolution;
                            
                            const rawDate = new Date(resolution.date);
                            const formattedDate = rawDate.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });
                            
                            const optVal = resolution.id;
                            const optText = resolution.number;
                            const optDate = resolution.date.split('T')[0];
                            
                            if (resolutionDropdown) {
                                const option = document.createElement('option');
                                option.value = optVal;
                                option.text = optText;
                                option.setAttribute('data-date', optDate);
                                option.selected = true;
                                resolutionDropdown.appendChild(option);
                            }
                            
                            const dateInputVal = document.getElementById('resolution_date');
                            if (dateInputVal) dateInputVal.value = optDate;
                            
                            numberInput.value = '';
                            closeModal('addResolutionModal');
                            
                            Swal.fire({
                                title: 'Success!',
                                text: 'Resolution added successfully',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            let msg = 'Something went wrong.';
                            if (res.body.errors && res.body.errors.number) {
                                msg = res.body.errors.number[0];
                            }
                            Swal.fire({
                                title: 'Error!',
                                text: msg,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(err => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Resolution';
                        Swal.fire({
                            title: 'Error!',
                            text: 'Network error or connection failed.',
                            icon: 'error'
                        });
                    });
                });
            }
        });
    </script>
@endsection

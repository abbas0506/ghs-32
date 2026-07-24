@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        {{-- Minimal Header Section --}}
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('expenses.index') }}" class="text-slate-400 hover:text-teal-600 text-xs transition-colors" title="Back to Expenses">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase">New School Expense</span>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @endif

        <form action="{{ route('expenses.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            {{-- Main Form Fields --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 space-y-4">
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider border-b border-slate-50 pb-2">Expense Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Fund Source (Hidden & Defaulted to NSB) --}}
                        <input type="hidden" name="fund_type" id="fund_type" value="nsb">

                        {{-- Expense Category --}}
                        <div>
                            <label for="expense_account_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Expense Category <span class="text-red-500">*</span></label>
                            <select name="expense_account_id" id="expense_account_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                                <option value="">-- Select Expense Type --</option>
                                @foreach ($expenseAccounts as $expenseAccount)
                                    <option value="{{ $expenseAccount->id }}" {{ old('expense_account_id') == $expenseAccount->id ? 'selected' : '' }}>
                                        {{ $expenseAccount->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label for="payment_account_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Payment Method <span class="text-red-500">*</span></label>
                            <select name="payment_account_id" id="payment_account_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                                <option value="">-- Select Payment Method --</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}" {{ (old('payment_account_id') == $method->id || $method->code == '1001') ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Expense Type --}}
                        <div>
                            <label for="expense_type" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Expense Type <span class="text-red-500">*</span></label>
                            <select name="expense_type" id="expense_type" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                                <option value="purchase" {{ old('expense_type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                <option value="service" {{ old('expense_type') == 'service' ? 'selected' : '' }}>Service</option>
                                <option value="utility" {{ old('expense_type') == 'utility' ? 'selected' : '' }}>Utility</option>
                                <option value="other" {{ old('expense_type', 'other') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Description / Detail</label>
                            <input type="text" id="description" name="description" value="{{ old('description', '') }}"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                                placeholder="e.g. Purchased lab stationery">
                        </div>

                        {{-- Net Amount --}}
                        <div>
                            <label for="amount" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Net Amount Paid (PKR) <span class="text-red-500">*</span></label>
                            <input type="number" id="amount" name="amount" value="{{ old('amount', '') }}" min="1"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                                placeholder="e.g. 7650" required>
                        </div>

                        {{-- Receipt Number --}}
                        <div>
                            <label for="receipt_no" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Receipt Number <span class="text-red-500">*</span></label>
                            <input type="text" id="receipt_no" name="receipt_no" value="{{ old('receipt_no', '') }}"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                                placeholder="e.g. REC-5912" required>
                        </div>
                    </div>
                </div>

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
                                <input type="radio" name="tax_type" value="none" class="sr-only" checked>
                                <span class="text-xs font-bold text-slate-700">No Tax</span>
                                <span class="text-[8px] text-slate-400 mt-0.5">0% Deduction</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition" id="label_tax_purchase">
                                <input type="radio" name="tax_type" value="purchase" class="sr-only">
                                <span class="text-xs font-bold text-slate-700">Purchase</span>
                                <span class="text-[8px] text-slate-400 mt-0.5">GST + IT</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition" id="label_tax_service">
                                <input type="radio" name="tax_type" value="service" class="sr-only">
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
                            <input type="number" step="0.01" id="gst_rate" name="gst_rate" value="19.00" min="0" max="100"
                                class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition">
                        </div>

                        {{-- PST Rate --}}
                        <div id="pst_rate_container">
                            <label for="pst_rate" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">PST Rate (%)</label>
                            <input type="number" step="0.01" id="pst_rate" name="pst_rate" value="20.00" min="0" max="100"
                                class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition">
                        </div>

                        {{-- IT Rate --}}
                        <div id="it_rate_container">
                            <label for="it_rate" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Income Tax (%)</label>
                            <input type="number" step="0.01" id="it_rate" name="it_rate" value="11.00" min="0" max="100"
                                class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Ledger Cashbook Breakdown --}}
            <div class="space-y-4">
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-xl shadow-md p-5 flex flex-col justify-between min-h-[280px]">
                    <div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5 mb-3">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Cashbook Preview</span>
                            <span id="fund_badge" class="px-2 py-0.5 bg-amber-500 text-white rounded text-[8px] font-bold uppercase tracking-wider">NSB</span>
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
                            Save to Cashbook
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

                const sgWrapper = document.getElementById('special_grant_wrapper');
                const sgInput = document.getElementById('special_grant_id');
                if (fundType === 'special_grant') {
                    sgWrapper.classList.remove('hidden');
                    sgInput.setAttribute('required', 'required');
                } else {
                    sgWrapper.classList.add('hidden');
                    sgInput.removeAttribute('required');
                    sgInput.value = '';
                }

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

                const rateSum = gstRate + pstRate + itRate;
                let estimatedGross = amount;
                if (rateSum < 100) {
                    estimatedGross = Math.round(amount / (1 - rateSum / 100));
                }

                const gstVal = Math.round((estimatedGross * gstRate) / 100);
                const pstVal = Math.round((estimatedGross * pstRate) / 100);
                const itVal = Math.round((estimatedGross * itRate) / 100);
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
                            gstRateInput.value = '19.00';
                            itRateInput.value = '11.00';
                        } else if (this.value === 'service') {
                            pstRateInput.value = '20.00';
                            itRateInput.value = '11.00';
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
@endsection

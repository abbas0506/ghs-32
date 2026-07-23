@extends('layouts.app')
@section('page-content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Import Missing Students - {{ $section->name }}</h2>
    </div>

    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('ftf-vouchers.index') }}">Vouchers</a>
        <div>/</div>
        <a href="{{ route('ftf-vouchers.show', $voucher) }}">Details</a>
        <div>/</div>
        <a href="{{ route('ftf-voucher.section.payments.index', [$voucher, $section]) }}">Payments</a>
        <div>/</div>
        <div>Import</div>
    </div>

    <!-- message -->
    <div class="md:w-3/4 mx-auto mt-4">
        <x-message></x-message>
    </div>

    <div class="bg-white p-4 md:p-8 rounded-2xl border border-gray-100 shadow-sm mt-4">
        <p class="text-xs text-slate-400 mb-6 uppercase tracking-widest font-bold">Select students to add to this voucher</p>

        <form action="{{ route('ftf-voucher.section.payments.import.post', [$voucher, $section]) }}" method="post">
            @csrf
            <div class="mb-4 flex items-center space-x-2">
                <input type="checkbox" id="check_all" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                <label for="check_all" class="text-sm font-bold text-slate-600">SELECT ALL</label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($students->sortBy('rollno') as $student)
                    <div class="checkable-row border rounded-xl p-3 flex items-start space-x-3 hover:border-teal-200 hover:bg-teal-50 transition-all">
                        <input type="checkbox" name="student_ids_array[]" value="{{ $student->id }}" class="mt-1 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <div>
                            <div class="text-sm font-bold text-gray-700">{{ $student->name }}</div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-tight">Roll# {{ $student->rollno }} | Adms# {{ $student->admission_no }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($students->isEmpty())
                <div class="py-12 text-center">
                    <i class="bi-check2-circle text-teal-100 text-5xl"></i>
                    <p class="text-slate-400 mt-2">All students from this section are already added to this voucher.</p>
                </div>
            @else
                <div class="mt-10 pt-6 border-t border-gray-50 flex justify-end">
                    <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-teal-700 shadow-lg shadow-teal-100 transition-all">
                        Add Selected Students
                    </button>
                </div>
            @endif
        </form>
    </div>
@endsection

@section('script')
    <script type="module">
        $(document).ready(function() {
            $('.checkable-row').click(function(e) {
                if (e.target.tagName !== 'INPUT') {
                    const cb = $(this).find('input[type="checkbox"]');
                    cb.prop('checked', !cb.prop('checked')).change();
                }
            });

            $('.checkable-row input').change(function() {
                if ($(this).prop('checked'))
                    $(this).closest('.checkable-row').addClass('border-teal-400 bg-teal-50 shadow-sm');
                else
                    $(this).closest('.checkable-row').removeClass('border-teal-400 bg-teal-50 shadow-sm');
            });

            $('#check_all').change(function() {
                const checked = $(this).prop('checked');
                $('.checkable-row input').each(function() {
                    $(this).prop('checked', checked).change();
                });
            });
        });
    </script>
@endsection

@extends('layouts.app')
@section('page-content')
    <h2>
        Invoice # {{ $bulkInvoice->id }}</h2>
    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('bulk-invoices.index') }}">Invoices</a>
        <div>/</div>
        <div>Edit</div>
    </div>

    <div class="grid md:w-4/5 mx-auto mt-6 bg-slate-100 md:p-5 p-4 rounded">
        <h2 class="col-span-full"><i class="bi-receipt text-slate-500"></i> {{ $bulkInvoice->title }} @ Rs.
            {{ $bulkInvoice->amount }}</h2>

        <div class="text-slate-500 text-sm">
            {{ $bulkInvoice->billingMonth() }} <br>
            {{ $totalPaid }}/{{ $totalPayable }}
            Paid

        </div>

    </div>

    <!-- message -->
    <div class="md:w-3/4 mx-auto">
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif
    </div>

    <div class="md:w-4/5 overflow-x-auto mx-auto bg-white md:p-8 p-4 rounded border mt-3">
        <table class="table-auto xs md:sm borderless w-full">
            <thead>
                <tr>
                    <th class="w-1/3 text-left">Student</th>
                    <th>Roll#</th>
                    <th>Fee</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fees as $fee)
                    <tr class="tr">
                        <td class="text-left"><a href="{{ route('fees.show', $fee) }}"
                                class="link">{{ $fee->student->name }}</a>
                            <br>
                            <span class="text-slate-400 text-xs">{{ $fee->student->father_name }}</span>
                        </td>
                        <td>{{ $fee->student->section->name }}-{{ $fee->student->rollno }}</td>
                        <td>{{ $fee->amount }}</td>
                        <td>
                            <div class="flex items-center justify-center">
                                @if ($fee->status)
                                    <span class="text-slate-100 text-xs bg-green-600 rounded-full px-2 py-1">Paid</span>
                                @else
                                    <span class="text-slate-100 text-xs bg-red-600 rounded-full p-1 w-4"></span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
@section('script')
    <script type="module">
        $('.checkable-row input').change(function() {
            if ($(this).prop('checked'))
                $(this).parents('.checkable-row').addClass('active')
            else
                $(this).parents('.checkable-row').removeClass('active')
        })

        $('#check_all').change(function() {
            if ($(this).prop('checked')) {
                $('.checkable-row input').each(function() {
                    $(this).prop('checked', true)
                    $(this).parents('.checkable-row').addClass('active')
                })
            } else {
                $('.checkable-row input').each(function() {
                    $(this).prop('checked', false)
                    $(this).parents('.checkable-row').removeClass('active')
                })
            }
        })
    </script>

    <script>
        function confirmDel(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    //submit corresponding form
                    form.submit();
                }
            });
        }
    </script>
@endsection

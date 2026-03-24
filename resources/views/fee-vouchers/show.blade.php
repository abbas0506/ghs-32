@extends('layouts.app')
@section('page-content')
    <h2>
        Voucher # {{ $voucher->id }}</h2>
    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('vouchers.index') }}">Vouchers</a>
        <div>/</div>
        <div>Edit</div>
    </div>

    <div class="grid md:grid-cols-3 md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border">
        <h2 class="mb-4 col-span-full"><i class="bi-receipt text-slate-500"></i> {{ $voucher->name }} @ Rs.
            {{ $voucher->sumOfPayableAmount() }}</h2>

        <div class="md:col-span-2 text-slate-400 text-sm">
            Removing voucher is a destructive activity.
            It will destory all fee info against this voucher.
            Remove only if you are sure!
        </div>
        <div class="flex items-center justify-end space-x-2 mt-4">
            <div class="flex w-8 h-8 rounded-full border justify-center items-center">
                <form action="{{ route('vouchers.destroy', $voucher) }}" method="post" onsubmit="return confirmDel(event)">
                    @csrf
                    @method('DELETE')
                    <button><i class="bx bx-trash text-red-600"></i></button>
                </form>
            </div>
            <div class="flex w-8 h-8 rounded-full border justify-center items-center">
                <a href="{{ route('vouchers.edit', $voucher) }}"><i class="bx bx-pencil text-green-600"></i></a>
            </div>

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
        <table class="table-auto borderless w-full">
            <thead>
                <tr>
                    <th class="w-2/3 text-left">Class</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sections as $section)
                    <tr class="tr">
                        <td class="text-left text-sm">
                            <a href="{{ route('voucher.section.payments.index', [$voucher, $section]) }}" class="link">
                                {{ $section->name }}
                            </a>
                        </td>
                        <td class="text-sm">
                            {{ $voucher->studentsWhoHavePaid($section->id)->count() }}
                            <span class="text-slate-400"> /
                                {{ $voucher->studentsFromSection($section->id)->count() }}</span>

                            @if ($voucher->studentsWhoHavePaidToday($section->id)->count())
                                <span class="text-xs ml-2">
                                    <i class="bi-arrow-up text-green-600"></i>
                                    {{ $voucher->studentsWhoHavePaidToday($section->id)->count() }}
                            @endif
                            </span>
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

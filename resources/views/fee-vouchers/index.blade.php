@extends('layouts.app')
@section('page-content')
    <h1>Fee Vouchers</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <div>Vouchers</div>
    </div>

    <div
        class="w-full md:w-4/5 mx-auto flex items-center gap-3 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50 mt-8">
        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-teal-100 text-teal-600">
            <i class="ri-money-rupee-circle-line text-lg"></i>
        </div>
        <div>
            <h2 class="font-semibold text-gray-800 text-sm leading-tight">Fee Vouchers</h2>
            <p class="text-xs text-gray-400">List of all fee vouchers</p>
        </div>
    </div>

    <div class="w-full md:w-4/5 mx-auto p-4 md:p-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center flex-wrap justify-between">
            <!-- search -->
            <div class="flex relative w-full md:w-1/3">
                <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                    oninput="search(event)">
                <i class="bx bx-search absolute top-2 right-2"></i>
            </div>
            @role('head')
                <a href="{{ route('fee-vouchers.create') }}"
                    class="fixed bottom-4 right-4 flex justify-center items-center bg-teal-400 hover:bg-teal-600 hover:cursor-pointer rounded-full w-12 h-12"><i
                        class="bi-plus-lg"></i></a>
            @endrole
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <table class="table-auto borderless w-full mt-8">
            <thead>
                <tr class="">
                    <th class="w-12">Sr</th>
                    <th class="text-left">Voucher Title</th>
                    <th class="w-20">Rs.</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($feeVouchers->sortByDesc('due_date') as $feeVoucher)
                    <tr class="tr">
                        <td>
                            <div class="ico cyan mx-auto">
                                {{ $loop->index + 1 }}
                            </div>
                        </td>
                        <td class="text-left">
                            <a href="{{ route('fee-vouchers.show', $feeVoucher) }}"
                                class="{{ $feeVoucher->isOpen() ? 'link' : '' }}">{{ $feeVoucher->description }}</a>
                            <br>
                            <span>Rs. {{ $feeVoucher->amount }} <span class="text-slate-400 text-xs">till
                                    {{ $feeVoucher->due_date->format('d-m-Y') }}</span>

                        </td>
                        <td>{{ $feeVoucher->sumOfPaidAmount() }} / {{ $feeVoucher->sumOfDueAmount() }} </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    </div>
    <script type="text/javascript">
        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }
    </script>
@endsection

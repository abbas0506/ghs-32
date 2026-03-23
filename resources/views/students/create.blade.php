@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>{{ $section->name }} </h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('sections.show', $section) }}">{{ $section->name }}</a>
            <div>/</div>
            <div>New Student</div>
        </div>

        <a href="{{ route('sections.show', $section) }}" class="mt-5"><i class="bi-arrow-left text-slate-400"></i></a>

        <div class="md:w-4/5 mx-auto">
            <!-- close button -->
            <a href="{{ route('sections.show', $section) }}" class="absolute top-2 right-2 p-2 rounded"><i
                    class="bi-x text-slate-400"></i></a>

            <div class="mt-6">
                <!-- page message -->
                @if ($errors->any())
                    <x-message :errors='$errors'></x-message>
                @else
                    <x-message></x-message>
                @endif

                <form action="{{ route('section.students.store', $section) }}" method='post' class="mt-4"
                    onsubmit="return validate(event)">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-3">
                        <h2 class="text-teal-500 col-span-full">Student Info</h2>
                        <div class="col-span-full">
                            <label>Name *</label>
                            <input type="text" name='name' class="custom-input" placeholder="Type here">
                        </div>
                        <div>
                            <label>Father</label>
                            <input type="text" name='father_name' class="custom-input" placeholder="Type here">
                        </div>
                        <div class="">
                            <label>B Form </label>
                            <input type="text" name='bform' class="custom-input bform" placeholder="Type here">
                        </div>
                        <div class="">
                            <label>Phone </label>
                            <input type="text" name='phone' class="custom-input phone" placeholder="Type here">
                        </div>
                        <div class="">
                            <label>Roll No *</label>
                            <input type="number" name='rollno' class="custom-input" placeholder="Type here">
                        </div>
                    </div>
                    <div class="text-center md:text-right mt-8">
                        <button type="submit" class="btn-teal rounded">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {

            $('.bform').on('input', function() {
                let value = $(this).val().replace(/\D/g, '').substring(0, 13);
                let formatted = value;
                if (value.length > 5) formatted = value.substring(0, 5) + '-' + value.substring(5);
                if (value.length > 12) formatted = formatted.substring(0, 13) + '-' + value.substring(12);
                $(this).val(formatted);
            });

            // Auto-insert dash for Phone
            $('.phone').on('input', function() {
                let value = $(this).val().replace(/\D/g, '').substring(0, 12);
                let formatted = value;
                if (value.length > 4) formatted = value.substring(0, 4) + '-' + value.substring(4);
                $(this).val(formatted);
            });
        });
    </script>
@endsection

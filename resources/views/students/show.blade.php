@extends('layouts.app')
@section('page-content')
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Student Profile</h2>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <a href="{{ route('sections.index') }}">Sections</a>
                <div>/</div>
                <a href="{{ route('sections.show', $section) }}" class="text-teal-600 hover:underline">{{ $section->name }}</a>
                <div>/</div>
                <span class="text-gray-500">Student #{{ $student->rollno }}</span>
            </div>
        </div>
        <div class="flex items-center justify-center gap-2">
             @can('update', $student)
                <a href="{{ route('section.students.edit', [$section, $student]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 font-semibold rounded-xl hover:bg-indigo-100 transition">
                    <i class="ri-pencil-line"></i>Edit
                </a>
            @endcan
            @can('delete', $student)
                <form action="{{ route('section.students.destroy', [$section, $student]) }}" method="post" onsubmit="return confirmDel(event)">
                    @csrf
                    @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition">
                        <i class="ri-delete-bin-line"></i> Delete
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Profile Header Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500/10 to-transparent h-32"></div>
            <div class="px-8 pb-8">
                <div class="relative flex flex-col md:flex-row items-center md:items-end gap-6 -mt-16">
                    <div class="w-32 h-32 rounded-xl bg-white p-1 shadow-xl">
                        <div class="w-full h-full rounded-2xl bg-gray-50 overflow-hidden border-2 border-white">
                            @if ($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                    <i class="ri-user-line text-4xl"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 text-center md:text-left mb-2">
                        <h1 class="text-xl font-semibold text-gray-800">{{ $student->name }}</h1>
                        <p class="text-gray-500 text-sm">S/O {{ $student->father_name }}</p>
                    </div>
                    <div class="md:mb-3">
                         <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-teal-100 text-teal-700">
                            {{ $student->status ? 'Active Student' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Academic Stats --}}
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-xl p-4 md:p-8 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="ri-graduation-cap-line text-teal-500"></i> Academic Details
                    </h3>
                    <div class="grid grid-cols-2 gap-y-8">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-1">Class</p>
                            <p class="text-gray-700 font-semibold">{{ $section->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-1">Admission #</p>
                            <p class="text-indigo-600 font-semibold">#{{ $student->admission_no ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-1">Roll No</p>
                            <p class="text-gray-700 font-semibold">{{ $student->rollno }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-1">Monthly Fee</p>
                            <div class="flex items-center gap-1">
                                <span class="text-gray-400 text-sm font-semibold">Rs.</span>
                                <span class="text-green-600 font-semibold">{{ number_format($student->fee) }}</span>
                            </div>
                        </div>
                         <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-1">Admission Date</p>
                            <p class="text-gray-700 font-semibold">{{ $student->admission_date ? $student->admission_date->format('d M, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="ri-user-settings-line text-indigo-400"></i> Personal Information
                    </h3>
                    <div class="grid grid-cols-2 gap-y-8">
                         <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-1">Gender</p>
                            <p class="text-gray-700 font-semibold uppercase tracking-wide">{{ $student->gender == 'm' ? 'Male' : 'Female' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-1">Date of Birth</p>
                            <p class="text-gray-700 font-semibold">{{ $student->dob ? $student->dob->format('d M, Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-1">B-Form</p>
                            <p class="text-gray-700 font-semibold tracking-wider">{{ $student->bform ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact & Address --}}
            <div class="space-y-6">
                 <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 h-full">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-widest mb-6 flex items-center gap-2">
                        <i class="ri-contacts-book-line text-orange-400"></i> Contact info
                    </h3>
                    <div class="space-y-8">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-2">Emergency Phone</p>
                            <a href="tel:{{ $student->phone }}" class="inline-flex items-center gap-2 text-teal-600 font-semibold hover:underline">
                                <i class="ri-phone-fill"></i> {{ $student->phone ?? 'N/A' }}
                            </a>
                        </div>
                         <div>
                            <p class="text-xs font-semibold text-gray-400 tracking-wide mb-2">Residential Address</p>
                            <div class="flex items-start gap-2 text-gray-600 text-sm leading-relaxed font-medium">
                                <i class="ri-map-pin-2-fill mt-1 text-gray-300"></i>
                                <span>{{ $student->address ?? 'No address provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function confirmDel(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            })
        }
    </script>
@endsection

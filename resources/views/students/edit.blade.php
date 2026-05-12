@extends('layouts.app')
@section('page-content')
    <style>
        .photo-box {
            width: 150px;
            height: 150px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 18px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .photo-upload-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .custom-file-upload {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .custom-file-upload:hover {
            background-color: #0056b3;
        }

        input[type="file"] {
            display: none;
        }
    </style>
    <div class="custom-container">
    <div class="custom-container space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('sections.index') }}" class="hover:text-teal-600 transition-colors">Classes</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('sections.show', $section) }}" class="hover:text-teal-600 transition-colors">{{ $section->name }}</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Edit Student</span>
                </div>
                <h2 class="text-base font-bold text-gray-800 leading-none">Edit Student Profile</h2>
            </div>
        </div>
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl mx-auto mt-8">
            <form id="studentForm" action="{{ route('section.students.update', [$section, $student]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Header Section with Photo --}}
                <div class="bg-gradient-to-r from-teal-500/10 to-transparent p-8 flex flex-col md:flex-row items-center gap-8 border-b border-gray-100">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-3xl bg-white shadow-lg border-4 border-white overflow-hidden" id="photoPreview">
                            @if ($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" id="photoImage" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                    <i class="ri-user-line text-4xl"></i>
                                    <span class="text-[10px] font-bold mt-1 uppercase tracking-wider">No Photo</span>
                                </div>
                            @endif
                        </div>
                        <label for="photo" class="absolute -bottom-2 -right-2 bg-teal-500 text-white w-10 h-10 rounded-2xl flex items-center justify-center cursor-pointer shadow-lg hover:bg-teal-600 transition group-hover:scale-110">
                            <i class="ri-camera-line text-lg"></i>
                            <input type="file" id="photo" name="photo" accept="image/*" class="hidden" onchange="previewSelectedPhoto(event)">
                        </label>
                    </div>

                    <div class="text-center md:text-left">
                        <h2 class="text-base font-bold text-gray-800">{{ $student->name }}</h2>
                        <p class="text-gray-500 text-xs mt-1 font-medium">{{ $section->name }} — Roll #{{ $student->rollno }}</p>
                        <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-2">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-100 text-teal-800">
                                {{ $student->status ? 'Active Student' : 'Inactive' }}
                            </span>
                            @if($student->admission_no)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800">
                                    Adm #{{ $student->admission_no }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    {{-- Basic Information --}}
                    <div>
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-1">
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Student Name</label>
                                <input type="text" name="name" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition" value="{{ old('name', $student->name) }}" required>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Father Name</label>
                                <input type="text" name="father_name" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition" value="{{ old('father_name', $student->father_name) }}" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Gender</label>
                                    <select name="gender" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition">
                                        <option value="m" {{ $student->gender == 'm' ? 'selected' : '' }}>Male</option>
                                        <option value="f" {{ $student->gender == 'f' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Date of Birth</label>
                                    <input type="date" name="dob" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition" value="{{ old('dob', $student->dob?->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">B-Form / CNIC</label>
                                <input type="text" name="bform" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition bform" value="{{ old('bform', $student->bform) }}" placeholder="00000-0000000-0">
                            </div>
                        </div>
                    </div>

                    {{-- Academic & Admission --}}
                    <div>
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Academic & Admission</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700">
                             <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Admission No <span class="text-teal-500 font-bold">*</span></label>
                                <input type="text" name="admission_no" value="{{ old('admission_no', $student->admission_no) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition" placeholder="Required for indexing">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Roll No</label>
                                <input type="number" name="rollno" value="{{ old('rollno', $student->rollno) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition" required>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Monthly Fee (Rs.)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] font-bold">Rs.</span>
                                    <input type="number" name="fee" value="{{ old('fee', $student->fee) }}" class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Admission Date</label>
                                <input type="date" name="admission_date" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition" value="{{ old('admission_date', $student->admission_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Contact Information --}}
                    <div>
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Emergency Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition phone" placeholder="0000-0000000">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-2">Residential Address</label>
                                <textarea name="address" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition resize-none">{{ old('address', $student->address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="bg-gray-50 px-8 py-6 flex items-center justify-between">
                    <a href="{{ route('sections.show', $section) }}" class="text-xs font-bold text-gray-400 hover:text-teal-600 transition flex items-center gap-2 uppercase tracking-widest">
                        <i class="ri-arrow-left-line"></i>
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-1 bg-gradient-to-r from-teal-500 to-green-500 text-white text-xs font-bold rounded-2xl shadow-lg shadow-teal-500/20 hover:scale-105 transition duration-300 uppercase tracking-widest">
                        <i class="ri-save-3-line text-lg"></i> Update
                    </button>
                </div>
            </form>
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
    <script>
        function previewSelectedPhoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const photoBox = document.getElementById('photoPreview');
                photoBox.style.backgroundImage = `url('${reader.result}')`;
                photoBox.style.backgroundSize = 'cover';
                photoBox.style.backgroundPosition = 'center';
                photoBox.textContent = ''; // Remove "Photo" placeholder
            }
            reader.readAsDataURL(event.target.files[0]);
        }
        // show error if file size exceeds 1MB
        const form = document.getElementById('studentForm');
        const photoInput = document.getElementById('photo');
        const errorText = document.getElementById('photo-error');

        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.size > 1024 * 1024) {
                errorText.classList.remove('hidden');
            } else {
                errorText.classList.add('hidden');
            }
        });

        form.addEventListener('submit', function(e) {
            const file = photoInput.files[0];

            if (file && file.size > 1024 * 1024) { // 1MB = 1024 * 1024 bytes
                e.preventDefault(); // stop form submission
                errorText.classList.remove('hidden'); // show error
                Swal.fire({
                    title: "Warning",
                    text: "Photo size exceeds 1MB",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 1500

                });
            } else {
                errorText.classList.add('hidden'); // hide error if valid
            }
        });
    </script>
@endsection

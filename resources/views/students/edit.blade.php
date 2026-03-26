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
        <!-- Title     -->
        <h1>Edit Student</h1>
        <div class="flex items-center">
            <div class="flex-1">
                <div class="bread-crumb">
                    <a href="{{ url('/') }}">Home</a>
                    <div>/</div>
                    <a href="{{ route('sections.show', $section) }}">{{ $section->name }}</a>
                    <div>/</div>
                    <div>{{ $student->rollno }}</div>
                    <div>/</div>
                    <div>Edit</div>
                </div>
            </div>
        </div>
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="md:col-span-2 photo-upload-wrapper">
            <div class="photo-box" id="photoPreview">
                @if ($student->photo)
                    <!-- adjust $user or $student as needed -->
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Current Photo" id="photoImage" class="rounded"
                        width="100" height="100">
                @else
                    Photo
                @endif
            </div>
            <!-- Custom Upload Button -->
            <label for="photo" class="custom-file-upload">Upload Photo</label>
            <input type="file" id="photo" name="photo" accept="image/*" onchange="previewSelectedPhoto(event)">
            <label id="photo-error" class="text-red-500 mt-1 hidden">File size exceeds 1MB.</label>
        </div>

        <div class="w-full md:w-4/5 mx-auto md:p-8 mt-8">
            <form action="{{ route('section.students.update', [$section, $student]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <!-- student info -->
                <div class="grid md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label for="">Student Name</label>
                        <input type="text" name="name" class="custom-input" placeholder="Student name"
                            value="{{ $student->name }}">
                    </div>
                    <div class="md:col-span-2">
                        <label for="">Father Name</label>
                        <input type="text" name="father_name" class="custom-input" placeholder="Father name"
                            value="{{ $student->father_name }}">
                    </div>

                    <div>
                        <label for="">Phone No</label>
                        <input type="text" name="phone" class="custom-input phone" placeholder="Phone No."
                            value="{{ $student->phone }}">
                    </div>
                    <div>
                        <label for="">B Form</label>
                        <input type="text" name="bform" class="custom-input bform" placeholder="B Form"
                            value="{{ $student->bform }}">
                    </div>
                    <div>
                        <label for="">Roll No</label>
                        <input type="text" name="rollno" value="{{ $student->rollno }}" placeholder="Type here"
                            class="custom-input">
                    </div>
                    <div>
                        <label for="">Fee</label>
                        <input type="text" name="fee" value="{{ $student->fee }}" placeholder="Type here"
                            class="custom-input">
                    </div>
                    <div class="md:col-span-2">
                        <label for="">Address</label>
                        <input type="text" name="address" class="custom-input" placeholder="Address"
                            value="{{ $student->address }}">
                    </div>
                </div>
                <div class="text-center mt-8">
                    <button class="btn-teal rounded py-3">Update student</button>
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

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
                    <a href="{{ route('users.index') }}">users</a>
                    <div>/</div>
                    <div>{{ $user->id }}</div>
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


        <form action="{{ route('users.update', $user) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @csrf
            <div class="photo-upload-wrapper">
                <!-- Placeholder Photo Box -->
                <div class="photo-box" id="photoPreview">
                    @if ($user->profile->photo)
                        <!-- adjust $user or $student as needed -->
                        <img src="{{ asset('storage/' . $user->profile->photo) }}" alt="Current Photo" id="photoImage"
                            class="rounded" width="100" height="100">
                    @else
                        Photo
                    @endif
                </div>

                <!-- Custom Upload Button -->
                <label for="photo" class="custom-file-upload">Upload Your Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*" onchange="previewSelectedPhoto(event)">
                <label id="photo-error" class="text-red-500 mt-1 hidden">File size exceeds 1MB.</label>
            </div>
            <div class="w-full md:w-4/5 mx-auto p-0 md:p-8 mt-8">
                <!-- page error message -->
                @if ($errors->any())
                    <x-message :errors='$errors'></x-message>
                @else
                    <x-message></x-message>
                @endif

                <!-- <hr class="mt-3"> -->
                <div class="grid md:grid-cols-2 gap-3">
                    <h2 class="col-span-full mb-3 text-decoration underline">Basic Info</h2>
                    <div class="">
                        <label for="">Name <i class="bi-person"></i></label>
                        <input type="text" name="name" class="custom-input fancy-focus" placeholder="Your good name"
                            value="{{ $user->profile->name }}" required>
                    </div>
                    <div class="">
                        <label for="">Short Name</label>
                        <input type="text" name="short_name" class="custom-input fancy-focus" placeholder="Short name"
                            value="{{ $user->profile->short_name }}">
                    </div>
                    <div class="">
                        <label for="">Father Name</label>
                        <input type="text" name="father_name" class="custom-input fancy-focus" placeholder="Father name"
                            value="{{ $user->profile->father_name }}">
                    </div>
                    <div>
                        <label for="">CNIC <i class="bi-person-vcard"></i></label>
                        <input type="text" name="cnic" id='cnic' class="custom-input fancy-focus cnic"
                            placeholder="Type your CNIC" value="{{ $user->profile->cnic }}">
                    </div>
                    <div>
                        <label for="">Email <i class="bi-at"></i></label>
                        <input type="email" name="email" id='email' class="custom-input fancy-focus"
                            placeholder="Email" value="{{ $user->email }}">
                    </div>
                    <div>
                        <label for="">Phone No <i class="bi-whatsapp"></i></label>
                        <input type="text" name="phone" id='phone' class="custom-input fancy-focus phone"
                            placeholder="Personal Phone" value="{{ $user->profile->phone }}">
                    </div>

                    <div class="md:col-span-2">
                        <label for="">Home Address <i class="bi-house"></i></label>
                        <input type="text" name="address" id='address' class="custom-input fancy-focus"
                            placeholder="Address" value="{{ $user->profile->address }}">
                    </div>

                    <div>
                        <label for="qualification">Qualification <i class="bi-layers"></i></label>
                        <input type="text" name="qualification" class="custom-input fancy-focus"
                            placeholder="e.g MA Urdu" value="{{ $user->profile->qualification }}">
                    </div>

                    <div>
                        <label for="senioirty">Seniority # <i class="bi-numeric"></i></label>
                        <input type="number" name="seniority" class="custom-input fancy-focus" placeholder="type here"
                            value="{{ $user->profile->seniority }}">
                    </div>
                    <div class="flex justify-center space-x-3 text-center mt-8 md:col-span-2">
                        <a href="{{ url('/') }}" class="btn-gray rounded py-2 px-5">Cancel </a>
                        <button class="btn-blue rounded py-2 px-5">Submit </button>
                    </div>
                </div>
        </form>
    </div>
@endsection
@section('script')
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

@extends('layouts.app')
@section('page-content')
    <style>
        .fancy-focus:focus {
            border-color: #0d9488;
            /* teal-600 */
            background-color: transparent;
            animation: waveGlow 1s ease-in-out infinite;
            outline: none;
        }

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
        <h1>New user </h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('users.index') }}">users</a>
            <div>/</div>
            <div>New</div>
        </div>

        <div class="md:w-4/5 mx-auto mt-8">

            <div class="w-full">
                <!-- page message -->
                @if ($errors->any())
                    <x-message :errors='$errors'></x-message>
                @else
                    <x-message></x-message>
                @endif


                <form action="{{ route('users.store') }}" method='post' class="mt-4" enctype="multipart/form-data"
                    onsubmit="return validate(event)">
                    @csrf
                    <div class="photo-upload-wrapper">
                        <!-- Placeholder Photo Box -->
                        <div class="photo-box" id="photoPreview">Photo</div>

                        <!-- Custom Upload Button -->
                        <label for="photo" class="custom-file-upload">Upload Your Photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                            onchange="previewSelectedPhoto(event)">
                        <label id="photo-error" class="text-red-500 mt-1 hidden">File size exceeds 1MB.</label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label>Name *</label>
                            <input type="text" name='name' class="custom-input" placeholder="Type here">
                        </div>
                        <div>
                            <label>Father</label>
                            <input type="text" name='father_name' class="custom-input" placeholder="Type here">
                        </div>
                        <div class="">
                            <label>CNIC</label>
                            <input type="text" name='cnic' class="custom-input cnic" placeholder="Type here">
                        </div>
                        <div class="">
                            <label>Phone</label>
                            <input type="text" name='phone' class="custom-input phone" placeholder="Type here">
                        </div>
                        <div class="">
                            <label>Email*</label>
                            <input type="email" name='email' class="custom-input" placeholder="Type here">
                        </div>
                        <div class="">
                            <label>Salary*</label>
                            <input type="number" name='salary' class="custom-input" placeholder="Type here">
                        </div>
                    </div>
                    <div class="flex justify-center items-center space-x-2 mt-8">
                        <!-- close button -->
                        <a href="{{ route('users.index') }}" class="btn-gray rounded">Cancel</a>

                        <button type="submit" class="btn-teal rounded">Create Now</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {

            $('.cnic').on('input', function() {
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
        const form = document.getElementById('applicationForm');
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
            if (!photoInput.files.length) {
                e.preventDefault(); // Stop form submission
                Swal.fire({
                    title: "Warning",
                    text: "Please select a photo",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 1500

                });
                photoInput.focus();
            }
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
